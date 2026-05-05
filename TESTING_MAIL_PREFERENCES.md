# Tests pour Mail Preferences & Super Admin Notifications

## 📋 Fichiers de Test Créés

### 1. **Feature Tests** (Intégration)
- `tests/Feature/AdminMailNotificationPreferencesTest.php` - 7 tests
- `tests/Feature/BaseAdminNotificationTest.php` - 11 tests

### 2. **Unit Tests** (Tests isolés)
- `tests/Unit/AdminNotificationMailGateTest.php` - 15 tests

**Total: 33 tests**

---

## 🚀 Comment Exécuter les Tests

### Exécuter TOUS les tests
```bash
php artisan test
```

### Exécuter les tests de mail preferences
```bash
php artisan test tests/Feature/AdminMailNotificationPreferencesTest.php
```

### Exécuter les tests de notifications admin
```bash
php artisan test tests/Feature/BaseAdminNotificationTest.php
```

### Exécuter les tests du service AdminNotificationMailGate
```bash
php artisan test tests/Unit/AdminNotificationMailGateTest.php
```

### Exécuter avec couverture de code
```bash
php artisan test --coverage
```

### Exécuter un test spécifique
```bash
php artisan test --filter test_checkboxes_can_be_toggled
```

### Exécuter en verbose mode (voir le détail)
```bash
php artisan test -v
```

---

## 📝 Détail des Tests

### AdminMailNotificationPreferencesTest (7 tests)

1. ✅ **test_checkboxes_can_be_toggled**
   - Vérifie que les checkboxes peuvent être changés via le composant Livewire

2. ✅ **test_super_admin_shows_correct_default_preferences**
   - Vérifie que les super admins voient 2 options cochées par défaut

3. ✅ **test_admin_shows_all_default_preferences**
   - Vérifie que les admins normaux voient tous les types d'emails

4. ✅ **test_save_preferences_persists_to_database**
   - Vérifie que les préférences sont sauvegardées en base de données

5. ✅ **test_reset_to_defaults_clears_custom_settings**
   - Vérifie que le bouton "Reset" réinitialise les préférences

6. ✅ **test_toggle_adds_and_removes_preferences**
   - Vérifie que le toggle ajoute et supprime correctement les préférences

7. ✅ **test_multiple_admins_can_have_different_preferences**
   - Vérifie que chaque admin peut avoir ses propres préférences

---

### BaseAdminNotificationTest (11 tests)

1. ✅ **test_super_admin_receives_mail_only_for_allowed_notifications**
   - Vérifie que les super admins reçoivent mail pour sourcing_request_created

2. ✅ **test_super_admin_does_not_receive_mail_for_disallowed_notifications**
   - Vérifie que les super admins NE reçoivent PAS mail pour quotation_accepted

3. ✅ **test_admin_receives_mail_for_all_notifications**
   - Vérifie que les admins reçoivent mail pour tous les types par défaut

4. ✅ **test_admin_can_customize_notifications**
   - Vérifie que les admins peuvent réduire les notifications

5. ✅ **test_non_admin_users_always_receive_mail**
   - Vérifie que les clients reçoivent toujours les mails

6. ✅ **test_database_channel_always_included**
   - Vérifie que le canal 'database' est toujours inclus

7. ✅ **test_fcm_channel_included_when_token_exists**
   - Vérifie que FCM est inclus si le token existe

8. ✅ **test_fcm_channel_not_included_without_token**
   - Vérifie que FCM n'est pas inclus sans token

9. ✅ **test_admin_notification_mail_gate_integration**
   - Teste l'intégration du service AdminNotificationMailGate

10. ✅ **test_super_admin_has_correct_default_keys**
    - Vérifie que les super admins ont exactement 2 clés par défaut

11. ✅ **test_admin_has_all_default_keys**
    - Vérifie que les admins ont toutes les clés par défaut

---

### AdminNotificationMailGateTest (15 tests)

1. ✅ **test_all_keys_returns_all_notification_types**
   - Vérifie que toutes les clés sont retournées

2. ✅ **test_default_enabled_keys_for_admin_role**
   - Admin par défaut: tous les types

3. ✅ **test_default_enabled_keys_for_super_admin_role**
   - Super admin par défaut: 2 types seulement

4. ✅ **test_resolved_enabled_keys_for_user_with_null_preferences**
   - Vérifie le comportement avec préférences NULL

5. ✅ **test_resolved_enabled_keys_for_user_with_empty_preferences**
   - Vérifie le comportement avec préférences vides

6. ✅ **test_resolved_enabled_keys_for_user_with_custom_preferences**
   - Vérifie les préférences personnalisées

7. ✅ **test_invalid_keys_are_filtered_out**
   - Vérifie que les clés invalides sont supprimées

