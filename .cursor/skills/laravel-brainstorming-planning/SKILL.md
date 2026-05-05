---
name: laravel-brainstorming-planning
description: Guide le brainstorming et la planification pour des projets Laravel. Structure les idées en fonctionnalités, organise les tâches selon les patterns Laravel (MVC, DDD, Services), et crée des plans d'implémentation. Utilise quand l'utilisateur demande de brainstormer, planifier une fonctionnalité, organiser des idées, ou créer un plan d'implémentation pour Laravel.
---

# Laravel Brainstorming & Planning

Guide pour structurer le brainstorming et créer des plans d'implémentation pour des projets Laravel.

## Intégration avec Laravel Boost

Lorsque tu utilises ce skill dans ce projet, tu dois t'appuyer sur **Laravel Boost** (MCP dédié à cette app) pour sécuriser tes choix techniques :
- **Toujours vérifier la doc avant de figer un plan** : utiliser l'outil `search-docs` de Laravel Boost pour Laravel 12, Livewire 3, Tailwind, etc., avec des requêtes simples de type « rate limiting », « notifications », « queues », sans ajouter le nom des packages.
- **Artisan & génération de code** : avant de proposer des commandes `artisan make:*`, utiliser `list-artisan-commands` pour confirmer les options disponibles, puis respecter la règle « tout passe par `vendor\bin\sail` » (`vendor\bin\sail artisan make:model`, etc.).
- **Debug / validation d'idées** : pour confirmer rapidement une hypothèse sur des modèles ou des requêtes, utiliser `tinker` (exécution PHP) ou `database-query` (lecture seule) plutôt que d'inventer des scripts ad‑hoc.
- **Logs navigateur & front** : si le plan implique du front (Livewire, Blade, Tailwind), prévoir l'usage de `browser-logs` pour inspecter les erreurs JS/front et ajuster le plan.
- **Conventions & structure** : respecter strictement les règles de `GEMINI.md` : ne pas changer les dépendances, ne pas inventer de nouveaux dossiers racine, coller à la structure Laravel 12 et à Sail.

Intègre systématiquement ces outils dans le **workflow de planification** : d'abord brainstorm → ensuite vérification via Laravel Boost → puis seulement rédaction du plan détaillé.

## Workflow de Brainstorming

### 1. Collecte des Idées

Lors du brainstorming initial :
- Capturer toutes les idées sans filtrage
- Noter les besoins métier et les contraintes techniques
- Identifier les acteurs (utilisateurs, admins, systèmes externes)
- Définir les objectifs principaux

### 2. Structuration Laravel

Organiser les idées selon l'architecture Laravel :

```
Fonctionnalité → Domaines → Composants → Tâches
```

**Domaines identifiables :**
- Authentification & Autorisation
- Gestion des données (CRUD)
- Workflows métier (états, transitions)
- Intégrations externes (APIs, webhooks)
- Notifications (email, push, database)
- Rapports & Analytics
- Administration

### 3. Plan d'Implémentation

Créer un plan structuré avec :

```markdown
# [Nom de la Fonctionnalité]

## 1. Vue d'ensemble
- Objectif
- Acteurs concernés
- Valeur métier

## 2. Architecture Laravel

### Modèles (Models)
- [ ] Modèle principal : `ModelName`
- [ ] Relations : `belongsTo`, `hasMany`, etc.
- [ ] Migrations nécessaires

### Contrôleurs (Controllers)
- [ ] `ControllerName` pour les routes client
- [ ] `Admin/ControllerName` pour les routes admin
- [ ] Actions CRUD requises

### Routes
- [ ] Routes client (`routes/web.php` ou `routes/api.php`)
- [ ] Routes admin (`routes/admin.php`)
- [ ] Middleware requis (auth, role, etc.)

### Policies & Autorisations
- [ ] Policy : `ModelNamePolicy`
- [ ] Règles d'autorisation par rôle

### Services
- [ ] Service métier : `ServiceName`
- [ ] Logique complexe à extraire des contrôleurs

### Événements & Listeners
- [ ] Événements : `ModelNameCreated`, `ModelNameUpdated`
- [ ] Listeners : notifications, logs, intégrations

### Notifications
- [ ] Notification : `ModelNameNotification`
- [ ] Canaux : mail, database, FCM

### Vues (Blade)
- [ ] Vues client
- [ ] Vues admin
- [ ] Composants réutilisables

### Tests
- [ ] Tests Feature
- [ ] Tests Unit
- [ ] Tests d'intégration

## 3. Étapes d'Implémentation (par priorité)

### Phase 1 : Fondations
- [ ] Migration de base de données
- [ ] Modèle avec relations
- [ ] Policy de base

### Phase 2 : CRUD de base
- [ ] Contrôleur client
- [ ] Contrôleur admin
- [ ] Routes
- [ ] Vues de base

### Phase 3 : Logique métier
- [ ] Service métier
- [ ] Validation avancée
- [ ] Workflows d'état

### Phase 4 : Améliorations
- [ ] Notifications
- [ ] Événements
- [ ] Tests complets
- [ ] Optimisations

## 4. Considérations Techniques

### Performance
- Indexes de base de données nécessaires
- Cache potentiel
- Pagination requise

### Sécurité
- Validation des entrées
- Autorisations par rôle
- Protection CSRF

### Intégrations
- APIs externes
- Webhooks entrants/sortants
- Services tiers

## 5. Points d'Attention
- Contraintes existantes
- Dépendances avec d'autres modules
- Migrations de données si nécessaire
```

