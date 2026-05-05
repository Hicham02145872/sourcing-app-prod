# Guide de Déploiement pour Sourcing-App

Ce document décrit les étapes pour déployer l'application Sourcing-App sur un serveur de production (VPS, type Hostinger). Deux approches sont présentées :

1.  **Déploiement avec Docker (Recommandé)** : Plus simple, plus fiable et isole l'environnement de l'application.
2.  **Déploiement Traditionnel ("Bare Metal")** : Installation manuelle de chaque service sur le serveur.

---

## Prérequis

Avant de commencer, assurez-vous d'avoir :

*   Un accès SSH à votre serveur VPS avec les droits `sudo`.
*   Un nom de domaine pointant vers l'adresse IP de votre serveur.
*   Votre code source hébergé sur un repository Git (GitHub, GitLab, etc.).
*   Les identifiants pour les services externes (Firebase, service de mail).

---

## Approche 1 : Déploiement avec Docker (Recommandé)

Cette méthode utilise les fichiers `Dockerfile` et `docker-compose.yml` déjà présents dans votre projet.

### Étape 1 : Configuration du Serveur

1.  **Connectez-vous en SSH à votre VPS.**

2.  **Installez Docker et Docker Compose.**
    ```bash
    # Mettre à jour les paquets
    sudo apt update && sudo apt upgrade -y

    # Installer Docker
    sudo apt install docker.io -y

    # Installer Docker Compose
    sudo apt install docker-compose -y

    # Ajouter votre utilisateur au groupe Docker pour éviter d'utiliser sudo
    sudo usermod -aG docker ${USER}
    ```
    > **Important** : Déconnectez-vous et reconnectez-vous pour que les changements de groupe prennent effet.

3.  **Installez Git.**
    ```bash
    sudo apt install git -y
    ```

### Étape 2 : Déploiement de l'Application

1.  **Clonez votre projet.**
    ```bash
    git clone [URL_de_votre_repository] sourcing-app
    cd sourcing-app
    ```

