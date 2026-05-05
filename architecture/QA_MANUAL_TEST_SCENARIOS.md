# Scénarios QA – Tests manuels complets

Document de scénarios pour tester l’application FastSourcingBrothers manuellement (par rôle et parcours).

---

## 1. Authentification et compte

### 1.1 Inscription (client)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 1.1.1 | Aller sur `/register`, remplir nom, email, téléphone, mot de passe (8+ car., maj, min, chiffre, symbole) | Formulaire validé, compte créé |
| 1.1.2 | S’inscrire avec un email déjà utilisé | Message d’erreur "email already taken" |
| 1.1.3 | S’inscrire avec mot de passe trop faible | Message de validation Laravel |
| 1.1.4 | Après inscription réussie | Redirection vers page "Verify your email" |
| 1.1.5 | Vérifier la boîte mail | Email de vérification reçu (lien cliquable) |
| 1.1.6 | Cliquer sur le lien de vérification | Compte vérifié, redirection dashboard client |

### 1.2 Connexion / Déconnexion
| # | Action | Résultat attendu |
|---|--------|------------------|
| 1.2.1 | Se connecter avec email/mot de passe valides (client) | Redirection `/client/dashboard` |
| 1.2.2 | Se connecter avec identifiants invalides | Message "Invalid credentials" |
| 1.2.3 | Se connecter en tant qu’admin / super_admin | Redirection `/admin/dashboard` |
| 1.2.4 | Cliquer sur "Log out" | Déconnexion, redirection page d’accueil ou login |

### 1.3 Mot de passe oublié
| # | Action | Résultat attendu |
|---|--------|------------------|
| 1.3.1 | Cliquer "Forgot password", saisir un email existant | Message "reset link sent", email reçu |
| 1.3.2 | Cliquer sur le lien dans l’email | Page "Reset password" s’affiche |
| 1.3.3 | Saisir un nouveau mot de passe (règles respectées) | Mot de passe mis à jour, redirection login |

### 1.4 Profil (utilisateur connecté)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 1.4.1 | Aller sur `/profile`, modifier nom ou téléphone, sauvegarder | Données mises à jour, message de succès |
| 1.4.2 | Changer l’email | Nouvel email demandé, nouvel email de vérification envoyé |
| 1.4.3 | Changer le mot de passe (mot de passe actuel + nouveau) | Mot de passe mis à jour |

### 1.5 Langue (EN/FR)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 1.5.1 | Changer la langue (EN ou FR) via le sélecteur | Interface et libellés dans la langue choisie |
| 1.5.2 | Naviguer après changement | La langue reste cohérente sur les pages visitées |

---

## 2. Parcours client

### 2.1 Sourcing Request (demande de sourcing)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.1.1 | Client : Créer une nouvelle demande (produit, catégorie, pays, quantités, adresses, etc.) | Demande créée, statut approprié (ex. "handling") |
| 2.1.2 | Ajouter plusieurs destinations (pays + quantités + adresses) | Toutes les destinations enregistrées |
| 2.1.3 | Joindre une image produit | Image uploadée et affichée |
| 2.1.4 | Voir la liste "En cours" / "Historique" | Demandes correctement filtrées |
| 2.1.5 | Dupliquer une demande existante | Nouvelle demande créée avec données copiées |
| 2.1.6 | Annuler une demande (si autorisé) | Statut passé à "cancelled" ou équivalent |

### 2.2 Devis (Quotation)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.2.1 | Client : Voir la liste des devis | Devis reçus listés avec statuts |
| 2.2.2 | Ouvrir un devis (détails, montant, devise) | Détails corrects, boutons Accepter / Refuser / Négocier visibles si applicable |
| 2.2.3 | Accepter un devis | Commande (Sourcing Order) créée, statut "pending_payment", notification admin |
| 2.2.4 | Refuser un devis | Statut devis "rejected", message de confirmation |
| 2.2.5 | Demander une négociation | Statut "negotiating", message/notification admin |

### 2.3 Commandes (Sourcing Orders)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.3.1 | Client : Voir la liste des commandes | Commandes listées avec statuts, filtres si présents |
| 2.3.2 | Ouvrir une commande (détails, montant, destinations) | Infos cohérentes avec le devis et les destinations |
| 2.3.3 | Télécharger le reçu (receipt) si disponible | PDF ou page reçu correcte |
| 2.3.4 | Exporter la liste (Excel/CSV si proposé) | Fichier téléchargé avec les données attendues |

