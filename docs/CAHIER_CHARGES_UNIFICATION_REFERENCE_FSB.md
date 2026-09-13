# Cahier des charges — Unification de la référence de commande et du suivi (FSB)

**Version :** 1.0
**Rédigé pour :** Client / partie non technique
**Objet :** Décrire le besoin, comprendre la complexité de la tâche et le temps nécessaire.

---

## 1. Contexte : le problème rencontré

Aujourd'hui, un client qui passe une commande reçoit **deux numéros différents** pour la même commande :

- **Un numéro de référence** qui ressemble à `SB00005` (c'est le numéro affiché comme « référence » de la commande).
- **Un numéro de suivi** qui ressemble à `FSB000043` (c'est le numéro à utiliser pour suivre la commande).

Quand le client veut suivre l'avancement de son colis, il tape le numéro qu'il voit sur sa commande — c'est-à-dire `SB00005` — dans la page de suivi. Mais **la page de suivi ne reconnaît que le numéro `FSB000043`** (ou le vrai numéro du transporteur). Résultat : le suivi **échoue** et le client ne comprend pas pourquoi. Mauvais pour le client, mauvais pour la confiance, et cela génère des demandes au support.

**Ce que nous voulons :** qu'il n'y ait **qu'un seul numéro**. Le numéro affiché sur la commande doit être le même que celui utilisé pour suivre, partout — côté client **et** côté équipe de gestion (administration).

---

## 2. Objectif du projet

> **Faire en sorte qu'une commande n'ait qu'un seul numéro : `FSB…`, utilisé aussi bien comme référence que comme numéro de suivi, de façon cohérente sur tout le système.**

Avant / Après, vu simplement :

| Aujourd'hui | Après le changement |
|---|---|
| Le client voit `SB00005` sur sa commande | Le client voit `FSB000043` sur sa commande |
| La page de suivi attend `FSB000043` | La page de suivi accepte le numéro affiché (`FSB000043`) |
| Le client tape `SB00005` → échec | Le client tape `FSB000043` → le suivi fonctionne |
| L'équipe et le client n'utilisent pas le même numéro | L'équipe et le client utilisent **le même numéro FSB** |

**Ce qui ne change PAS :**
- Le vrai numéro du transporteur (ex. numéro de suivi UPS/DHL) reste celui que le transporteur nous fournit. Il est enregistré par l'équipe, pas modifié.
- Le numéro « SB » reste présent en interne (visible par l'équipe pour retrouver des anciennes commandes et rechercher des dossiers), mais il ne sera **plus affiché au client ni utilisé comme référence de commande en façade**.

---

## 3. Pourtant, pourquoi est-ce « simple » et « complexe » à la fois ?

Pour un œil non technique, la demande paraît évidente : *« remplacez SB par FSB »*. En apparence, ce n'est qu'une étiquette à changer. **En réalité, ce n'est pas une simple étiquette** : c'est un numéro qui circule dans toute l'application, et chaque endroit où il apparaît doit être vérifié un par un.

Voici les raisons principales de la complexité.

### 3.1. Le même numéro est utilisé dans beaucoup d'endroits
Ce numéro apparaît :
- sur la page de la commande vue par le client,
- sur les listes de commandes,
- sur les étiquettes d'expédition imprimées,
- dans les e-mails et notifications (plusieurs types),
- dans les pages de l'équipe de gestion,
- dans les variables de recherche et les filtres internes.

Changer l'affichage en un endroit mais pas un autre recréerait exactement le bug actuel. **Tout doit être vérifié et coordonné**, endroit par endroit.

### 3.2. Deux numéros coexistaient pour de bonnes (anciennes) raisons
Histoiquement, le numéro « SB » a été créé pour représenter une **demande** de sourcing, et le numéro « FSB » pour le **suivi** de la commande. Ils couvrent deux notions différentes. Le changement doit unifier les deux **sans casser** les mécanismes internes (recherche, regroupement de dossiers, rapprochement des anciennes commandes).

### 3.3. Il faut gérer les anciennes commandes
Il existe déjà des milliers de commandes en base avec l'ancien numéro « SB ». Il faut s'assurer que :
- les anciennes commandes restent retrouvables par l'équipe (via le numéro « SB » stocké) ;
- leur affichage devient cohérent (FSB) sans perdre l'historique.

On ne peut pas simplement « supprimer » les anciennes valeurs : cela casserait la recherche et l'historique.

### 3.4. Le suivi n'est pas un simple affichage
C'est le cœur du système : un service qui « résout » un numéro vers une commande, vérifie le vrai numéro transporteur, interroge le transporteur (ou affiche un statut intermédiaire « en préparation »). Nous devons garantir qu'en passant à FSB partout, **tous ces cas de fonctionnement restent exactement les mêmes** :
- suivi d'une commande sans numéro transporteur (statut « en préparation ») ;
- suivi d'une commande avec le vrai numéro transporteur ;
- commande avec plusieurs destinations / plusieurs colis (chacun a son numéro FSB).

### 3.5. Des tests automatiques existent et doivent être mis à jour
Le projet dispose de **tests automatiques** qui vérifient ces numéros. En changeant le comportement, certains de ces tests deviennent obsolètes et doivent être réécrits, puis de **nouveaux tests** écrits pour garantir que le nouveau comportement reste stable dans le temps. C'est une bonne chose (cela protège contre les régressions), mais cela demande du temps supplémentaire.

### 3.6. Le changement touche à la fois « affichage » et « logique interne »
Ce n'est pas juste « changer le texte sur un bouton ». Le choix technique se situe dans la définition même du numéro de référence de la commande : il faut modifier **la source** qui produit ce numéro, puis vérifier que **chaque écran** qui le consomme affiche bien la nouvelle valeur. Un endroit oublié = le bug revient.

---

## 4. Comment nous allons procéder (les grandes étapes)

1. **Préparation et repérage** : établir la liste complète des endroits où le numéro apparaît (écrans client, écrans équipe, e-mails, étiquettes, fichiers internes de suivi).
2. **Modification de la source du numéro** : la référence de commande devient systématiquement le FSB.
3. **Mise à jour des écrans et notifications** : vérifier un à un tous les points de contact et remplacer toute apparition du numéro « SB » (côté client et côté équipe).
4. **Mise à jour des anciens tests + création de nouveaux tests** : pour garantir que le suivi fonctionne toujours et que rien ne « casse » ailleurs.
5. **Vérification complète** : relire chaque endroit pour s'assurer qu'il ne reste **aucun** affichage résiduel de « SB » pour une commande, et que le suivi fonctionne avec le FSB.
6. **Mise en production et contrôle** : déploiement puis vérification sur le site en conditions réelles.

---

## 5. Pourquoi cela prend du temps (et ce qui le rend nécessaire)

| Raison | Impact sur le temps |
|---|---|
| Nombre de points de contact à modifier et vérifier | Chaque écran/notification doit être contrôlé individuellement |
| Pérennité des anciennes commandes | Il faut garantir recherche + historique sans casse |
| Tests automatiques à refonder | Réécriture des tests utilisés + création de nouveaux tests |
| Complexité du moteur de suivi | Vérification de tous les cas (un colis, plusieurs colis, avec/sans transporteur) |
| Tests de non-régression | Vérification manuelle et automatique pour éviter de recréer le bug |

Cette tâche ne demande en réalité que **peu de nouvelles fonctionnalités** : elle demande surtout de la **rigueur de vérification**. Le temps est consacré à ne rien oublier, pour éviter de devoir revenir corriger plus tard (ce qui coûterait plus cher).

---

## 6. Risques et points d'attention

- **Oubli d'un écran** : si un écran affiche encore l'ancien numéro, le client peut retomber sur le bug initial. → Mitigation : vérification exhaustive et tests automatiques.
- **Casse des anciennes commandes** : si la recherche interne est mal refaite, l'équipe ne trouverait plus les dossiers. → Mitigation : le numéro « SB » reste stocké pour la recherche, seul l'affichage change.
- **Régression sur le suivi** : un changement mal coordonné pourrait affecter le suivi des colis. → Mitigation : tests automatiques dédiés à la résolution du numéro.

---

## 7. Livrables

- Un numéro unique et cohérent (`FSB…`) pour chaque commande, côté client **et** côté équipe.
- Un suivi qui fonctionne avec ce numéro, dans tous les cas (avec ou sans numéro transporteur, une ou plusieurs destinations).
- Un document récapitulatif des points modifiés et des vérifications effectuées.

---

## 8. Conclusion

La demande est simple à énoncer : **« le numéro de commande = le numéro de suivi »**. Sa réalisation demande de la rigueur car ce numéro circule dans tout le système, entretient un historique et s'appuie sur un moteur de suivi. Le temps nécessaire est largement consacré à la **vérification** (et non à l'écriture de nouvelles fonctions), afin de livrer un résultat fiable et durable.