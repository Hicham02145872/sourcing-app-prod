# Rapport de test — Règles SLA (autolock nav admin)

**Date** : 2026-09-12 · **Environnement** : preprod (`https://preprod.fastsourcingbrothers.com`)
**HEAD** : `a7a83c6` (branch `feature/production-setup`)
**Comptes test** : admin `test-admin@example.com` (id 11, rôle admin)

---

## Périmètre du mécanisme

- Feature flag : `sla_deadlines_autolock` → **actif**
- Config (`config/fsb.php`) :

| Entité | Statut | Délai |
|---|---|---|
| SourcingRequest | `in_review` | 24 h |
| SourcingRequest | `negotiating` | 24 h |
| SourcingOrder | `paid` | 24 h |
| SourcingOrder | `in_transit_china` | 48 h |

- Command cron : `workflow:check-deadlines` (hourly, `bootstrap/app.php` via le crontab)
- Middleware : `EnforceSlaNavigationLock` (alias `sla.lockout`, appliqué au groupe `admin.*`)

---

## R1 — Request `in_review` dépassée → création de devis

### Setup
Requête **#11** (admin 11, `in_review`) → `status_changed_at = now() - 25 h`.

### Résultat commande
```
Request status 'in_review' (deadline 24h): 1 request(s) flagged.
1 notification(s) sent.
```

### Redirections / CTA live (utilisateur admin 11)
| Page | Attendu | Résultat |
|---|---|---|
| `/admin/sourcing-orders` | rediriger vers création de devis | ✅ `→ /admin/quotations/create/11` |
| Banner dashboard → CTA | `/admin/quotations/create/11` | ✅ 2 liens présents |

---

## R2 — Request `negotiating` avec devis → édition du devis

### Setup
Requête **#15** (admin 11, `negotiating`, devis **#14** lié) → `status_changed_at = now() - 25 h`.

### Résultat commande
```
Request status 'negotiating' (deadline 24h): 1 request(s) flagged.
1 notification(s) sent.
```

### Redirections / CTA live
| Page | Attendu | Résultat |
|---|---|---|
| `/admin/sourcing-orders` | rediriger vers édition du devis | ✅ `→ /admin/quotations/14/edit` |
| Banner dashboard → CTA | `/admin/quotations/14/edit` | ✅ présent |
| `/admin/quotations/14/edit` | accessible | ✅ 200 |

---

## R2-bis — Request `negotiating` **sans** devis → page de la requête (fallback)

### Setup
Requête **#16** (admin 11, `negotiating`, `quotation_id = NULL`) → `status_changed_at = now() - 25 h`.

### Résultat commande
```
Request status 'negotiating' (deadline 24h): 1 request(s) flagged.
1 notification(s) sent.
```

### Redirections / CTA live
| Page | Attendu | Résultat |
|---|---|---|
| `/admin/quotations` | rediriger vers la requête | ✅ `→ /admin/sourcing-requests/16` |
| Banner dashboard → CTA | `/admin/sourcing-requests/16` | ✅ présent |
| Routes `admin.sourcing-requests.*` | restent autorisées (ligne 112) | ✅ non bloquées |

---

## R3 — Order `paid` dépassée → page de la commande + escalade

### Setup
Order **#6** (admin 11, `paid`) → `status_changed_at = now() - 25 h`.

