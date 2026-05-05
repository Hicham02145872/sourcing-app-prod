# Brainstorm : YNPS comme société d’expédition, GCC/UPS comme transporteurs

## Contexte

- Le **client** parle d’une **société d’expédition « YNPS »**.
- En réalité, YNPS utilise **GCC** et **UPS** comme **transporteurs** (ceux qu’on a déjà dans l’app pour le tracking).
- Donc : **YNPS** = nom commercial / partenaire ; **GCC / UPS** = transporteurs techniques pour le suivi.

---

## Ce qu’on a aujourd’hui dans l’app

| Concept | Où c’est stocké | Rôle |
|--------|------------------|------|
| **Société d’expédition** | `sourcing_orders.shipping_company_id` → `shipping_companies` | Partenaire / société (ex. Faster, une autre société). |
| **Transporteur (carrier)** | `sourcing_orders.tracking_carrier` (texte libre) | Utilisé pour **détecter le provider** de tracking (Faster, GCC, UPS, etc.) et pour l’affichage client. |

- Côté **client** : on affiche aujourd’hui  
  `tracking_carrier ?: shippingCompany->name`  
  → si tu mets `tracking_carrier = "GCC"`, le client voit « GCC », pas « YNPS ».
- Côté **tracking** : `detectProvider()` utilise `tracking_carrier` (ou le format du numéro) pour choisir l’API (faster/gcc → Faster, ups → UPS).

---

## Objectif

- **Client** : voit toujours **YNPS** comme société d’expédition.
- **App / tracking** : utilise **GCC** ou **UPS** selon le colis (numéro de suivi GCC ou UPS).

---

## Pistes de solution

### Option 1 – YNPS en société + carrier uniquement pour le tracking (sans changer l’affichage)

- Créer une **ShippingCompany « YNPS »**.
- Pour chaque commande YNPS, l’admin remplit :
  - **Shipping company** = YNPS
  - **Tracking number** = numéro réel (format GCC ou UPS)
  - **Carrier** = **GCC** ou **UPS** (pour le routing du tracking uniquement).
- **Changement côté affichage client** : pour « Société de transport », afficher **toujours** le nom de la **Shipping Company** quand `shipping_company_id` est renseigné, et n’utiliser `tracking_carrier` que pour l’API de tracking (pas pour le libellé client).
  - Résultat : client voit « YNPS », tracking continue de marcher avec GCC/UPS.

**Avantages** : pas de nouvelle table, pas de nouveau champ. Une règle d’affichage à ajuster.  
**Inconvénients** : si un jour tu veux afficher « Transporteur technique : GCC » quelque part, il faudra le faire explicitement.

---

### Option 2 – Distinguer « nom affiché » et « provider de tracking »

- Garder **Shipping Company = YNPS** (nom commercial).
- Utiliser **tracking_carrier** uniquement pour le **provider technique** (GCC, UPS, Faster, etc.).
- Règle d’affichage client :
  - **Société de transport (affichée)** = `shippingCompany->name` si présent, sinon `tracking_carrier`.
  - **Tracking** = toujours via `tracking_carrier` + numéro (comme aujourd’hui).

En pratique c’est la même idée que l’option 1, en la formalisant : « nom affiché = shipping company ; technique = tracking_carrier ».

---

### Option 3 – Lier les sociétés aux transporteurs (modèle plus riche)

- Introduire une notion **Transporteur** (GCC, UPS, Faster, etc.) : table `carriers` ou liste dans config.
- **Shipping companies** peuvent avoir **plusieurs transporteurs** (ex. YNPS → GCC, UPS).
- En commande : **shipping_company_id** = YNPS, **carrier_id** (ou `tracking_carrier` conservé) = GCC ou UPS pour ce colis.
- Affichage client = nom de la shipping company (YNPS) ; tracking = provider associé au carrier (GCC/UPS).

**Avantages** : clair pour plusieurs sociétés qui utilisent les mêmes transporteurs (YNPS + une autre qui utilise aussi GCC/UPS).  
**Inconvénients** : migrations, écrans admin (sélection transporteur selon la société), plus de complexité.

---

### Option 4 – YNPS + détection automatique GCC vs UPS par le numéro

- **Shipping company** = YNPS.
- **tracking_carrier** : optionnel pour YNPS. Si vide, on déduit le provider **uniquement par le format du numéro** (ex. 1Z… → UPS, ME… → Faster/GCC).
- Affichage client = toujours **YNPS** (comme option 1/2).

**Avantages** : moins de saisie pour l’admin si les numéros sont toujours reconnaissables.  
**Inconvénients** : il faut que les préfixes soient stables et documentés ; sinon il faut quand même pouvoir forcer GCC ou UPS.

---

## Recommandation courte

- **Court terme** : **Option 1 (ou 2)**  
  - Créer la société **YNPS**.  
  - Saisir **tracking_carrier = "GCC"** ou **"UPS"** selon le colis.  
  - Modifier l’affichage client pour que « Société de transport » = **nom de la shipping company** (ex. YNPS) quand elle est renseignée, et réserver `tracking_carrier` au tracking (GCC/UPS).
- **Plus tard**, si tu as beaucoup de sociétés avec plusieurs transporteurs : envisager l’**option 3** (table carriers, lien société ↔ transporteurs).

Si tu veux, on peut détailler la règle d’affichage exacte dans la vue (Blade) et les champs à utiliser dans `SourcingOrder` pour que le client ne voie jamais GCC/UPS, seulement YNPS.
