# Plan d'Intégration Google Sheets pour les Commandes Payées

**Objectif :** Lorsqu'une commande (`SourcingOrder`) passe au statut `paid`, envoyer automatiquement les informations clés de cette commande vers une nouvelle ligne dans une feuille de calcul Google Sheets.

---

### Étape 1 : Point de Déclenchement (Le "Quand ?")

L'action doit se déclencher précisément lorsque le statut d'une `SourcingOrder` change et devient `paid`.

- **Approche Recommandée :** Créer un nouveau **Listener** qui écoutera l'événement `SourcingOrderStatusChanged` qui existe déjà.
- **Nom du Listener :** `SyncOrderToGoogleSheet`
- **Logique :** À l'intérieur du listener, nous vérifierons si le nouveau statut de la commande est bien `paid`.
- **Avantage :** Cette approche est propre, découplée et respecte l'architecture existante de l'application. Elle n'encombre pas le `SourcingOrderController`.

---

### Étape 2 : Configuration de l'API Google Sheets (Le "Comment ?")

1.  **Installer la librairie client de Google pour PHP :**
    ```bash
    composer require google/apiclient:^2.0
    ```
2.  **Configurer un Projet sur Google Cloud Platform (GCP) :**
    *   Créer un nouveau projet sur la [console GCP](https://console.cloud.google.com/).
    *   Activer l'API "Google Sheets".
    *   Créer un **Compte de Service** (`Service Account`).
    *   Générer une clé JSON pour ce compte de service et la télécharger.
3.  **Sécuriser la Clé :**
    *   Placer le fichier `credentials.json` dans le dossier `storage/app/secure/`.
    *   **Crucial :** Ajouter ce chemin au fichier `.gitignore` pour ne jamais exposer les identifiants dans le dépôt Git.
4.  **Partager la Feuille de Calcul :**
    *   Créer une nouvelle feuille Google Sheets.
    *   Partager cette feuille avec l'adresse e-mail du compte de service (qui ressemble à `...gserviceaccount.com`). Lui donner les permissions "Éditeur".
5.  **Variables d'Environnement :**
    *   Ajouter l'ID de la feuille de calcul et le chemin vers les identifiants dans le fichier `.env` :
        ```
        GOOGLE_SHEET_ID=...
        GOOGLE_APPLICATION_CREDENTIALS_PATH=secure/credentials.json
        ```

---

### Étape 3 : Implémentation du Code

1.  **Créer le Listener :**
    *   Générer le fichier de la classe : `php artisan make:listener SyncOrderToGoogleSheet --event=SourcingOrderStatusChanged`
2.  **Enregistrer le Listener :**
    *   Dans `app/Providers/EventServiceProvider.php`, ajouter le nouveau listener au tableau `$listen` pour l'événement `SourcingOrderStatusChanged`.
3.  **Développer la Logique du Listener (`SyncOrderToGoogleSheet.php`) :**
    *   Dans la méthode `handle()`, implémenter le flux suivant :
        ```php
        // 1. Vérifier si le statut est 'paid'
        if ($event->sourcingOrder->status !== 'paid') {
            return; // Ne rien faire si ce n'est pas le bon statut
        }

        // 2. Préparer les données à envoyer
        $order = $event->sourcingOrder;
        $order->load('user', 'quotation.sourcingRequest'); // S'assurer que les relations sont chargées

        $data = [
            $order->id,
            $order->quotation->sourcingRequest->product_name,
            $order->total_amount,
            $order->user->name,
            $order->user->email,
            now()->toDateTimeString(),
        ];

        // 3. Initialiser le client Google Sheets
        // ... Logique d'authentification avec le fichier JSON ...

        // 4. Envoyer les données à la feuille de calcul
        // ... Logique pour ajouter une nouvelle ligne (append) ...

        // 5. Gérer les erreurs avec un bloc try/catch
        // Logger toute erreur potentielle sans bloquer le reste de l'application.
        ```
4.  **Créer une Classe Service (Optionnel mais recommandé) :**
    *   Pour garder le Listener propre, la logique de communication avec Google Sheets peut être encapsulée dans une classe dédiée, par exemple `App\Services\GoogleSheetService`. Le Listener n'aurait plus qu'à appeler un simple méthode comme `$googleSheetService->appendRow($data)`.

---

### Résumé des Tâches :
- [ ] Installer `google/apiclient`.
- [ ] Configurer le projet GCP, le compte de service et la feuille de calcul.
- [ ] Stocker les identifiants de manière sécurisée.
- [ ] Ajouter les variables d'environnement (`.env`).
- [ ] Créer le listener `SyncOrderToGoogleSheet`.
- [ ] Enregistrer le listener dans `EventServiceProvider`.
- [ ] Implémenter la logique de récupération des données et d'envoi à Google Sheets.
- [ ] Ajouter une gestion robuste des erreurs.
- [ ] Tester en passant une commande au statut `paid`.
