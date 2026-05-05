# Plan d'Intégration du Paiement en Ligne Stripe

**Objectif :** permettre au client de payer en ligne une commande `SourcingOrder` via Stripe, puis synchroniser automatiquement l’état de paiement avec l’application.

## 1. Contexte actuel

L’application possède déjà un socle paiement suffisamment clair pour intégrer Stripe sans refonte majeure :
- statut de commande `pending_payment` puis `paid`
- écran client de commande avec paiement et preuve de paiement
- entité `PaymentMethod` pour gérer les moyens de paiement disponibles
- événements existants autour du changement de statut de commande
- logique de notification et de suivi déjà branchée sur l’état de la commande

Le bon choix ici est d’ajouter Stripe comme canal de paiement réel, tout en conservant le flux actuel de suivi et de notification.

## 2. Recommandation technique

### Approche recommandée
Utiliser **Stripe Checkout** pour la page de paiement et **Stripe Webhooks** pour confirmer le paiement côté serveur.

Pourquoi :
- sécurité supérieure par rapport à un formulaire carte maison
- moindre charge de conformité PCI
- intégration rapide
- redirection simple depuis la commande client
- confirmation fiable grâce aux webhooks Stripe

### Flux cible
1. Le client ouvre une commande en `pending_payment`.
2. Il clique sur Payer maintenant.
3. L’application crée une session Stripe Checkout.
4. Stripe redirige le client vers la page de paiement.
5. Stripe appelle un webhook serveur quand le paiement est confirmé.
6. L’ordre passe à `paid`.
7. Les événements existants se déclenchent : notifications, tracking FSB, synchronisations éventuelles.

## 3. Modèle de données à prévoir

Ajouter, au minimum, les champs suivants sur `sourcing_orders` ou une table de paiements séparée si vous voulez historiser plusieurs tentatives :
- `payment_provider` : ex. `stripe`
- `payment_status` : ex. `pending`, `requires_action`, `paid`, `failed`, `refunded`
- `stripe_checkout_session_id`
- `stripe_payment_intent_id`
- `stripe_customer_id` si utile pour les clients récurrents
- `paid_at`
- `payment_currency`
- `payment_amount`

Si vous voulez une solution plus robuste, créer une table `payments` est préférable pour tracer :
- plusieurs tentatives
- remboursements
- paiements partiels
- historique des événements Stripe

## 4. Écrans et parcours utilisateur

### Côté client
- bouton Payer avec Stripe sur la page de commande
- affichage du statut de paiement
- message de succès après retour Stripe
- affichage d’un état clair si paiement en attente ou échoué

### Côté admin
- visibilité du provider de paiement utilisé
- filtre par statut de paiement
- accès au détail des événements Stripe liés à la commande
- action de remboursement si vous l’ouvrez plus tard

## 5. Intégration backend

### Étape 1 : Configuration Stripe
- ajouter les variables `.env`
- configurer une classe `config/stripe.php`
- stocker les clés Stripe en sécurité
- séparer clé publique, clé secrète et secret webhook

Variables attendues :
- `STRIPE_KEY`
- `STRIPE_SECRET`
- `STRIPE_WEBHOOK_SECRET`
- `STRIPE_CURRENCY`

### Étape 2 : Dépendance
- installer le SDK Stripe PHP
- centraliser l’accès Stripe dans un service dédié, par exemple `App\Services\Payments\StripeService`

### Étape 3 : Création de session Checkout
- créer une route ou une action dédiée pour initier le paiement
- construire la session Stripe à partir de la commande
- envoyer le montant exact, la devise, l’email client et les métadonnées utiles
- inclure `sourcing_order_id` dans les metadata Stripe

### Étape 4 : Webhook Stripe
Gérer au minimum les événements suivants :
- `checkout.session.completed`
- `payment_intent.succeeded`
- `payment_intent.payment_failed`
- `charge.refunded`

Le webhook doit :
- vérifier la signature Stripe
- retrouver la commande via les metadata
- éviter les doubles traitements
- mettre à jour le statut en base
- journaliser l’événement reçu

### Étape 5 : Synchronisation métier
Quand Stripe confirme le paiement :
- passer la commande à `paid`
- enregistrer l’ID de session / payment intent
- déclencher l’événement existant de changement de statut
- continuer le flux actuel de notifications et d’assignation tracking

## 6. Sécurité et fiabilité

### Points obligatoires
- ne jamais faire confiance au front pour confirmer un paiement
- confirmer uniquement via webhook Stripe
- vérifier la signature des webhooks
- éviter les doublons de traitement avec une clé d’idempotence
- consigner chaque événement Stripe dans les logs
- ne pas stocker de données carte en base

### Gestion d’erreurs
- session Stripe expirée
- paiement annulé par le client
- paiement partiellement confirmé
- webhook reçu en retard ou plusieurs fois
- montant discordant entre commande et session Stripe

## 7. Impacts sur le code existant

Les zones probables à adapter :
- `Client\SourcingOrderController` pour démarrer le paiement
- `SourcingOrder` pour stocker les champs Stripe et le statut métier
- `EventServiceProvider` si vous ajoutez un événement Stripe dédié
- vues client de commande pour afficher le bouton de paiement et les statuts
- éventuellement `PaymentMethod` si vous voulez afficher Stripe comme méthode active

## 8. Plan d’implémentation par phases

### Phase 1 : Socle
- créer les champs de base de données
- installer le SDK Stripe
- ajouter la configuration `.env`
- créer le service Stripe

### Phase 2 : Paiement client
- créer la session Checkout
- rediriger le client vers Stripe
- gérer le retour succès / annulation

### Phase 3 : Confirmation serveur
- implémenter le webhook
- mettre à jour la commande en `paid`
- journaliser les événements Stripe

### Phase 4 : UX et admin
- afficher l’état paiement dans l’espace client
- ajouter des badges de statut côté admin
- prévoir les actions de suivi et d’exception

### Phase 5 : Durcissement
- tests unitaires et feature tests
- test webhook simulé
- test de double callback
- test de désynchronisation montant/statut

## 9. Stratégie de test

Cas à couvrir :
- création d’une session Stripe avec montant correct
- webhook valide qui passe l’ordre à `paid`
- webhook invalide rejeté
- paiement annulé reste en `pending_payment`
- webhook dupliqué ne repasse pas l’ordre deux fois
- commande déjà payée reste stable

## 10. Décision produit à valider

Avant de coder, il faut trancher ces points :
- paiement unique par commande ou paiements multiples possibles ?
- remboursement manuel uniquement ou partiellement automatisé ?
- Stripe seulement ou Stripe + autres moyens de paiement ?
- devise unique ou multi-devises ?

## 11. Recommandation finale

Pour aller vite et rester robuste :
- partir sur **Stripe Checkout**
- utiliser **webhooks** comme source de vérité
- garder le statut métier `pending_payment` -> `paid`
- intégrer Stripe comme un provider de paiement dans le système existant
- ajouter une table `payments` seulement si vous voulez historiser plusieurs tentatives et remboursements de manière propre

---

Si tu veux, je peux maintenant transformer ce plan en plan d’exécution concret avec les fichiers à créer/modifier dans l’ordre exact.
