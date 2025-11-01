# Guide de Déploiement avec Dokploy sur Hostinger

Ce guide vous explique comment déployer cette application Laravel sur un VPS Hostinger (KVM) en utilisant Dokploy et sa fonctionnalité de Buildpacks (Nixpacks), qui automatise une grande partie du processus.

---

## Prérequis

1.  **VPS Hostinger** : Un serveur KVM 2 ou supérieur est recommandé.
2.  **Nom de domaine** : Un nom de domaine qui pointe vers l'adresse IP de votre VPS (via un enregistrement de type A).
3.  **Dépôt Git** : Votre projet doit être sur un dépôt Git (GitHub, GitLab, etc.).

---

## Étape 1: Installation de Dokploy sur votre VPS

1.  Connectez-vous à votre VPS en SSH :
    ```bash
    ssh root@VOTRE_IP_VPS
    ```

2.  Lancez le script d'installation de Dokploy :
    ```bash
    curl -sSL https://dokploy.com/install.sh | sh
    ```

3.  Une fois l'installation terminée, accédez à l'interface de Dokploy dans votre navigateur : `http://VOTRE_IP_VPS:3000`. Créez votre compte administrateur.

---

## Étape 2: Création de la Base de Données

Dans l'interface Dokploy :

1.  Allez dans le menu **Databases**.
2.  Cliquez sur **Create**.
3.  Choisissez le type de base de données (par exemple, **PostgreSQL** ou **MySQL**).
4.  Donnez-lui un nom (ex: `sourcing-db`) et cliquez sur **Create**. Dokploy va démarrer un conteneur sécurisé pour votre base de données.

---

## Étape 3: Déploiement de l'Application Laravel

1.  Allez dans le menu **Projects** et cliquez sur **Create**.
2.  Donnez un nom à votre projet (ex: `sourcing-app`).
3.  Dans l'écran de configuration du projet, allez dans l'onglet **Git** et connectez votre compte GitHub ou autre fournisseur Git.
4.  Sélectionnez le dépôt de votre application.
5.  Dans l'onglet **Build**, sélectionnez **Nixpacks** comme méthode de build. C'est la méthode la plus simple qui détectera automatiquement votre projet Laravel.
6.  Dans l'onglet **General**, section **Linked Database**, sélectionnez la base de données que vous avez créée à l'étape 2. Cela injectera automatiquement les variables de connexion dans votre application.

---

## Étape 4: Configuration des Variables d'Environnement

C'est l'étape la plus importante. Dans l'onglet **Environment** de votre projet, ajoutez les variables suivantes. **NE COCHEZ PAS** la case "Secret" sauf si spécifié.

| Variable          | Valeur                                                              | Secret |
| ----------------- | ------------------------------------------------------------------- | ------ |
| `APP_NAME`        | "Sourcing App"                                                      | Non    |
| `APP_ENV`         | `production`                                                        | Non    |
| `APP_DEBUG`       | `false`                                                             | Non    |
| `APP_URL`         | `https://votredomaine.com` ( **Changez ceci !** )                    | Non    |
| `APP_KEY`         | `base64:0TR3F2sBaa0D0vATPS3TFmujJBxYW2ydnRc1vE2JWvc=`                | Oui    |
| `LOG_CHANNEL`     | `stderr`                                                            | Non    |
| `DB_CONNECTION`   | `mysql` ou `pgsql` (selon votre choix à l'étape 2)                 | Non    |

**Note sur la base de données** : Comme vous avez lié la base de données à l'étape 3, Dokploy fournit automatiquement les variables `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, et `DB_PASSWORD`. Vous n'avez **pas** besoin de les ajouter manuellement.

---

## Étape 5: Lancement du Déploiement

1.  Vérifiez les autres onglets :
    *   **Build**: Laissez "Build Command" vide. Nixpacks s'en chargera.
    *   **General**: Laissez "Start Command" vide. Nixpacks s'en chargera également.
    *   **Networking**: Assurez-vous que le port exposé est `8000` (valeur par défaut pour les projets Laravel avec Nixpacks).

2.  Cliquez sur le bouton **Deploy**. Le premier déploiement peut prendre plusieurs minutes car Dokploy doit construire l'image de votre application. Vous pouvez suivre les logs en direct.

---

## Étape 6: Configuration du Domaine et SSL

1.  Une fois le déploiement réussi (vous verrez le statut "Running"), allez dans l'onglet **Domains**.
2.  Entrez votre nom de domaine (ex: `app.votredomaine.com`).
3.  Cliquez sur **Create**.

Dokploy détectera automatiquement le domaine, le liera à votre application et générera un certificat SSL/TLS avec Let's Encrypt.

---

## Bravo !

Votre application est maintenant déployée, sécurisée avec SSL et prête à être utilisée.