### 2.4 Preuve de paiement (Proof of payment)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.4.1 | Sur une commande "pending_payment", aller sur "Upload proof of payment" | Formulaire ou zone d’upload affichée |
| 2.4.2 | Envoyer un fichier (image/PDF) valide | Upload réussi, statut passe à "paid" (ou équivalent), notification FSB tracking envoyée une fois |
| 2.4.3 | Télécharger sa preuve depuis la fiche commande | Fichier téléchargé correctement |

### 2.5 Suivi (Tracking) – client
| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.5.1 | Aller sur la page Tracking, saisir un numéro FSB (ex. FSB000010) | Résultat : statut virtuel ou réel selon configuration |
| 2.5.2 | Commande avec une seule destination : affichage du FSB et du bouton "Track" | Un FSB, un bouton, lien vers la même commande |
| 2.5.3 | Commande avec plusieurs destinations : chaque destination a son FSB (FSB000010, FSB000011…) | FSB distincts par destination, pas de numéro de tracking réel exposé au client |
| 2.5.4 | Quand un vrai tracking est assigné par l’admin : client voit toujours le FSB et le badge "Live tracking" si applicable | Pas d’affichage du numéro réel, seulement statut "live" |
| 2.5.5 | Depuis la fiche commande client : cliquer "Track this parcel" pour une destination | Redirection tracking avec le bon FSB |

### 2.6 Remboursements (Refunds)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.6.1 | Client : Liste des remboursements | Demandes de remboursement listées avec statuts |
| 2.6.2 | Créer une demande de remboursement sur une commande éligible (montant, catégorie, preuves) | Demande créée, statut "pending" ou équivalent |
| 2.6.3 | Montant supérieur au restant remboursable | Message d’erreur de validation |
| 2.6.4 | Voir le détail d’une demande (preuves, statut) | Détails cohérents, traductions EN/FR si prévues |

### 2.7 Frais de livraison (Shipping fees) – client
| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.7.1 | Aller sur la page Shipping fees, choisir catégorie / pays | Grille ou liste des tarifs affichée pour le pays choisi |

### 2.8 Historique et notifications
| # | Action | Résultat attendu |
|---|--------|------------------|
| 2.8.1 | Consulter l’historique (timeline) du compte | Activité (demandes, devis, commandes) affichée |
| 2.8.2 | Exporter l’historique (si proposé) | Fichier exporté |
| 2.8.3 | Ouvrir les notifications (cloche / page notifications) | Liste des notifications, marquer comme lu / tout marquer / effacer |

---

## 3. Parcours admin

### 3.1 Demandes de sourcing (admin)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.1.1 | Liste des demandes avec filtres (statut, recherche) | Résultats corrects |
| 3.1.2 | Assigner une demande à soi-même (ou à un admin) | Assignation enregistrée, visible sur la demande |
| 3.1.3 | Libérer une demande assignée | Demande libérée, un autre admin peut s’assigner |
| 3.1.4 | Changer le statut d’une demande (si proposé) | Statut mis à jour |
| 3.1.5 | Créer une demande pour un client (création de compte client si besoin) | Client créé ou sélectionné, demande créée et liée |

### 3.2 Devis (admin)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.2.1 | Sélectionner une demande pour créer un devis | Formulaire pré-rempli avec la demande |
| 3.2.2 | Créer un devis (prix unitaire, quantités, montants, devise, médias) | Devis créé, notification client envoyée |
| 3.2.3 | Modifier un devis (brouillon ou selon règles métier) | Modifications enregistrées |
| 3.2.4 | Approuver / Rejeter un devis (si workflow prévu) | Statut et notifications cohérents |

### 3.3 Commandes (admin)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.3.1 | Liste des commandes avec filtres et recherche | Résultats corrects |
| 3.3.2 | Ouvrir une commande : détails, statut, destinations, tracking | Toutes les infos affichées correctement |
| 3.3.3 | Changer le statut (ex. pending_payment → paid → shipped, etc.) | Transitions autorisées uniquement, statut mis à jour |
| 3.3.4 | Télécharger la preuve de paiement | Fichier correct |
| 3.3.5 | Rejeter la preuve de paiement (avec motif si prévu) | Statut repasse à "pending_payment" ou équivalent, client notifié |
| 3.3.6 | Mettre à jour les données financières (coûts, etc.) | Données enregistrées, pas d’erreur |
| 3.3.7 | Générer / télécharger l’étiquette d’expédition (PDF) – commande multi-destinations | Une étiquette par destination (ou une globale selon spec) |
| 3.3.8 | Upload / suppression de médias sur la commande | Fichiers ajoutés/supprimés et visibles |
| 3.3.9 | Supprimer une commande (si autorisé) | Commande supprimée, pas d’effet de bord inattendu |