2.  **Configuration de l'environnement.**
    *   **Sécurité des fichiers `.env`** : Votre projet contient un fichier `.env.production`. **NE JAMAIS** commettre ce fichier sur Git. Il doit être créé sur le serveur.
    *   Créez le fichier `.env` qui sera utilisé par Docker Compose :
        ```bash
        cp .env.example .env
        ```
    *   **Modifiez le fichier `.env`** avec vos configurations de production :
        *   `APP_NAME`, `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://votredomaine.com`
        *   `DB_HOST=db` (le nom du service dans `docker-compose.yml`)
        *   `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` (secrets)
        *   `MAIL_...` pour l'envoi d'emails.
        *   `FILESYSTEM_DISK=public`
        *   `QUEUE_CONNECTION=redis` (Recommandé, car Redis est plus performant que la base de données pour les files d'attente).
        *   `REDIS_HOST=redis` (le nom du service redis)

3.  **Optimisation du Build Docker (Action Requise).**
    Votre `Dockerfile` exécute `php artisan config:cache` et `php artisan route:cache` pendant la construction de l'image. Cela "fige" la configuration de l'environnement au moment du build. Il est préférable de le faire au démarrage du conteneur.

    *   **Modifiez votre `Dockerfile`** : Supprimez ou commentez ces lignes.
    *   **Créez un script d'entrée** (ex: `docker/entrypoint.sh`) qui exécutera ces commandes au démarrage.

4.  **Lancez les conteneurs.**
    ```bash
    docker-compose up -d --build
    ```

5.  **Exécutez les commandes post-déploiement.**
    ```bash
    # Générer la clé d'application (si non définie dans .env)
    docker-compose exec app php artisan key:generate

    # Lancer les migrations et les seeders
    docker-compose exec app php artisan migrate --seed --force

    # Lier le stockage
    docker-compose exec app php artisan storage:link

    # Mettre en cache la configuration (si vous avez suivi l'optimisation)
    docker-compose exec app php artisan config:cache
    docker-compose exec app php artisan route:cache
    docker-compose exec app php artisan view:cache
    ```

### Étape 3 : Configuration du Reverse Proxy (Nginx)

Pour que votre domaine pointe vers l'application Docker, vous pouvez installer Nginx sur le VPS et le configurer comme un reverse proxy.

1.  **Installez Nginx.**
    ```bash
    sudo apt install nginx -y
    ```

2.  **Créez un fichier de configuration pour votre site.**
    ```bash
    sudo nano /etc/nginx/sites-available/sourcing-app
    ```

3.  **Collez cette configuration** (en remplaçant `votredomaine.com`):
    ```nginx
    server {
        listen 80;
        server_name votredomaine.com www.votredomaine.com;

        location / {
            proxy_pass http://127.0.0.1:8000; # Le port exposé par votre docker-compose
            proxy_set_header Host $host;
            proxy_set_header X-Real-IP $remote_addr;
            proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto $scheme;
        }
    }
    ```
    > **Note** : Assurez-vous que le port `8000` correspond à celui que vous exposez dans `docker-compose.yml` (ex: `ports: - "8000:80"`).

4.  **Activez le site et redémarrez Nginx.**
    ```bash
    sudo ln -s /etc/nginx/sites-available/sourcing-app /etc/nginx/sites-enabled/
    sudo nginx -t # Tester la configuration
    sudo systemctl restart nginx
    ```

### Étape 4 : Sécurisation avec SSL (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d votredomaine.com -d www.votredomaine.com
```

---

## Approche 2 : Déploiement Traditionnel ("Bare Metal")

### Étape 1 : Installation des Dépendances sur le VPS

```bash
sudo apt update && sudo apt upgrade -y

# Installer Nginx, Git, Unzip
sudo apt install nginx git unzip -y

# Installer PHP 8.2 et les extensions requises par Laravel et votre projet
sudo apt install php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd php8.2-gmp php8.2-redis -y

# Installer Composer (gestionnaire de paquets PHP)
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Installer Node.js et npm (pour compiler les assets frontend)
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs

# Installer MySQL Server et Redis
sudo apt install mysql-server redis-server -y
```

### Étape 2 : Configuration de la Base de Données

1.  **Sécurisez MySQL.**
    ```bash
    sudo mysql_secure_installation
    ```

2.  **Créez une base de données et un utilisateur.**
    ```sql
    sudo mysql -u root -p
    CREATE DATABASE sourcing_app;
    CREATE USER 'sourcing_user'@'localhost' IDENTIFIED BY 'un_mot_de_passe_solide';
    GRANT ALL PRIVILEGES ON sourcing_app.* TO 'sourcing_user'@'localhost';
    FLUSH PRIVILEGES;
    EXIT;
    ```

### Étape 3 : Déploiement du Code

1.  **Clonez le projet.**
    ```bash
    sudo git clone [URL_de_votre_repository] /var/www/sourcing-app
    ```

2.  **Configurez l'environnement.**
    ```bash
    cd /var/www/sourcing-app
    sudo cp .env.example .env
    sudo nano .env
    ```
    Remplissez le fichier `.env` avec les informations de votre base de données, de mail, etc. `DB_HOST` sera `127.0.0.1`.

3.  **Installez les dépendances et compilez les assets.**
    ```bash
    # Définir les permissions pour le cache et les logs
    sudo chown -R www-data:www-data /var/www/sourcing-app
    sudo chmod -R 775 /var/www/sourcing-app/storage /varwww/sourcing-app/bootstrap/cache

    # Installer les dépendances PHP et JS
    sudo -u www-data composer install --no-dev --optimize-autoloader
    npm install
    npm run build
    ```

4.  **Finalisez l'installation Laravel.**
    ```bash
    sudo -u www-data php artisan key:generate
    sudo -u www-data php artisan storage:link
    sudo -u www-data php artisan migrate --seed --force
    sudo -u www-data php artisan config:cache
    sudo -u www-data php artisan route:cache
    sudo -u www-data php artisan view:cache
    ```

### Étape 4 : Configuration de Nginx et Supervisor

1.  **Créez un fichier de configuration Nginx** (`sudo nano /etc/nginx/sites-available/sourcing-app`) avec le contenu suivant :
    ```nginx
    server {
        listen 80;
        server_name votredomaine.com www.votredomaine.com;
        root /var/www/sourcing-app/public;

        add_header X-Frame-Options "SAMEORIGIN";
        add_header X-Content-Type-Options "nosniff";

        index index.php;

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        error_page 404 /index.php;

        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
            include fastcgi_params;
        }

        location ~ /\.(?!well-known).* {
            deny all;
        }
    }
    ```
    Activez le site comme dans l'approche Docker.

2.  **Configurez Supervisor pour les workers.**
    ```bash
    sudo apt install supervisor -y
    sudo nano /etc/supervisor/conf.d/sourcing-worker.conf
    ```
    Ajoutez la configuration pour le worker :
    ```ini
    [program:sourcing-worker]
    process_name=%(program_name)s_%(process_num)02d
    command=php /var/www/sourcing-app/artisan queue:work redis --sleep=3 --tries=3
    autostart=true
    autorestart=true
    user=www-data
    numprocs=2
    redirect_stderr=true
    stdout_logfile=/var/www/sourcing-app/storage/logs/worker.log
    ```
    Activez la configuration :
    ```bash
    sudo supervisorctl reread
    sudo supervisorctl update
    sudo supervisorctl start sourcing-worker:*
    ```

3.  **Configurez le Cron Job pour les tâches planifiées.**
    ```bash
    sudo crontab -e -u www-data
    ```
    Ajoutez cette ligne :
    ```
    * * * * * cd /var/www/sourcing-app && php artisan schedule:run >> /dev/null 2>&1
    ```

### Étape 5 : SSL
L'installation de Certbot est identique à celle de l'approche Docker.

---

## Prochaines Étapes

*   **Choisissez une approche.** Je vous conseille fortement de privilégier **Docker**.
*   **Sécurisez vos secrets.** Ne stockez jamais de mots de passe ou de clés API dans votre code Git. Utilisez le fichier `.env` sur le serveur.
*   **Automatisation.** Pour des déploiements futurs, vous pourriez automatiser ces étapes avec des outils comme GitHub Actions ou Deployer.php.
