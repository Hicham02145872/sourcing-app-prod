# Plan d'Intégration : Création de Sourcing par l'Admin (Post-External)

Ce document détaille l'approche pour permettre aux administrateurs de créer des demandes de sourcing manuellement pour le compte de clients (existant ou nouveau/externe), tout en suivant le flux complet de l'application (Quotation -> Commande -> Paiement -> Tracking).

## Objectif
Permettre à un administrateur d'initier une demande de sourcing reçue hors-ligne (ex: WhatsApp, Email, Téléphone) dans le système pour centraliser la gestion et le suivi.

## Architecture & Flux

### 1. Sélection ou Invitation du Client
L'admin doit pouvoir :
- Sélectionner un client existant via un champ de recherche.
- Ou créer un "Client Invité" en saisissant simplement son Email et Téléphone. Le système créera un compte utilisateur avec un mot de passe temporaire ou un lien d'activation envoyé par mail.

### 2. Formulaire de Création (Admin)
Un nouveau formulaire dans le panel admin (`/admin/sourcing-requests/create`) permettra de saisir :
- **Produit** : Nom, Catégorie, Notes.
- **Logistique** : Méthode d'expédition, Destinations (Pays + Services + Quantités).
- **Photos** : Upload direct par l'admin.

### 3. Assignation Automatique
Puisque l'admin crée la demande, elle lui sera **automatiquement assignée**.

---

## Plan d'Implémentation

### Phase 1 : Backend & Routes
- [ ] **Routes Admin** : Ajouter `GET /admin/sourcing-requests/create` et `POST /admin/sourcing-requests/store`.
- [ ] **Validation (Form Request)** : Créer une `StoreAdminSourcingRequest` qui valide à la fois les données du produit et les informations du client (existant ou nouveau).
- [ ] **Logique du Contrôleur** :
    - Vérifier si l'utilisateur existe déjà par email.
    - Si non, créer l'utilisateur avec le rôle `client`.
    - Créer la `SourcingRequest` rattachée à cet utilisateur.
    - Créer les `SourcingRequestDestination` associées.

### Phase 2 : Interface Utilisateur (Admin)
- [ ] **Vue Blade** : Créer `resources/views/admin/sourcing-requests/create.blade.php`.
- [ ] **Composant de Sélection** : Utiliser un sélecteur (ex: Select2 ou TomSelect) pour filtrer les clients existants.
- [ ] **Intégration du Design** : Utiliser le thème "Enterprise Slate" pour la consistance.

### Phase 3 : Flux Complet & Notifications
- [ ] **Notifications** : Envoyer un mail de bienvenue au nouveau client avec ses accès et le lien vers sa demande.
- [ ] **Redirection** : Après création, rediriger vers la page de détails (`show`) où l'admin peut immédiatement commencer le chiffrage (Quotation).

---

## Avantages
- **Centralisation** : Toutes les demandes, même celles initiées hors-ligne, sont dans la base de données.
- **Suivi Client** : Le client reçoit ses accès et peut suivre sa commande sur son tableau de bord dès que l'admin a validé les infos.
- **Reporting** : Les rapports financiers (MAD) incluront naturellement ces nouvelles données.