### 3.4 Tracking (admin)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.4.1 | Sur une commande, assigner une société de livraison | Société enregistrée |
| 3.4.2 | Saisir un numéro de tracking réel par destination (si multi-destinations) | Chaque destination a son tracking et transporteur |
| 3.4.3 | Utiliser "Deep tracking" / "Refresh tracking" pour une destination | Statut réel récupéré (pas seulement "pending"), affiché dans l’admin |
| 3.4.4 | Vérifier que le client ne voit jamais le numéro réel, seulement le FSB | Côté client : uniquement FSB + badge "Live tracking" si applicable |

### 3.5 Intégration sheets (Google / Lark)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.5.1 | Sur une commande, déclencher "Sync to Google Sheet" ou "Force Sync to Lark" | Sync exécutée, message succès ou erreur clair |
| 3.5.2 | Vérifier dans le sheet (Google ou Lark) : en-têtes (PICTURE, SELLING DATE, PRODUCT NAME, QUANTITY, PRODUCT PRICE, TOTAL PRICE, TRACKING NUMBER FROM CHINA, Shipping address, LABEL SHIPPING) | En-têtes conformes |
| 3.5.3 | Vérifier une ligne : image produit, date, nom produit, quantité, prix unitaire, total (prix × quantité), colonnes tracking/adresse vides, image du label d’expédition | Données et images (PICTURE, LABEL SHIPPING) corrects, formule IMAGE utilisée |
| 3.5.4 | Commande multi-destinations : une ligne par destination dans le sheet | Autant de lignes que de destinations |

### 3.6 Remboursements (admin)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.6.1 | Liste des demandes de remboursement, filtres | Liste correcte |
| 3.6.2 | Ouvrir une demande, voir preuves et montant | Détails cohérents |
| 3.6.3 | Approuver une demande | Statut "approved", montant remboursé pris en compte |
| 3.6.4 | Rejeter une demande (avec motif si prévu) | Statut "rejected", client notifié |
| 3.6.5 | S’assigner une demande "Assign to me" | Assignation enregistrée |

### 3.7 Rapports
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.7.1 | Rapport Sales Margin : filtre par date, affichage des totaux par devise d’origine | Pas de conversion automatique, montants en devise d’origine |
| 3.7.2 | Export Excel / PDF du rapport | Fichier généré et cohérent avec l’écran |
| 3.7.3 | Rapport financier : vue principale et onglet/rapport Refunds | Données et devises cohérentes |
| 3.7.4 | Rapport Refunds : totaux et filtres | Résultats corrects |

### 3.8 Référentiels (admin)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.8.1 | CRUD Catégories | Création, modification, suppression OK |
| 3.8.2 | CRUD Services | Idem |
| 3.8.3 | CRUD Pays (Countries) | Idem |
| 3.8.4 | CRUD Payment methods | Idem |
| 3.8.5 | Liens réseaux sociaux (edit/update) | Sauvegarde et affichage sur les pages prévues |

### 3.9 Google Sheets (paramètres)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 3.9.1 | Upload des credentials Google (JSON) | Fichier accepté, pas d’erreur 500 |
| 3.9.2 | Test de connexion | Succès ou message d’erreur explicite |
| 3.9.3 | Install headers / Create sheet / Sync all (selon écrans) | Actions exécutées, logs ou messages cohérents |
| 3.9.4 | Consulter les logs de sync | Liste des opérations récentes |

---

## 4. Parcours Super Admin

### 4.1 Gestion des admins
| # | Action | Résultat attendu |
|---|--------|------------------|
| 4.1.1 | Créer un nouvel admin (email, nom, mot de passe) | Compte admin créé, peut se connecter |
| 4.1.2 | Lister les admins | Liste complète |
| 4.1.3 | Modifier un admin (nom, email, mot de passe) | Modifications enregistrées |
| 4.1.4 | Supprimer un admin (pas le dernier super_admin) | Admin supprimé |
| 4.1.5 | Tenter de supprimer le dernier super_admin | Empêché avec message clair |

### 4.2 Calendrier d’expéditions (Shipment calendar)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 4.2.1 | Ouvrir la page Calendrier | Événements chargés (commandes / étapes selon la logique métier) |
| 4.2.2 | Changer de mois / vue | Données mises à jour |

