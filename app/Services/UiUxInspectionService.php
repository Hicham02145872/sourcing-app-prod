<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class UiUxInspectionService
{
    protected array $config;

    public function __construct(?array $config = null)
    {
        $this->config = $config ?? config('services.gemini');
    }

    public function analyze(string $imagePath, array $context = []): array
    {
        $apiKey = static::effectiveApiKey();
        if ($apiKey === null) {
            throw new RuntimeException('La clé GEMINI_API_KEY est manquante. Ajoutez-la dans votre .env ou dans le Dev Dashboard.');
        }

        if (! is_file($imagePath)) {
            throw new RuntimeException("Capture introuvable: {$imagePath}");
        }

        $model = $this->config['model'] ?? 'gemini-2.5-flash';
        $endpoint = rtrim(
            $this->config['endpoint'] ?? 'https://generativelanguage.googleapis.com/v1beta/models',
            '/'
        );

        $base64 = base64_encode((string) file_get_contents($imagePath));

        try {
            $response = Http::timeout(120)
                ->withHeaders(['x-goog-api-key' => $apiKey])
                ->post($endpoint.'/'.$model.':generateContent', [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $this->buildPrompt($context)],
                                [
                                    'inline_data' => [
                                        'mime_type' => 'image/png',
                                        'data' => $base64,
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'maxOutputTokens' => 8192,
                        'responseMimeType' => 'application/json',
                        'responseSchema' => $this->schema(),
                    ],
                ]);
        } catch (ConnectionException $e) {
            throw new RuntimeException('Impossible de joindre l\'API Gemini: '.$e->getMessage());
        }

        if ($response->status() >= 400) {
            throw new RuntimeException(
                'Erreur API Gemini ('.$response->status().'): '.$this->extractApiError($response->body())
            );
        }

        $body = $response->json();
        $text = $body['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if ($text === null) {
            $block = $body['promptFeedback']['blockReason'] ?? null;

            throw new RuntimeException('Réponse Gemini vide'.($block ? ' (bloquée: '.$block.')' : ''));
        }

        $text = trim((string) $text);
        $text = (string) preg_replace('/^```(?:json)?\s*/i', '', $text);
        $text = (string) preg_replace('/```\s*$/', '', $text);

        $decoded = json_decode($text, true);
        if (! is_array($decoded)) {
            throw new RuntimeException('Réponse Gemini non structurée en JSON.');
        }

        return $decoded;
    }

    protected function buildPrompt(array $context): string
    {
        $url = $context['url'] ?? '';
        $viewport = $context['viewport'] ?? 'desktop';
        $language = $context['language'] ?? 'fr';
        $capture = $context['capture'] ?? [];

        $page = $capture['page'] ?? [];
        $pageJson = $page ? json_encode($page, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '{}';
        $consoleJson = json_encode($capture['consoleMessages'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $failedJson = json_encode($capture['failedRequests'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
Tu es un inspecteur UI/UX senior avec 15 ans d'expérience sur des applications B2B SaaS à forte densité de données
(tableaux, formulaires, dashboards, workflows multi-étapes). Tu combines expertise en design d'interaction,
heuristiques de Nielsen, accessibilité WCAG 2.2 AA, psychologie de conversion et architecture de l'information.

## Page analysée
- URL: {$url}
- Viewport: {$viewport}

## Métadonnées récupérées par le crawler (à confronter avec ce que tu vois sur la capture)
{$pageJson}

## Erreurs console détectées
{$consoleJson}

## Requêtes réseau échouées
{$failedJson}

## Mission
Analyse la capture d'écran de la page avec l'œil d'un senior reviewer. Croise la capture avec les métadonnées
(hiérarchie des titres, champs de formulaire, liens, images sans alt, erreurs console, requêtes échouées...).

Évalue la page sur les critères suivants (chaque critère noté /100):
1. Première impression & hiérarchie visuelle (zone au-dessus de la ligne de flottaison)
2. Clarté & structure de l'information (navigation, groupement, labels)
3. Feedback d'interaction & états (hover, focus, chargement, erreurs, vides)
4. Formulaires & saisie (labels, aide, validation, taille des champs)
5. Densité d'information & lisibilité (typo, espacement, contraste)
6. Accessibilité (contraste, alt, focus visible, sémantique, aria)
7. Responsive & cohérence multi-viewports
8. Conversion & persuasion (CTA, preuve sociale, friction, confiance)

## Règles de notation
- Un score /100 par critère. Le overall_score est la moyenne.
- Sois exigeant et précis: cite ce que tu observes réellement sur la capture.
- Signale les vrais problèmes concrets avec des descriptions factuelles, pas des généralités.
- Les erreurs console et les requêtes réseau échouées sont des findings (severity major ou critical) si elles
  affectent l'expérience visible.

## Format de réponse
Réponds UNIQUEMENT avec un objet JSON valide respectant strictement le schéma fourni.
- severity ∈ {critical, major, minor, suggestion}
- priority ∈ {high, medium, low}
- Les champs texte doivent être rédigés en {$language}.

PROMPT;
    }

    protected function schema(): array
    {
        $object = fn (array $properties, array $required = []) => array_filter([
            'type' => 'OBJECT',
            'properties' => $properties,
            'required' => $required ?: null,
        ]);

        return $object([
            'overall_score' => ['type' => 'INTEGER'],
            'grade' => ['type' => 'STRING'],
            'summary' => ['type' => 'STRING'],
            'strengths' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
            'criteria' => [
                'type' => 'ARRAY',
                'items' => $object([
                    'name' => ['type' => 'STRING'],
                    'score' => ['type' => 'INTEGER'],
                    'max' => ['type' => 'INTEGER'],
                    'comment' => ['type' => 'STRING'],
                ]),
            ],
            'findings' => [
                'type' => 'ARRAY',
                'items' => $object([
                    'severity' => ['type' => 'STRING'],
                    'area' => ['type' => 'STRING'],
                    'title' => ['type' => 'STRING'],
                    'description' => ['type' => 'STRING'],
                    'heuristic' => ['type' => 'STRING'],
                    'recommendation' => ['type' => 'STRING'],
                ]),
            ],
            'accessibility_issues' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
            'conversion_opportunities' => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
            'recommendations' => [
                'type' => 'ARRAY',
                'items' => $object([
                    'priority' => ['type' => 'STRING'],
                    'action' => ['type' => 'STRING'],
                    'impact' => ['type' => 'STRING'],
                    'effort' => ['type' => 'STRING'],
                ]),
            ],
        ], ['overall_score', 'summary', 'findings']);
    }

    protected function extractApiError(string $body): string
    {
        $decoded = json_decode($body, true);

        return $decoded['error']['message'] ?? mb_substr($body, 0, 500);
    }

    public static function effectiveApiKey(): ?string
    {
        $key = config('services.gemini.api_key');
        if (blank($key)) {
            try {
                $key = \App\Models\Setting::get('gemini_api_key');
            } catch (\Throwable) {
                $key = null;
            }
        }

        return blank($key) ? null : (string) $key;
    }
}