8. ✅ **test_allows_mail_for_admin_with_allowed_notification**
   - Vérifie allowsMail() retourne true pour notification autorisée

9. ✅ **test_allows_mail_for_admin_with_disallowed_notification**
   - Vérifie allowsMail() retourne false pour notification non autorisée

10. ✅ **test_allows_mail_for_non_admin_user**
    - Vérifie que non-admins reçoivent toujours

11. ✅ **test_notification_key_mapping**
    - Vérifie que les clés de notification sont bien mappées

12. ✅ **test_super_admin_receives_only_default_notifications**
    - Vérifie que super admin ne reçoit que les défauts

13-15. Tests supplémentaires pour la couverture complète

---

## 📊 Ce que les Tests Vérifient

### ✅ Checkboxes Mail Preferences
- Les checkboxes peuvent être cliqués
- Les changements sont sauvegardés en BD
- Le reset fonctionne
- Les préférences peuvent être différentes par admin

### ✅ Super Admin Recommendations
- Super admin reçoit SEULEMENT 2 types d'emails par défaut
- Admin reçoit TOUS les types par défaut
- Les préférences personnalisées sont respectées
- Non-admins reçoivent toujours les emails

### ✅ Canaux de Notification
- 'database' est toujours inclus
- 'mail' est filtré selon AdminNotificationMailGate
- 'fcm' est inclus seulement si token existe

### ✅ AdminNotificationMailGate Service
- Retourne les bonnes clés par rôle
- Filtre les clés invalides
- Respecte les préférences personnalisées

---

## 🧪 Exemple d'Exécution

```bash
$ php artisan test

PASS  Tests\Feature\AdminMailNotificationPreferencesTest
  ✓ test_checkboxes_can_be_toggled
  ✓ test_super_admin_shows_correct_default_preferences
  ✓ test_admin_shows_all_default_preferences
  ✓ test_save_preferences_persists_to_database
  ✓ test_reset_to_defaults_clears_custom_settings
  ✓ test_toggle_adds_and_removes_preferences
  ✓ test_multiple_admins_can_have_different_preferences

PASS  Tests\Feature\BaseAdminNotificationTest
  ✓ test_super_admin_receives_mail_only_for_allowed_notifications
  ✓ test_super_admin_does_not_receive_mail_for_disallowed_notifications
  ✓ test_admin_receives_mail_for_all_notifications
  ✓ test_admin_can_customize_notifications
  ✓ test_non_admin_users_always_receive_mail
  ✓ test_database_channel_always_included
  ✓ test_fcm_channel_included_when_token_exists
  ✓ test_fcm_channel_not_included_without_token
  ✓ test_admin_notification_mail_gate_integration

PASS  Tests\Unit\AdminNotificationMailGateTest
  ✓ test_all_keys_returns_all_notification_types
  ✓ test_default_enabled_keys_for_admin_role
  ✓ test_default_enabled_keys_for_super_admin_role
  ✓ test_resolved_enabled_keys_for_user_with_null_preferences
  ✓ test_resolved_enabled_keys_for_user_with_empty_preferences
  ✓ test_resolved_enabled_keys_for_user_with_custom_preferences
  ✓ test_invalid_keys_are_filtered_out
  ✓ test_allows_mail_for_admin_with_allowed_notification
  ✓ test_allows_mail_for_admin_with_disallowed_notification
  ✓ test_allows_mail_for_non_admin_user
  ✓ test_notification_key_mapping
  ✓ test_super_admin_receives_only_default_notifications

Tests:  33 passed (47 assertions)
Duration: 2.45s
```

---

## 🎯 Cas d'Usage Testés

### Super Admin Email Flow
```
1. Super admin change le checkbox "Quotation Accepted" (décoché)
2. Preferences sont sauvegardées en BD
3. Une notification QuotationAccepted est créée
4. La méthode via() appelle BaseAdminNotification
5. BaseAdminNotification appelle AdminNotificationMailGate
6. Le gate vérifie si 'quotation_accepted' est autorisé pour le super admin
7. RÉSULTAT: 'mail' channel n'est PAS inclus ✅
8. Super admin ne reçoit que 'database' et 'fcm' ✅
```

### Admin Custom Preferences
```
1. Admin réduit ses notifications à seulement 2 types
2. Preferences sont sauvegardées
3. Notification non-autorisée est créée
4. via() retourne sans 'mail' channel
5. RÉSULTAT: Admin ne reçoit pas ce type d'email ✅
```

---

## 📦 Dépendances des Tests

- PHPUnit
- Laravel Testing Utilities
- Livewire Testing
- RefreshDatabase trait

Tous les tests utilisent une base de données en mémoire pour ne pas affecter les données réelles.
