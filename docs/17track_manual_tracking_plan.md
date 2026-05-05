# Plan de Vérification Manuelle du Suivi (17TRACK)

Ce document décrit l'approche pour vérifier le statut d'un colis "à la demande" (Polling), en complément ou en alternative à la méthode Webhook.

## Pourquoi une vérification manuelle ?

Bien que 17TRACK recommande l'utilisation des Webhooks (Push) pour recevoir les mises à jour en temps réel, il est parfois nécessaire de forcer une vérification (Pull) :
- **Synchronisation immédiate** : Un administrateur veut vérifier le statut le plus récent sans attendre le prochain cycle de push.
- **Débogage** : Vérifier si le webhook a manqué une mise à jour.
- **Action Manuelle** : Permettre à l'administrateur de forcer la récupération des statuts via l'API pour synchroniser les données immédiatement, sans attendre la notification du Webhook.

## API Endpoint : GetTrackInfo

Pour récupérer les informations manuellement, nous utilisons l'endpoint `gettrackinfo` de l'API v2.

### Détails Techniques

- **URL de base** : `https://api.17track.net/track/v2.2/gettrackinfo` (Version à confirmer dans le dashboard 17TRACK, souvent v2.2 ou v2.4).
- **Méthode** : `POST`
- **Headers** :
  - `17token`: `VOTRE_CLÉ_API`
  - `Content-Type`: `application/json`

### Payload de la requête

Vous pouvez récupérer les infos pour un ou plusieurs numéros (Max 40). **Note :** Les numéros doivent généralement être **déjà enregistrés** via `/register` pour que cela fonctionne de manière optimale, bien que l'API puisse parfois traiter des numéros ad-hoc. L'approche recommandée est de toujours s'assurer que le numéro est enregistré avant de demander des infos.

```json
[
    {
        "number": "RR123456789CN",
        "carrier": 3011 // Optionnel si auto-détecté, mais recommandé
    }
]
```

### Réponse attendue (JSON)

```json
{
    "code": 0,
    "data": {
        "accepted": [
            {
                "number": "RR123456789CN",
                "track": {
                    "e": 0, // État du suivi (0: pas trouvé, 1: en transit, etc.)
                    "z0": { // Dernier événement
                        "a": "2023-10-25 14:00", // Date
                        "c": "Paris", // Lieu
                        "z": "Livré" // Description
                    }
                }
            }
        ],
        "errors": []
    }
}
```

## Stratégie d'Implémentation dans Sourcing App

### 1. Bouton "Rafraîchir le Statut" (Admin)

Ajouter un bouton sur la vue de détail de la commande (`admin/orders/show`).

**Flux :**
1. Admin clique sur "Rafraîchir".
2. Backend (Controller/Service) appelle `SeventeenTrackService::getTrackingInfo($trackingNumber)`.
3. Le Service appelle l'API `gettrackinfo`.
4. Si réponse positive, mise à jour de la table `sourcing_orders` (statut + json détails).
5. Retour d'un message de succès à l'admin et rechargement de la page.

### 2. Gestion des Cas "Non Trouvé"

Si le numéro n'est pas trouvé (`e: 0`), il est possible qu'il n'ait pas encore été scanné par le transporteur ou qu'il ne soit pas enregistré.
- **Action** : Tenter un appel `/register` si le numéro semble valide mais inconnu de 17TRACK, puis réessayer `gettrackinfo` après un court délai.

### 3. Solution de Repli (Fallback)


**Note importante** : Ce lien ouvre une **page web (HTML)** pour un affichage visuel. Il ne retourne PAS de JSON. Pour obtenir une réponse JSON utilisable par l'application, il faut IMPÉRATIVEMENT utiliser l'endpoint API `gettrackinfo` décrit plus haut.

## Avantages et Inconvénients

| Méthode | Avantages | Inconvénients |
| :--- | :--- | :--- |
| **Webhook (Push)** | Automatique, pas d'action requise, économe en ressources serveur | Délai possible, nécessite une config serveur publique |
| **Manuel (Pull)** | Résultat immédiat, contrôle total quand vérifier | Consomme des quotas API à chaque clic, nécessite une action utilisateur |
| **Lien Externe** | Gratuit, 100% fiable | L'utilisateur quitte l'application, pas de mise à jour en BDD |

**Recommandation** : Utiliser le **Webhook** comme mécanisme principal et le **bouton manuel (API)** comme fonctionnalité d'appoint pour les admins.
