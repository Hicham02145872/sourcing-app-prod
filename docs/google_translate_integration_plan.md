# Plan d'Intégration Google Translate (Client-Side)

Ce document détaille les étapes pour intégrer le widget Google Translate "style WordPress/GTranslate" dans votre application Laravel via une solution purement client-side (JavaScript).

## Prérequis
- Accès aux fichiers de vue (`resources/views`)
- Accès aux fichiers CSS (`resources/css`)

---

## Étape 1 : Nettoyage de l'Interface (CSS)

Pour éviter la barre supérieure Google disgracieuse et styliser le menu déroulant, nous allons ajouter du CSS personnalisé.

**Fichier cible** : `resources/css/app.css`

**Code à ajouter** :
```css
/* Google Translate Widget Customization */

/* Hide the Google Translate Top Bar */
.goog-te-banner-frame.skiptranslate {
    display: none !important;
}

/* Hide the "Google Translate" logo in the dropdown */
.goog-te-gadget-icon {
    display: none !important;
}

/* Fix the body shift caused by the hidden top bar */
body {
    top: 0px !important;
}

/* Hide the "Powered by Google" branding if possible/allowed */
.goog-te-gadget-simple {
    background-color: transparent !important;
    border: none !important;
    padding: 0 !important;
    font-size: 14px !important;
    display: flex !important;
    align-items: center !important;
}

.goog-te-gadget-simple .goog-te-menu-value {
    color: #4b5563 !important; /* text-gray-600 */
    margin: 0 !important;
}

.goog-te-gadget-simple .goog-te-menu-value span {
    border-left: none !important;
}
```

---

## Étape 2 : Ajout du Widget (Blade Layout)

Nous allons placer le sélecteur de langue dans la barre de navigation, juste avant le menu utilisateur.

**Fichier cible** : `resources/views/layouts/navigation.blade.php`

**Emplacement** : Juste avant la div `Settings Dropdown` (ligne ~21).

**Code HTML/JS à insérer** :
```blade
            <!-- Google Translate Widget -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div id="google_translate_element"></div>
                
                <script type="text/javascript">
                    function googleTranslateElementInit() {
                        new google.translate.TranslateElement({
                            pageLanguage: 'en', // Langue par défaut du site
                            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                            autoDisplay: false,
                        }, 'google_translate_element');
                    }
                </script>
                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            </div>
```

**Exemple de contexte** :
```blade
            <!-- Google Translate Widget -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div id="google_translate_element"></div>
                
                <script type="text/javascript">
                    // ... (script content)
                </script>
                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <!-- ... -->
```

---

## Étape 3 : Vérification

1.  Recompilez les assets si nécessaire (bien que le CSS soit souvent hot-reloaded avec Vite) :
    ```bash
    npm run dev
    ```
2.  Ouvrez une page de l'application (ex: Dashboard).
3.  Vérifiez la présence du sélecteur de langue dans la navbar.
4.  Changez de langue et vérifiez que :
    - La page se traduit.
    - La barre supérieure Google "Original text" n'apparaît pas (ou est masquée).
    - Le layout ne "saute" pas (pas de décalage vers le bas).

---

## Notes Techniques

- **Performance** : Le script est chargé depuis les serveurs Google. Il est asynchrone (`cb=googleTranslateElementInit`), donc il ne devrait pas bloquer le chargement initial de la page.
- **Limitations** :
    - La traduction est automatique (IA Google), donc pas toujours parfaite contextuellement.
    - Le design de la popup (au clic) est géré par Google et difficilement styleable (iframe).
