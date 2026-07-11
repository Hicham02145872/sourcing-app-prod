## 1. Database & Config

- [x] 1.1 Créer la migration pour la table `settings` (key, value nullable, timestamps)
- [x] 1.2 Créer le model `Setting` avec `$fillable`, methodes `get()`/`set()` static
- [x] 1.3 Exécuter la migration

## 2. Dev Login - Controller & Routes

- [x] 2.1 Créer `DevLoginController` dans `app/Http/Controllers/Auth/` avec `showLoginForm()` et `login()`
- [x] 2.2 Ajouter les routes `/{locale}/dev/login` (GET + POST) dans `routes/web.php`
- [x] 2.3 Ajouter le rate limiting (5 attempts/1 min) sur la route POST

## 3. Dev Login - Vue

- [x] 3.1 Créer `resources/views/auth/dev-login.blade.php` avec design dark theme, badge DEV, formul email+password
- [x] 3.2 Ajouter les traductions EN/FR/AR pour le formulaire dev-login

## 4. Maintenance Mode - Middleware

- [x] 4.1 Créer le middleware `CheckMaintenanceMode` dans `app/Http/Middleware/`
- [x] 4.2 Enregistrer le middleware dans `bootstrap/app.php` comme web global
- [x] 4.3 Le middleware vérifie le cache/table `settings` et rend la vue maintenance si actif (sauf developer/admin/super_admin)

## 5. Maintenance Mode - Vue (Design WOW)

- [x] 5.1 Créer `resources/views/maintenance.blade.php` avec :
  - Background gradient animé dark (indigo → violet → noir)
  - Particules flottantes CSS (bokeh effect)
  - Carte glassmorphism centrée (backdrop-blur, bordures translucides)
  - Illustration SVG inline animée (engrenages + fusée qui décolle)
  - Logo SmartSourcing avec effet glow/shimmer
  - Message personnalisable en gradient text
  - Animations fade-in + pulsation + rotation
  - Full responsive mobile/tablet/desktop
- [x] 5.2 Ajouter les traductions EN/FR/AR pour l'écran de maintenance

## 6. Dev Dashboard - Toggle Maintenance

- [x] 6.1 Ajouter la section "Maintenance Mode" dans `DevDashboard.php` (propriétés + methodes load/toggle/updateMessage)
- [x] 6.2 Ajouter le toggle switch + champ message dans `dev-dashboard.blade.php`
- [x] 6.3 Synchroniser le cache avec la table `settings` lors du toggle