### Résultat commande
```
Order status 'paid' (deadline 24h): 1 order(s) flagged.
6 notification(s) sent.
```
→ 1 notif (admin assigné #11) + **5 notifs d'escalade aux super-admins** (les 5 comptes `super_admin`).

### Redirections / CTA live
| Page | Attendu | Résultat |
|---|---|---|
| `/admin/sourcing-orders/6` | page commande accessible | ✅ 200 |
| `/admin/sourcing-orders` | reste autorisé (routes orders ouvertes, ligne 114-124) | ✅ 200 |
| `/admin/quotations` | bloqué → rediriger vers l'ordre | ✅ `→ /admin/sourcing-orders/6` |
| Banner dashboard → CTA | `?status=paid&overdue=1&admin_id=me` | ✅ présent |

---

## R4 — Order `in_transit_china` dépassée → page commande + zone preuves

### Setup
Order **#7** (admin 11, `in_transit_china`) → `status_changed_at = now() - 49 h`.

### Résultat commande
```
Order status 'in_transit_china' (deadline 48h): 1 order(s) flagged.
1 notification(s) sent.
```

### Redirections / CTA live
| Page | Attendu | Résultat |
|---|---|---|
| `/admin/sourcing-orders/7` | page commande accessible | ✅ 200 |
| `/admin/quotations` | bloqué → rediriger vers l'ordre | ✅ `→ /admin/sourcing-orders/7` |
| Zone « En transit depuis la Chine / PREUVES » | visible | ✅ H3 + badge « Evidence » |

### Zone preuves (inputs Livewire rendus)
| Champ | ID | Placeholder | Présent |
|---|---|---|---|
| Suivi Chine | `chinaTrackingNumber` | `Ex: LP001234567890` | ✅ `wire:model.defer` |
| N° suivi local | `evidence_tracking_number` | `Ex: ME49508327` | ✅ |
| Transporteur | `evidence_tracking_carrier` | `Ex: Faster.ae, DHL...` | ✅ |
| Photo étiquette colis | `packageLabelPhoto` | JPG/PNG/WEBP max 5 MB | ✅ |
| Boutons | — | « Enregistrer les preuves » / « Enregistrer le suivi » | ✅ |

Livewire hydraté (snapshot + `wire:id` présents). Warning console pré-existant : « published Livewire assets are out of date » — non bloquant pour le rendu, à traiter séparément.

---

## Priorités entre cibles (ordre de `mostUrgentTarget`)

1. Request `in_review` (priorité 0)
2. Request `negotiating` (priorité 1)
3. Order `paid` (priorité 2)
4. Order `in_transit_china` (priorité 3)

Vérifié en N-1 lors du test précédent (non re-testé ici) : quand plusieurs éléments sont restreints, la priorité ci-dessus + le plus ancien `status_changed_at` l'emportent.

---

## Synthèse

| Règle | Flag commande | Notification | Redirection middleware | CTA dashboard |
|---|---|---|---|---|
| R1 `in_review` | ✅ | ✅ (1) | ✅ → `quotations.create/11` | ✅ |
| R2 `negotiating` + devis | ✅ | ✅ (1) | ✅ → `quotations.edit/14` | ✅ |
| R2-bis `negotiating` sans devis | ✅ | ✅ (1) | ✅ → `sourcing-requests.show/16` | ✅ |
| R3 `paid` | ✅ | ✅ (1 admin + 5 super-admins) | ✅ → order page | ✅ |
| R4 `in_transit_china` | ✅ | ✅ (1) | ✅ → order page | — (voir R3) |

**Toutes les règles SLA sont validées en live sur preprod.**

---

## Fix appliqué lors des tests (commit `481a8a6`)

Le pull `b2621a0` faisait crasher le dashboard admin en 500 (`Unknown column 'quotation_id' in 'field list'`) : `AdminDashboardController.php` sélectionnait une colonne inexistante sur `sourcing_requests` (le champ vit sur `quotations`, relation `hasOne`).

Correctif : `select('id','status')->with('quotation:id')` + `$request->quotation?->id`. **Pushé** sur `origin/feature/production-setup` → la **production doit pull** pour propager le correctif.

---

## Nettoyage effectué

- Requêtes #11, #15, #16 et orders #6, #7 : `is_restricted_due_to_delay = false` + `status_changed_at = now()`.
- Seules restent restreintes les requêtes #1-#5 (admin 5, historique antérieur, non imputables à ces tests).
- `workflow:check-deadlines` final : `0 flagged, 0 notification`.