# Stratégie de Migration vers une Architecture Microservices

Ce document propose une stratégie pour faire évoluer l'application monolithique Laravel vers une architecture basée sur les microservices. L'approche s'appuie sur les concepts du Domain-Driven Design (DDD) déjà identifiés.

## 1. Principe Fondamental : Des Contextes Bornés aux Microservices

La transition la plus naturelle vers les microservices consiste à transformer chaque Contexte Borné (Bounded Context) en un service autonome. Chaque service aura son propre code, sa propre base de données et sera déployable indépendamment.

Nos microservices candidats sont :

1.  **Service `Sourcing`**
    -   **Responsabilités :** Gérer le cycle de vie des `SourcingRequests` et des `Quotations`.
    -   **Données possédées :** `sourcing_requests`, `quotations`, `categories`.
    -   **API exposée :** Opérations CRUD pour les demandes de sourcing et les devis.

2.  **Service `Ordering`**
    -   **Responsabilités :** Gérer le cycle de vie des `SourcingOrders` après l'acceptation d'un devis, y compris le paiement et le suivi.
    -   **Données possédées :** `sourcing_orders`, `payments`, `shipment_tracking_events`.
    -   **API exposée :** Opérations pour les commandes, téléversement de preuves de paiement, mise à jour des statuts de livraison.

3.  **Service `Identity`**
    -   **Responsabilités :** Gérer les utilisateurs, les rôles (`client`, `admin`), l'authentification (création de tokens JWT) et l'autorisation.
    -   **Données possédées :** `users`, `roles`, `permissions`.
    -   **API exposée :** Inscription, connexion, récupération des profils utilisateurs, validation des tokens.

4.  **Service `Notification`**
    -   **Responsabilités :** Centraliser l'envoi de toutes les communications sortantes.
    -   **API exposée :** Un point de terminaison pour l'envoi d'emails et un autre pour les notifications push (FCM).

5.  **Service `Administration`**
    -   **Responsabilités :** Gérer les données de configuration et de support.
    -   **Données possédées :** `countries`, `services`, `payment_methods`, `welcome_page_content`.
    -   **API exposée :** Opérations CRUD pour gérer ces données.

---

## 2. Communication Inter-Services

La communication est la clé d'une architecture microservices réussie. Nous privilégierons une approche asynchrone pour garantir le découplage et la résilience.

### Approche Asynchrone : Événements de Domaine (Fortement recommandée)

Les services communiquent en publiant des événements dans un **Message Broker** (ex: RabbitMQ, Apache Kafka) sans savoir qui les consommera.

**Exemple de flux - Acceptation d'un devis :**
1.  Un client accepte un devis via l'interface utilisateur. La requête arrive au **Service `Sourcing`**.
2.  Le Service `Sourcing` valide l'action, met à jour le statut de la `Quotation` à `accepted` et publie un événement `QuotationWasAccepted` dans le message broker.
3.  Le **Service `Ordering`**, qui est abonné à cet événement, le reçoit. Il crée alors une nouvelle `SourcingOrder` avec le statut `pending_payment` dans sa propre base de données.
4.  Le **Service `Notification`**, également abonné, reçoit l'événement et envoie un email de confirmation au client.

![Diagramme de communication asynchrone](https://i.imgur.com/ePLqjX5.png)

### Approche Synchrone : Appels API (REST ou gRPC)

À utiliser lorsque une réponse immédiate est nécessaire.

-   **Exemple :** Le frontend affiche une commande et a besoin du nom du client. La requête arrive à la Gateway API, qui la transmet au **Service `Ordering`**. Ce dernier, qui ne connaît que le `user_id`, fait un appel HTTP GET au **Service `Identity`** (`GET /users/{user_id}`) pour récupérer les détails du client avant de renvoyer la réponse complète.
-   **Inconvénients :** Crée un couplage temporel. Si le Service `Identity` est lent ou en panne, le Service `Ordering` est impacté. Des patterns comme le **Circuit Breaker** sont nécessaires pour gérer ces pannes.

---

## 3. Le "Frontend" de l'Architecture : Gateway API

Le client (application web, mobile) ne communique jamais directement avec les microservices. Il passe par une **Gateway API** (ex: Kong, Traefik, ou un service custom Node.js/Laravel).

**Rôles de la Gateway :**
-   **Point d'Entrée Unique :** Simplifie la configuration du client.
-   **Authentification :** Valide les tokens JWT en entrée pour sécuriser l'accès à l'ensemble de l'architecture. Elle peut interroger le **Service `Identity`** pour cela.
-   **Routage :** Redirige les requêtes vers le microservice approprié. `GET /api/orders/*` va au Service `Ordering`, `POST /api/login` va au Service `Identity`.
-   **Agrégation de Données :** Peut fusionner les réponses de plusieurs services pour répondre à une seule requête du client.

---

## 4. Gestion des Données : Une Base de Données par Service

C'est une règle d'or. **Chaque microservice est le seul propriétaire de ses données.**

-   **Avantages :**
    -   **Autonomie :** Le schéma de la base de données du Service `Ordering` peut être modifié sans impacter le Service `Sourcing`.
    -   **Technologie Adaptée :** Le Service `Identity` peut utiliser une base de données optimisée pour la lecture rapide (comme PostgreSQL), tandis qu'un service d'analytique pourrait utiliser une base NoSQL.
-   **Défis :**
    -   **Transactions Distribuées :** Les transactions ACID sur plusieurs services sont impossibles. Il faut utiliser le **Saga Pattern** pour gérer les workflows complexes qui impliquent plusieurs services.
    -   **Cohérence Éventuelle :** Les données ne sont pas toujours instantanément cohérentes entre les services. Il faut accepter ce compromis.

---

## 5. Stratégie de Migration : Le Pattern "Strangler Fig"

Il est hors de question de tout réécrire en une seule fois. La migration doit être progressive.

1.  **Étape 1 : Isoler un Contexte simple.**
    -   Le **Service `Identity`** est un excellent premier candidat. Créez ce service en tant que nouvelle application.
    -   Déployez-le et assurez-vous qu'il fonctionne.
    -   Modifiez le monolithe Laravel pour qu'il utilise ce nouveau service pour l'authentification au lieu de sa propre logique.

2.  **Étape 2 : Introduire la Gateway API.**
    -   Mettez en place la gateway et faites-la pointer vers le monolithe pour tout le trafic, sauf pour les routes d'authentification (`/login`, `/register`), qu'elle redirigera vers le nouveau Service `Identity`.

3.  **Étape 3 : Extraire le Contexte `Sourcing`.**
    -   Créez le **Service `Sourcing`**.
    -   Commencez par "étrangler" la fonctionnalité de lecture (GET). Modifiez la gateway pour que les requêtes `GET /api/sourcing-requests` aillent vers le nouveau service. Le monolithe peut encore gérer les écritures (POST, PUT).
    -   Une fois la lecture stable, migrez la logique d'écriture.

4.  **Répétez le processus** pour chaque contexte (`Ordering`, `Notification`, etc.) jusqu'à ce que le monolithe d'origine soit suffisamment "maigre" pour être soit supprimé, soit conservé pour des fonctions administratives restantes.
