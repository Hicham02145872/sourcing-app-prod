## Context

La page d'édition des quotations (`admin/quotations/{id}/edit`) inclut le partial `quality-options.blade.php` qui gère l'affichage des images de qualité (low, medium, good). Ce partial utilise Alpine.js pour dispatch un événement `open-viewer` lors du clic sur une image. Cependant, la page édition n'a pas de modal listener pour cet événement, contrairement à la page création qui en dispose.

Le composant `<x-photo-viewer>` existe et fonctionne pour l'image produit principale (`real_product_image`), mais il n'est pas utilisé pour les images de qualité dans le partial.

## Goals / Non-Goals

**Goals:**
- Rendre les photos de qualité cliquables pour zoom in sur la page d'édition
- Réutiliser le même pattern modal que la page création (cohérence UX)
- Aucune modification du partial partagé — fix uniquement dans `edit.blade.php`

**Non-Goals:**
- Modifier le partial `quality-options.blade.php`
- Ajouter de nouvelles dépendances
- Changer le comportement sur la page création ou client

## Decisions

**Décision: Copier le bloc modal viewer de `create.blade.php` vers `edit.blade.php`**

Alternatives considérées:
1. **Utiliser `<x-photo-viewer>`** — Rejeté car le partial dispatch déjà `open-viewer` via Alpine events, pas via un composant Blade. Changer le partial impacterait aussi la page création.
2. **Ajouter un listener dans le partial lui-même** — Rejeté car le partial est partagé entre create/edit, et le modal doit être au niveau de la page parente (pour le z-index et le contexte Alpine).
3. **Copier le bloc modal de create** — Sélectionné car c'est exactement le même pattern, minimal change, aucun risque de régression.

Le bloc modal à ajouter (lignes 286-303 de `create.blade.php`):
```html
<div x-data="{ viewerOpen: false, viewerSrc: '' }"
     @open-viewer.window="viewerSrc = $event.detail.src; viewerOpen = true"
     @keydown.window.escape="viewerOpen = false">
    <template x-teleport="body">
        <div x-show="viewerOpen" x-cloak class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 p-4"
             @click="viewerOpen = false">
            <div class="relative max-w-[90vw] max-h-[90vh]" @click.stop>
                <button type="button" @click="viewerOpen = false" class="absolute -top-3 -right-3 z-10 w-8 h-8 rounded-full bg-white/90 hover:bg-white shadow-lg flex items-center justify-center text-slate-700 hover:text-slate-900 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <img :src="viewerSrc" class="max-w-full max-h-[90vh] rounded-lg shadow-2xl object-contain">
            </div>
        </div>
    </template>
</div>
```

**Emplacement:** Avant `@push('scripts')` dans `edit.blade.php` (ligne ~562).

## Risks / Trade-offs

- **Risque zéro** — Le bloc modal est déjà testé et fonctionnel sur la page création. Aucune dépendance nouvelle, aucun changement de comportement existant.
