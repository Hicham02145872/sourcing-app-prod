# Plan d'intégration de la langue arabe — FastSourcingBrothers

> **Objectif :** Intégrer l'arabe (`ar`) comme langue à part entière sur l'ensemble de la plateforme — page d'accueil, espace client, authentification, emails, PDFs — avec support RTL complet.

---

## Table des matières

1. [Architecture du système de langue](#1-architecture)
2. [État actuel](#2-état-actuel)
3. [Phase 1 — Page Welcome ✅](#phase-1--page-welcome-)
4. [Phase 2 — Layouts globaux & RTL système](#phase-2--layouts-globaux--rtl-système)
5. [Phase 3 — Pages d'authentification](#phase-3--pages-dauthentification)
6. [Phase 4 — Espace client (21 vues)](#phase-4--espace-client-21-vues)
7. [Phase 5 — Pages statiques / légales](#phase-5--pages-statiques--légales)
8. [Phase 6 — Emails & notifications](#phase-6--emails--notifications)
9. [Phase 7 — PDFs & exports](#phase-7--pdfs--exports)
10. [Phase 8 — Panel Admin (scope limité)](#phase-8--panel-admin-scope-limité)
11. [Référence technique](#9-référence-technique)
12. [Checklist de déploiement](#10-checklist-de-déploiement)

---

## 1. Architecture

### Comment fonctionne le système de locale

```
Requête utilisateur
        │
        ▼
  SetLocale Middleware  ──────────────────────────────────────┐
        │                                                      │
        │  1. Segment URL /en, /fr, /ar ?  ──→  Appliquer     │
        │  2. Session::get('locale') ?     ──→  Appliquer     │
        │  3. Accept-Language header ?     ──→  Détecter      │
        │  4. Fallback config('app.locale')                   │
        │                                                      │
        ▼                                                      │
  App::setLocale($locale)                                     │
  Session::put('locale', $locale)  ◄──────────────────────── ┘
        │
        ▼
  __('clé.traduction')  →  lang/ar.json
```

### Fichiers clés

| Fichier | Rôle |
|---|---|
| `app/Http/Middleware/SetLocale.php` | Détecte et applique la locale |
| `bootstrap/app.php` | Enregistre le middleware globalement |
| `routes/web.php` | Routes `/en`, `/fr`, `/ar` + redirect `/` |
| `app/Http/Controllers/LanguageController.php` | Switch manuel via `/language/{locale}` |
| `lang/ar.json` | Traductions arabes |
| `lang/fr.json` | Traductions françaises |
| `lang/en.json` | Traductions anglaises (clés de référence) |

### Locales supportées

```php
$supportedLocales = ['en', 'fr', 'ar'];
```

---

## 2. État actuel

### ✅ Terminé

| Élément | Détail |
|---|---|
| Routing propre | `/`, `/en`, `/fr`, `/ar` opérationnels |
| Détection navigateur | Via `Accept-Language` header |
| Session persistante | `Session::put('locale', ...)` |
| Switcher UI navbar | Pill EN / FR / AR — desktop + mobile |
| Page Welcome complète | 100% traduit EN/FR/AR |
| `<html lang dir>` | Dynamique sur welcome.blade.php |
| RTL CSS welcome | Support basique arabe |
| Typed.js | Mots animés traduits en arabe |
| `ar.json` | 60 clés (welcome uniquement) |

### ❌ Reste à faire

- Layouts `app.blade.php`, `guest.blade.php` → attribut `dir` manquant
- 21 vues client → aucune traduction arabe
- 5 pages auth → aucune traduction arabe
- Pages légales / statiques → anglais uniquement
- Emails transactionnels → anglais uniquement
- PDFs (factures, labels) → LTR uniquement
- Admin panel → non ciblé (en, fr suffisent)

---

## Phase 1 — Page Welcome ✅

**Statut : TERMINÉ**

### Ce qui a été fait

```
welcome.blade.php
├── <html lang="{{ app()->getLocale() }}" dir="{{ ... }}">
├── CSS RTL dédié (section [dir="rtl"])
├── Language switcher navbar (EN|FR|AR pill)
├── Language switcher mobile menu
├── Tous les textes wrappés dans __()
└── typed.js strings → json_encode([__('welcome.typed.x')])
```

### Clés de traduction créées (`lang/ar.json`)

```
welcome.nav.*          → 7 clés (navbar)
welcome.hero.*         → 7 clés (hero section)
welcome.stats.*        → 3 clés (compteurs)
welcome.typed.*        → 5 clés (animation typed.js)
welcome.trust_bar      → 1 clé
welcome.process.*      → 8 clés (workflow)
welcome.ops.*          → 8 clés (opérations)
welcome.benefits.*     → 6 clés (avantages)
welcome.testimonials.* → 4 clés (avis)
welcome.cta.*          → 3 clés (call to action)
welcome.footer.*       → 8 clés (pied de page)
```

---

## Phase 2 — Layouts globaux & RTL système

**Statut : ❌ À FAIRE — Priorité HAUTE**

### Problème actuel

Les layouts `app.blade.php` et `guest.blade.php` ont déjà `lang="{{ app()->getLocale() }}"` mais **manquent de `dir`** et de la police arabe.

### Fichiers à modifier

```
resources/views/layouts/app.blade.php
resources/views/layouts/guest.blade.php
resources/views/layouts/legal.blade.php
resources/views/layouts/error-layout.blade.php
```

### Changements à apporter

**1. Tag `<html>` — ajouter `dir`**

```blade
{{-- AVANT --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

{{-- APRÈS --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
```

**2. Police arabe dans `<head>`**

```html
@if(app()->getLocale() === 'ar')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<style>
  [dir="rtl"] * { font-family: 'Cairo', sans-serif !important; }
</style>
@endif
```

**3. CSS RTL global à ajouter dans `app.blade.php`**

```css
/* RTL Global — layouts/app.blade.php */
[dir="rtl"] .text-left  { text-align: right; }
[dir="rtl"] .text-right { text-align: left;  }
[dir="rtl"] .ml-auto    { margin-left: 0;  margin-right: auto; }
[dir="rtl"] .mr-auto    { margin-right: 0; margin-left: auto;  }
[dir="rtl"] .pl-4       { padding-left: 0;  padding-right: 1rem; }
[dir="rtl"] .pr-4       { padding-right: 0; padding-left: 1rem; }
[dir="rtl"] .border-l   { border-left: 0;   border-right-width: 1px; }
[dir="rtl"] .border-r   { border-right: 0;  border-left-width: 1px;  }
[dir="rtl"] .rounded-l  { border-radius: 0 0.25rem 0.25rem 0; }
[dir="rtl"] .rounded-r  { border-radius: 0.25rem 0 0 0.25rem; }
[dir="rtl"] .space-x-4 > * + * { --tw-space-x-reverse: 1; }
[dir="rtl"] .flex-row   { flex-direction: row-reverse; }
[dir="rtl"] input, [dir="rtl"] textarea { text-align: right; }
[dir="rtl"] select      { text-align: right; direction: rtl; }
```

**4. Sidebar navigation — inverser les icônes**

```blade
{{-- Dans navigation.blade.php --}}
<svg class="{{ app()->getLocale() === 'ar' ? 'ml-2' : 'mr-2' }} ...">
```

---

## Phase 3 — Pages d'authentification

**Statut : ❌ À FAIRE — Priorité HAUTE**

### Fichiers concernés (5 vues)

```
resources/views/auth/
├── login.blade.php
├── register.blade.php
├── forgot-password.blade.php
├── reset-password.blade.php
└── verify-email.blade.php
```

### Clés à ajouter dans `ar.json`

```json
{
  "auth.login.title": "تسجيل الدخول",
  "auth.login.email": "البريد الإلكتروني",
  "auth.login.password": "كلمة المرور",
  "auth.login.remember": "تذكرني",
  "auth.login.forgot": "نسيت كلمة المرور؟",
  "auth.login.button": "تسجيل الدخول",
  "auth.login.no_account": "ليس لديك حساب؟",
  "auth.login.register_link": "إنشاء حساب",

  "auth.register.title": "إنشاء حساب جديد",
  "auth.register.name": "الاسم الكامل",
  "auth.register.email": "البريد الإلكتروني",
  "auth.register.phone": "رقم الهاتف",
  "auth.register.password": "كلمة المرور",
  "auth.register.confirm": "تأكيد كلمة المرور",
  "auth.register.button": "إنشاء الحساب",
  "auth.register.have_account": "لديك حساب بالفعل؟",

  "auth.forgot.title": "إعادة تعيين كلمة المرور",
  "auth.forgot.desc": "أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين.",
  "auth.forgot.button": "إرسال الرابط",

  "auth.verify.title": "تحقق من بريدك الإلكتروني",
  "auth.verify.desc": "لقد أرسلنا رابط التحقق إلى بريدك الإلكتروني.",
  "auth.verify.resend": "إعادة الإرسال",
  "auth.verify.logout": "تسجيل الخروج"
}
```

### Travail dans les vues

Pour chaque fichier auth, remplacer le texte hardcodé :

```blade
{{-- login.blade.php --}}
<h2>{{ __('auth.login.title') }}</h2>
<label>{{ __('auth.login.email') }}</label>
<x-input-error :messages="$errors->get('email')" />
```

> ⚠️ **Attention** : Les messages de validation Laravel (ex: "The email field is required") viennent de `lang/en/validation.php`. Il faut créer `lang/ar/validation.php`.

---

## Phase 4 — Espace client (21 vues)

**Statut : ❌ À FAIRE — Priorité MOYENNE**

### Inventaire des vues client

```
resources/views/client/
├── dashboard.blade.php              → Tableau de bord
├── sourcing-requests/
│   ├── index.blade.php             → Liste demandes
│   ├── create.blade.php            → Nouvelle demande
│   ├── show.blade.php              → Détail demande
│   └── edit.blade.php              → Modifier demande
├── sourcing-orders/
│   ├── index.blade.php             → Liste commandes
│   ├── show.blade.php              → Détail commande
│   └── upload-proof.blade.php      → Upload preuve de paiement
├── quotations/
│   └── index.blade.php             → Liste devis
├── tracking/
│   └── index.blade.php             → Suivi colis
├── refund-requests/
│   ├── index.blade.php             → Remboursements
│   └── show.blade.php
└── shipping-fees/
    └── index.blade.php             → Tarifs expédition
```

### Approche recommandée

**Étape 1** — Identifier tous les strings hardcodés dans chaque vue :

```bash
grep -rn '"[A-Z]' resources/views/client/ | grep -v "class=\|id=\|blade\|//\|<!--"
```

**Étape 2** — Créer les clés dans `lang/ar.json` par module :

```json
{
  "client.dashboard.title": "لوحة التحكم",
  "client.dashboard.welcome": "مرحباً، :name",
  "client.dashboard.active_requests": "الطلبات النشطة",
  "client.dashboard.pending_quotations": "العروض قيد الانتظار",
  "client.dashboard.active_orders": "الطلبيات النشطة",

  "client.sourcing_requests.title": "طلبات التوريد",
  "client.sourcing_requests.new": "طلب جديد",
  "client.sourcing_requests.status.pending": "قيد الانتظار",
  "client.sourcing_requests.status.in_progress": "قيد المعالجة",
  "client.sourcing_requests.status.completed": "مكتمل",
  "client.sourcing_requests.status.cancelled": "ملغي",

  "client.orders.title": "طلبياتي",
  "client.orders.track": "تتبع الشحنة",
  "client.orders.upload_proof": "رفع إثبات الدفع",

  "client.quotations.title": "عروض الأسعار",
  "client.quotations.accept": "قبول",
  "client.quotations.reject": "رفض",
  "client.quotations.negotiate": "التفاوض"
}
```

**Étape 3** — Appliquer `__()` dans les vues + tester le rendu RTL.

### Problèmes RTL spécifiques au client

| Composant | Problème RTL | Solution |
|---|---|---|
| Sidebar navigation | Icônes à gauche, texte à droite | Inverser avec `flex-row-reverse` pour RTL |
| Tableaux de données | Alignement colonnes | `text-right` sur cellules en RTL |
| Formulaires de création | Labels + inputs | CSS global `[dir="rtl"] input { text-align: right; }` |
| Badges de statut | Padding asymétrique | Vérifier `pl-` / `pr-` en RTL |
| Breadcrumbs | Chevron gauche→droite | Inverser `>` en `<` ou utiliser `transform: scaleX(-1)` |
| Timeline de commande | Progression gauche→droite | CSS `[dir="rtl"] .timeline { direction: rtl; }` |

---

## Phase 5 — Pages statiques / légales

**Statut : ❌ À FAIRE — Priorité BASSE**

### Fichiers concernés

```
resources/views/pages/
├── privacy.blade.php        → Politique de confidentialité
├── refund-policy.blade.php  → Politique de remboursement
├── shipping-policy.blade.php → Politique d'expédition
├── terms.blade.php          → CGU
└── support.blade.php        → Formulaire de contact
```

### Approche

Ces pages contiennent du **contenu long** (texte juridique). Deux options :

**Option A — Fichiers Markdown par locale** *(recommandée)*

```
lang/ar/pages/privacy.md
lang/fr/pages/privacy.md
lang/en/pages/privacy.md
```

Dans la vue :
```blade
{!! Str::markdown(File::get(lang_path(app()->getLocale() . '/pages/privacy.md'))) !!}
```

**Option B — Clés JSON longues**

```json
{
  "pages.privacy.title": "سياسة الخصوصية",
  "pages.privacy.intro": "نحن في FastSourcingBrothers نأخذ خصوصيتك بجدية تامة..."
}
```

---

## Phase 6 — Emails & notifications

**Statut : ❌ À FAIRE — Priorité MOYENNE**

### Fichiers concernés

```
resources/views/emails/
├── payment-reminder.blade.php
├── proforma-invoice.blade.php
├── refunds/
│   ├── approved.blade.php
│   └── rejected.blade.php
└── suspicious-activity.blade.php
```

### Stratégie d'envoi multilingue

L'email doit être envoyé dans la **langue préférée de l'utilisateur**, pas la locale de la session courante.

**Étape 1** — Ajouter un champ `preferred_locale` sur le modèle `User` :

```php
// Migration
Schema::table('users', function (Blueprint $table) {
    $table->string('preferred_locale', 5)->default('en')->after('email');
});
```

**Étape 2** — Sauvegarder la locale quand l'utilisateur change de langue :

```php
// Dans SetLocale middleware ou LanguageController
if (auth()->check()) {
    auth()->user()->update(['preferred_locale' => $locale]);
}
```

**Étape 3** — Dans les Mailable, forcer la locale :

```php
class PaymentReminderMail extends Mailable
{
    public function build()
    {
        return $this
            ->locale($this->user->preferred_locale ?? 'en')
            ->view('emails.payment-reminder');
    }
}
```

**Étape 4** — Traduire les templates email :

```blade
{{-- emails/payment-reminder.blade.php --}}
<p>{{ __('email.payment_reminder.greeting', ['name' => $user->name]) }}</p>
<p>{{ __('email.payment_reminder.body') }}</p>
```

Clés `ar.json` à ajouter :

```json
{
  "email.payment_reminder.greeting": "عزيزي :name،",
  "email.payment_reminder.body": "نود تذكيركم بأن دفعتكم معلقة...",
  "email.payment_reminder.cta": "الدفع الآن",

  "email.proforma.title": "فاتورة أولية",
  "email.proforma.greeting": "مرحباً :name،",

  "email.refund.approved.title": "تمت الموافقة على طلب الاسترداد",
  "email.refund.approved.body": "يسعدنا إخبارك بأنه تمت الموافقة على طلب استرداد أموالك.",
  "email.refund.rejected.title": "تم رفض طلب الاسترداد",
  "email.refund.rejected.body": "نأسف لإخبارك بأنه تم رفض طلب استرداد أموالك."
}
```

> ⚠️ **RTL dans les emails :** Ajouter `dir="rtl"` sur `<body>` ou le conteneur principal dans les templates email pour l'arabe.

---

## Phase 7 — PDFs & exports

**Statut : ❌ À FAIRE — Priorité HAUTE (complexité élevée)**

### Fichiers concernés

```
resources/views/pdf/
└── proforma-invoice.blade.php

resources/views/admin/sourcing-orders/
├── pdf.blade.php
└── shipping-label.blade.php
```

### Problème majeur : DomPDF et RTL

DomPDF (utilisé par Laravel pour les PDFs) a un **support RTL limité** et ne gère pas nativement les polices arabes Unicode.

### Solution recommandée — mPDF

```bash
composer require mpdf/mpdf
```

**Configuration dans le contrôleur :**

```php
use Mpdf\Mpdf;

public function exportPdf($order)
{
    $locale = $order->user->preferred_locale ?? 'en';
    $isRtl  = $locale === 'ar';

    $mpdf = new Mpdf([
        'mode'        => $isRtl ? 'utf-8' : 'c',
        'direction'   => $isRtl ? 'rtl' : 'ltr',
        'fontdata'    => $isRtl ? [
            'amiri' => [
                'R' => 'Amiri-Regular.ttf',
                'B' => 'Amiri-Bold.ttf',
            ]
        ] : [],
        'default_font' => $isRtl ? 'amiri' : 'dejavusans',
    ]);

    App::setLocale($locale);
    $html = view('pdf.proforma-invoice', compact('order'))->render();
    $mpdf->WriteHTML($html);

    return response($mpdf->Output('', 'S'), 200, [
        'Content-Type'        => 'application/pdf',
        'Content-Disposition' => 'inline; filename="invoice.pdf"',
    ]);
}
```

**Template PDF RTL :**

```blade
{{-- pdf/proforma-invoice.blade.php --}}
@php $isRtl = app()->getLocale() === 'ar'; @endphp
<html dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
<body style="font-family: {{ $isRtl ? 'amiri' : 'dejavusans' }}; direction: {{ $isRtl ? 'rtl' : 'ltr' }};">
  <h1>{{ __('pdf.invoice.title') }}</h1>
  ...
</body>
</html>
```

**Police à télécharger :**

- [Amiri Font](https://www.amirifont.org/) — référence pour les PDFs arabes
- Placer dans `storage/fonts/`

---

## Phase 8 — Panel Admin (scope limité)

**Statut : 🟡 OPTIONNEL**

Le panel admin est utilisé uniquement par l'équipe interne (FR/EN). L'arabe n'est **pas prioritaire** pour l'admin.

### Ce qui peut être utile

1. **Afficher les données client en arabe** : Les noms, adresses, notes en arabe saisis par les clients arabophones doivent s'afficher correctement dans l'admin.

```css
/* Dans admin layout */
.arabic-text { font-family: 'Cairo', sans-serif; direction: rtl; text-align: right; }
```

2. **Détecter automatiquement les champs arabes :**

```blade
@php $isArabic = preg_match('/\p{Arabic}/u', $text); @endphp
<span class="{{ $isArabic ? 'arabic-text' : '' }}">{{ $text }}</span>
```

---

## 9. Référence technique

### Fichiers clés — résumé

```
app/
├── Http/
│   ├── Middleware/
│   │   └── SetLocale.php              ← Détection + session locale
│   └── Controllers/
│       └── LanguageController.php     ← Switch manuel /language/{locale}
bootstrap/
└── app.php                            ← Enregistrement middleware global
routes/
└── web.php                            ← Routes /en /fr /ar + redirect /
lang/
├── en.json                            ← 1132 clés (référence)
├── fr.json                            ← 1241 clés
├── ar.json                            ← 60 clés (welcome page — à enrichir)
├── ar/                                ← À créer pour validation.php, etc.
│   ├── validation.php
│   ├── auth.php
│   └── pagination.php
resources/views/
├── welcome.blade.php                  ← ✅ 100% traduit + RTL
├── layouts/
│   ├── app.blade.php                  ← ❌ dir manquant
│   └── guest.blade.php                ← ❌ dir manquant
```

### Commandes utiles

```bash
# Vider le cache des traductions
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Lister les routes welcome
php artisan route:list | grep "locale\|GET / "

# Vérifier les clés manquantes (outil custom)
php artisan lang:missing --locale=ar

# Tester un email avec la locale arabe
php artisan tinker
>>> App::setLocale('ar'); echo __('email.payment_reminder.greeting', ['name' => 'Ahmad']);
```

### Variables de session

| Clé | Valeur | Description |
|---|---|---|
| `locale` | `en`, `fr`, `ar` | Langue choisie par l'utilisateur |

### Ordre de priorité de la locale

```
1. URL segment (/ar, /fr, /en)          ← Priorité absolue
2. Session::get('locale')               ← Choix persistant
3. $request->getPreferredLanguage()     ← Navigateur (Accept-Language)
4. config('app.locale')                 ← Fallback (en par défaut)
```

---

## 10. Checklist de déploiement

### Avant de déployer l'arabe en production

- [ ] **Phase 2** — `dir="rtl"` ajouté dans `app.blade.php` et `guest.blade.php`
- [ ] **Phase 2** — Police Cairo ajoutée pour l'arabe dans les layouts
- [ ] **Phase 3** — Pages auth traduites + messages de validation Laravel en arabe (`lang/ar/validation.php`)
- [ ] **Phase 4** — Dashboard client et pages principales traduites
- [ ] **Phase 4** — Tests visuels RTL sur Chrome + Firefox + Safari
- [ ] **Phase 4** — Tests sur mobile (iPhone Safari, Android Chrome) en RTL
- [ ] **Phase 6** — Emails envoyés dans la locale préférée de l'utilisateur
- [ ] **Phase 6** — Template email avec `dir="rtl"` pour l'arabe
- [ ] **Phase 7** — PDFs générés avec mPDF + police Amiri
- [ ] **`lang/ar/validation.php`** créé avec les messages de validation Laravel traduits
- [ ] **`lang/ar/auth.php`** créé (messages d'authentification)
- [ ] **`lang/ar/pagination.php`** créé
- [ ] **SEO** — `<meta property="og:locale" content="{{ app()->getLocale() }}_AR">` dans les layouts
- [ ] **Sitemap** — Ajouter les URLs `/ar/...` dans le sitemap
- [ ] **Google Search Console** — Déclarer les hreflang `ar`, `fr`, `en`

### hreflang SEO à ajouter dans `welcome.blade.php`

```blade
{{-- Dans <head> --}}
<link rel="alternate" hreflang="en" href="{{ url('/en') }}" />
<link rel="alternate" hreflang="fr" href="{{ url('/fr') }}" />
<link rel="alternate" hreflang="ar" href="{{ url('/ar') }}" />
<link rel="alternate" hreflang="x-default" href="{{ url('/en') }}" />
```

---

## Estimation effort restant

| Phase | Priorité | Effort estimé | Complexité |
|---|---|---|---|
| Phase 2 — Layouts RTL | HAUTE | 2h | Faible |
| Phase 3 — Auth pages | HAUTE | 3h | Faible |
| Phase 4 — Client (21 vues) | MOYENNE | 2–3 jours | Moyenne |
| Phase 5 — Pages légales | BASSE | 1 jour | Faible |
| Phase 6 — Emails | MOYENNE | 1 jour | Moyenne |
| Phase 7 — PDFs | HAUTE | 2 jours | Élevée |
| Phase 8 — Admin | OPTIONNEL | 4h | Faible |

---

*Document généré le 2026-04-14 — FastSourcingBrothers / claude/*