## Patterns Laravel à Considérer

### Structure MVC Standard
```
app/
├── Models/
├── Http/
│   ├── Controllers/
│   │   ├── Client/
│   │   └── Admin/
│   ├── Requests/
│   └── Middleware/
├── Services/
├── Events/
├── Listeners/
├── Notifications/
└── Policies/
```

### Domain-Driven Design (si applicable)
```
app/
├── [Domain]/
│   ├── Domain/
│   │   ├── Models/
│   │   ├── Events/
│   │   └── ValueObjects/
│   ├── Application/
│   │   └── Services/
│   └── Infrastructure/
│       └── Http/
│           └── Controllers/
```

### Services Pattern
Extraire la logique métier complexe dans des services :
- Validation métier
- Calculs complexes
- Appels API externes
- Workflows multi-étapes

### Repository Pattern (optionnel)
Pour les projets complexes avec beaucoup de requêtes :
- Centraliser les requêtes de base de données
- Faciliter les tests et la maintenance

## Checklist de Planification

Avant de commencer l'implémentation, vérifier :

- [ ] **Modèles** : Relations définies, fillable/guarded configurés
- [ ] **Migrations** : Colonnes, indexes, foreign keys
- [ ] **Routes** : Groupées par préfixe, middleware appropriés
- [ ] **Policies** : Autorisations pour chaque action
- [ ] **Validation** : Form Requests pour les entrées utilisateur
- [ ] **Services** : Logique métier extraite des contrôleurs
- [ ] **Événements** : Événements pour les actions importantes
- [ ] **Notifications** : Canaux appropriés (mail, database, FCM)
- [ ] **Tests** : Coverage des fonctionnalités critiques
- [ ] **Documentation** : Commentaires dans le code, README si nécessaire

## Exemples de Questions à Poser

Pour affiner le brainstorming :

1. **Acteurs** : Qui utilise cette fonctionnalité ? (client, admin, super_admin)
2. **États** : Y a-t-il des statuts/états à gérer ? (pending, approved, rejected)
3. **Workflows** : Y a-t-il des transitions d'état ? (approbation, rejet)
4. **Notifications** : Qui doit être notifié et quand ?
5. **Autorisations** : Qui peut créer/modifier/supprimer ?
6. **Intégrations** : Y a-t-il des APIs externes à intégrer ?
7. **Rapports** : Des statistiques ou rapports sont-ils nécessaires ?
8. **Historique** : Faut-il tracer les modifications (audit trail) ?

## Template de Brainstorming Rapide

```markdown
## Idée : [Nom]

**Objectif** : [Une phrase]

**Acteurs** : [Qui utilise]

**Fonctionnalités principales** :
- [ ] Fonctionnalité 1
- [ ] Fonctionnalité 2

**Composants Laravel nécessaires** :
- Modèles : [ ]
- Contrôleurs : [ ]
- Routes : [ ]
- Policies : [ ]
- Services : [ ]
- Événements : [ ]

**Priorité** : [Haute/Moyenne/Basse]

**Complexité estimée** : [Simple/Moyenne/Complexe]
```

## Conseils pour la Planification

1. **Commencer simple** : Implémenter d'abord le MVP, puis itérer
2. **Réutiliser** : Vérifier les composants existants avant de créer
3. **Séparer les responsabilités** : Contrôleurs légers, logique dans les Services
4. **Tester tôt** : Écrire les tests en parallèle du développement
5. **Documenter** : Noter les décisions importantes et les patterns utilisés
6. **Considérer la scalabilité** : Indexes DB, cache, pagination dès le début