### 4.3 Frais de livraison (import)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 4.3.1 | Importer un fichier Excel de frais (selon format attendu) | Import réussi, données visibles côté admin et client |
| 4.3.2 | Importer un fichier invalide | Message d’erreur sans crash |

### 4.4 Sociétés de livraison (Shipping companies)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 4.4.1 | Créer une société (nom, type Google Sheet / Lark, config) | Société créée |
| 4.4.2 | Tester la connexion Lark / Google | Succès ou erreur explicite |
| 4.4.3 | Install headers sur le sheet de la société | En-têtes installés, pas d’erreur dimension (ex. Lark startIndex) |
| 4.4.4 | Force Sync d’une commande vers cette société | Ligne(s) visibles dans le sheet avec images et label |

### 4.5 Tracking (test)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 4.5.1 | Page "Tracking test", saisir un numéro de tracking connu (transporteur configuré) | Résultat de tracking affiché ou message d’erreur clair |
| 4.5.2 | Consulter les logs de tracking (admin) | Entrées listées |

### 4.6 Utilisateurs (liste / suppression)
| # | Action | Résultat attendu |
|---|--------|------------------|
| 4.6.1 | Liste des utilisateurs (filtres par rôle si prévus) | Liste correcte |
| 4.6.2 | Supprimer un utilisateur client (si autorisé par politique) | Utilisateur supprimé, comportement cohérent sur ses données |

---

## 5. Notifications et emails

| # | Action | Résultat attendu |
|---|--------|------------------|
| 5.1 | Après création de devis : client reçoit une notification (in-app et/ou email) | Notification reçue, lien vers le devis |
| 5.2 | Après acceptation de devis : admin reçoit une notification | Notification reçue, lien vers la commande |
| 5.3 | Après upload de preuve de paiement : une seule notification FSB (avec tous les FSB si multi-destinations) | Pas de double envoi, liste des FSB dans le corps si plusieurs destinations |
| 5.4 | Après assignation d’un tracking réel par l’admin : pas de nouvelle notification "tracking" au client (éviter doublon avec 5.3) | Client ne reçoit pas une 2e notification identique |
| 5.5 | Demande de remboursement créée : admin notifié | Notification reçue |
| 5.6 | Remboursement approuvé / rejeté : client notifié | Notification et email si prévus |

---

## 6. Cas limites et régressions

| # | Scénario | Résultat attendu |
|---|----------|------------------|
| 6.1 | Commande avec 0 destination (données incohérentes) | Comportement défini : message d’erreur ou blocage en amont |
| 6.2 | Montant remboursement > restant remboursable | Refus avec message clair |
| 6.3 | Transition de statut non autorisée (ex. paid → pending_payment) | Refus, message ou validation côté service |
| 6.4 | Accès à une URL admin en tant que client | 403 Forbidden |
| 6.5 | Accès à une URL client en tant qu’admin | Comportement défini (redirection ou 403) |
| 6.6 | Client non vérifié (email) accède aux routes protégées client | Redirection vers vérification email (middleware verified.client) |
| 6.7 | Sync sheet alors qu’aucune société n’est assignée à la commande | Message explicite, pas d’erreur 500 |
| 6.8 | Fichier trop lourd ou type non autorisé (preuve de paiement, image produit) | Validation côté formulaire, message d’erreur |

---

## 7. Checklist rapide par release

- [ ] Inscription + vérification email + login
- [ ] Client : créer demande → recevoir devis → accepter → upload preuve → voir commande et FSB
- [ ] Client : tracking par FSB (1 et N destinations), pas de numéro réel affiché
- [ ] Client : demande de remboursement (création + vue)
- [ ] Admin : créer devis, gérer commandes (statut, financials, tracking, étiquettes)
- [ ] Admin : sync Google Sheet + Lark (headers, données, images produit et label)
- [ ] Admin : rapports (sales margin, financial, refunds) en devise d’origine
- [ ] Admin : remboursements (approuver / rejeter)
- [ ] Super Admin : admins, shipping companies, calendar, import shipping fees
- [ ] Notifications : pas de doublon FSB, une notification à la preuve de paiement
- [ ] Langue EN/FR sur les écrans critiques
- [ ] Déconnexion et accès refusé (403) selon les rôles

Utiliser ce document comme base de scénarios pour des tests manuels complets avant chaque livraison ou release majeure.
