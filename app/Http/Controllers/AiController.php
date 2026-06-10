<?php

namespace App\Http\Controllers;
use App\Models\Offer;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use ZipArchive;

class AiController extends Controller
{
public function index()
{
    $user = Auth::user();

    $query = Offer::with('organization')
        ->withCount('applications')
        ->latest();

    if ($user->role === 'admin_organisme') {
        $query->where('organization_id', $user->organization_id);
    }

$offers = $query->get();

    return view('ai.index', compact('offers'));
}

public function analyze(Application $application)
{
    try {
        $application->load(['student', 'offer.organization']);
        $this->authorizeAiAccess($application);
        $this->processApplicationAnalysis($application);

        // Rediriger vers la page précédente (candidatures ou classement)
        $referer = request()->headers->get('referer', '');

        if (str_contains($referer, 'classement')) {
            return redirect()
                ->route('ia.ranking', $application->offer_id)
                ->with('success', 'Analyse IA effectuée avec succès.');
        }

        return back()->with('success', 'Analyse IA effectuée avec succès.');

    } catch (RuntimeException $e) {
        return back()->with('error', $e->getMessage());
    } catch (\Throwable $e) {
        return back()->with('error', 'Erreur pendant l\'analyse IA : ' . $e->getMessage());
    }
}
private function processApplicationAnalysis(Application $application): void
{
    if (!$application->cv_path || !Storage::disk('public')->exists($application->cv_path)) {
        throw new RuntimeException('CV introuvable pour cette candidature.');
    }

    $cvFile = Storage::disk('public')->path($application->cv_path);
    $cvText = $this->extractTextFromFile($cvFile);

    if (mb_strlen(trim($cvText)) < 30) {
        throw new RuntimeException('Le texte extrait du CV est vide ou insuffisant.');
    }

    // Très important : le classement doit être calculé localement,
    // pas avec le score Groq. Groq peut donner le même score à plusieurs CV.
    $local = $this->calculateCandidateScore($cvText, $application->offer);

    // Groq sert seulement à extraire quelques infos et produire un résumé texte.
    // Même si Groq renvoie 85 pour tout le monde, ce score est ignoré.
    $result = [];
    try {
        $result = $this->analyzeWithLlm($cvText, $application);
    } catch (\Throwable $e) {
        $result = [];
    }

    $matchRate = $local['rate'];
    $score = $matchRate;

    $keywordsFoundArray = $local['found'];
    $keywordsMissingArray = $local['missing'];

    $summary = $this->buildLocalSummary($application, $local);

    if (!empty($keywordsMissingArray)) {
        $recommendations = 'Compétences à vérifier ou à renforcer : ' . implode(', ', $keywordsMissingArray);
    } else {
        $recommendations = 'Profil globalement adapté aux compétences demandées.';
    }

    $application->update([
        'candidate_name' => $result['nom'] ?? $application->student->name,
        'candidate_email' => $result['email'] ?? $application->student->email,
        'candidate_experience' => $this->toText($result['experience'] ?? null),
        'candidate_diplomas' => $this->toText($result['diplomes'] ?? null),
        'candidate_skills' => $this->toText($result['competences'] ?? $keywordsFoundArray),

        'ai_score' => $score,
        'ai_match_rate' => $matchRate,
        'ai_keywords_found' => $this->toText($keywordsFoundArray),
        'ai_keywords_missing' => $this->toText($keywordsMissingArray),
        'ai_summary' => $summary,
        'ai_recommendation' => $this->recommendationFromRate($matchRate),
        'ai_recommendations' => $recommendations,
        'ai_analyzed_at' => now(),
    ]);
}
private function calculateCandidateScore(string $cvText, Offer $offer): array
{
    $skills = array_values(array_filter(array_map('trim', preg_split('/[,;\n]+/', $offer->required_skills ?? ''))));

    if (count($skills) === 0) {
        return [
            'rate' => 0,
            'skills_rate' => 0,
            'profile_rate' => null,
            'found' => [],
            'missing' => [],
            'total_skills' => 0,
            'matched_skills' => 0,
            'profile_required' => $offer->profile_required,
            'profile_detected' => null,
        ];
    }

    $cvNormalized = $this->normalizeText($cvText);

    $found = [];
    $missing = [];

    foreach ($skills as $skill) {
        $matched = false;

        foreach ($this->skillAliases($skill) as $alias) {
            if ($this->textContainsTerm($cvNormalized, $alias)) {
                $matched = true;
                break;
            }
        }

        if ($matched) {
            $found[] = $skill;
        } else {
            $missing[] = $skill;
        }
    }

    $skillsRate = round((count($found) / count($skills)) * 100);

    $requiredLevel = $this->studyLevelRank($offer->profile_required ?? '');
    $detectedLevel = $this->detectStudyLevel($cvText);
    $profileRate = null;

    if ($requiredLevel !== null) {
        if ($detectedLevel === null) {
            // Niveau non trouvé dans le CV : on ne bloque pas, mais on pénalise légèrement.
            $profileRate = 50;
        } elseif ($detectedLevel >= $requiredLevel) {
            $profileRate = 100;
        } else {
            $profileRate = 60;
        }

        // Pondération : compétences surtout, niveau demandé en complément.
        $finalRate = round(($skillsRate * 0.85) + ($profileRate * 0.15));
    } else {
        $finalRate = $skillsRate;
    }

    return [
        'rate' => (int) $finalRate,
        'skills_rate' => (int) $skillsRate,
        'profile_rate' => $profileRate,
        'found' => $found,
        'missing' => $missing,
        'total_skills' => count($skills),
        'matched_skills' => count($found),
        'profile_required' => $offer->profile_required,
        'profile_detected' => $detectedLevel,
    ];
}

private function normalizeText(string $text): string
{
    $text = mb_strtolower($text, 'UTF-8');

    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    if ($converted !== false) {
        $text = $converted;
    }

    $text = preg_replace('/[^a-z0-9+#.]+/', ' ', $text);
    $text = preg_replace('/\s+/', ' ', $text);

    return trim($text ?? '');
}

private function textContainsTerm(string $normalizedText, string $term): bool
{
    $term = $this->normalizeText($term);

    if ($term === '' || mb_strlen($term) < 2) {
        return false;
    }

    return preg_match('/(^|[^a-z0-9+#.])' . preg_quote($term, '/') . '($|[^a-z0-9+#.])/', $normalizedText) === 1;
}

private function skillAliases(string $skill): array
{
    $skillNormalized = $this->normalizeText($skill);

    $map = [
        'php' => ['php'],
        'laravel' => ['laravel', 'eloquent', 'blade', 'artisan'],
        'mysql' => ['mysql', 'sql', 'base de donnees', 'base donnees', 'database'],
        'sql' => ['sql', 'mysql', 'postgresql', 'postgres', 'oracle', 'base de donnees', 'database'],
        'html/css' => ['html', 'css', 'bootstrap', 'frontend', 'front end'],
        'html css' => ['html', 'css', 'bootstrap', 'frontend', 'front end'],
        'html' => ['html'],
        'css' => ['css'],
        'javascript' => ['javascript', 'java script', 'js', 'jquery', 'react', 'vue', 'angular'],
        'java script' => ['javascript', 'js'],
        'git' => ['git', 'github', 'gitlab'],
        // API REST doit être détectée seulement si le CV parle clairement d'API/REST/web service.
        'api rest' => ['api rest', 'rest api', 'api restful', 'web service', 'webservice'],
        'api' => ['api rest', 'rest api', 'api restful', 'web service', 'webservice'],
        'json' => ['json'],

        // Analyse de documents : éviter les mots trop larges comme UML/conception/documentation.
        // Sinon un CV avec seulement "documentation technique" devient trop bien classé.
        'analyse de documents' => ['analyse de documents', 'analyse document', 'analyse documentaire', 'traitement de documents', 'extraction de texte', 'extraction texte', 'pdf parser', 'docx', 'ocr'],
        'analyse documents' => ['analyse de documents', 'analyse document', 'analyse documentaire', 'traitement de documents', 'extraction de texte', 'extraction texte', 'pdf parser', 'docx', 'ocr'],

        // Documentation courte : ici on accepte documentation/rapport/rédaction.
        'documentation courte' => ['documentation', 'rapport', 'redaction', 'fiche technique', 'manuel utilisateur', 'documentation technique'],
        'documentation' => ['documentation', 'rapport', 'redaction', 'fiche technique', 'documentation technique'],
    ];

    $aliases = [$skill, $skillNormalized];

    foreach ($map as $key => $values) {
        if ($skillNormalized === $key || str_contains($skillNormalized, $key)) {
            $aliases = array_merge($aliases, $values);
        }
    }

    // Pour une compétence composée non connue, on garde aussi les parties utiles.
    // Exemple : "React JS" peut matcher "React" ou "JS".
    foreach (preg_split('/[\/\-\s]+/', $skillNormalized) as $part) {
        if (mb_strlen($part) >= 3) {
            $aliases[] = $part;
        }
    }

    return array_values(array_unique(array_filter($aliases)));
}

private function studyLevelRank(?string $text): ?int
{
    if (!$text) {
        return null;
    }

    $text = $this->normalizeText($text);

    // 1) Si le CV contient une ligne claire du type "Niveau : Bac +3",
    // on prend cette valeur avant les anciens diplômes comme DUT/DEUG.
    if (preg_match('/niveau\s*:?\s*bac\s*\+?\s*([2-5])/', $text, $m)) {
        return (int) $m[1];
    }

    $levels = [];

    // 2) Récupérer tous les Bac +X présents dans le CV et garder le plus haut.
    if (preg_match_all('/bac\s*\+?\s*([2-5])/', $text, $matches)) {
        foreach ($matches[1] as $level) {
            $levels[] = (int) $level;
        }
    }

    // 3) Déduction par diplôme. On garde toujours le niveau maximum trouvé.
    if (preg_match('/master\s*2|m2|ingenieur/', $text)) {
        $levels[] = 5;
    }

    if (preg_match('/master\s*1|m1/', $text)) {
        $levels[] = 4;
    }

    if (preg_match('/licence|bachelor|genie logiciel|developpement informatique|developpement web/', $text)) {
        $levels[] = 3;
    }

    if (preg_match('/bts|dut|deug|deust|technicien specialise/', $text)) {
        $levels[] = 2;
    }

    return !empty($levels) ? max($levels) : null;
}

private function detectStudyLevel(string $cvText): ?int
{
    return $this->studyLevelRank($cvText);
}

private function buildLocalSummary(Application $application, array $local): string
{
    $name = $application->student->name;
    $matched = $local['matched_skills'];
    $total = $local['total_skills'];
    $rate = $local['rate'];

    $summary = "Le CV de {$name} correspond à {$matched}/{$total} compétence(s) demandée(s), soit une correspondance estimée à {$rate}%.";

    if (!empty($local['profile_required'])) {
        if ($local['profile_detected'] !== null) {
            $summary .= " Niveau détecté : Bac +{$local['profile_detected']}. Profil demandé : {$local['profile_required']}.";
        } else {
            $summary .= " Le niveau demandé ({$local['profile_required']}) n'a pas été clairement détecté dans le CV.";
        }
    }

    return $summary;
}

    private function authorizeAiAccess(Application $application): void
    {
        $user = Auth::user();

        if ($user->role === 'super_admin') {
            return;
        }

        if ($user->role === 'admin_organisme' && $application->offer->organization_id === $user->organization_id) {
            return;
        }

        abort(403, 'Accès non autorisé.');
    }

    private function extractTextFromFile(string $path): string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'pdf' => $this->extractPdfText($path),
            'docx' => $this->extractDocxText($path),
            default => throw new RuntimeException('Format non supporté. Utilisez un fichier PDF ou DOCX.'),
        };
    }

    private function extractPdfText(string $path): string
    {
        if (!class_exists(\Smalot\PdfParser\Parser::class)) {
            throw new RuntimeException('Installez le package : composer require smalot/pdfparser');
        }

        $parser = new \Smalot\PdfParser\Parser();

        return $parser->parseFile($path)->getText();
    }

    private function extractDocxText(string $path): string
    {
        $zip = new ZipArchive();

        if ($zip->open($path) !== true) {
            throw new RuntimeException('Impossible de lire le fichier DOCX.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (!$xml) {
            throw new RuntimeException('Le contenu du DOCX est introuvable.');
        }

        $xml = preg_replace('/<w:tab\/>/i', ' ', $xml);
        $xml = preg_replace('/<w:br\/>/i', "\n", $xml);
        $text = strip_tags($xml);

        return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
    
  private function analyzeWithLlm(string $cvText, Application $application): array
{
    $offer = $application->offer;
    $cvText = trim(preg_replace('/\s+/', ' ', $cvText));

    $prompt = "Tu es un assistant RH spécialisé dans l'analyse de CV.
Réponds UNIQUEMENT avec un objet JSON valide, sans markdown et sans explication.

Important : ne calcule pas le score et ne donne pas de classement. Laravel calcule déjà le score localement.

OFFRE DE STAGE :
Titre : {$offer->title}
Profil demandé : " . ($offer->profile_required ?: 'Non précisé') . "
Compétences requises : " . ($offer->required_skills ?: 'Non précisées') . "
Description : " . ($offer->description ?: '') . "

CV DU CANDIDAT :
" . Str::limit($cvText, 6000) . "

Le JSON doit contenir exactement ces clés :
nom, email, competences, diplomes, experience, resume.

Règles :
- competences, diplomes et experience doivent être des tableaux.
- resume doit être court, factuel et basé uniquement sur le CV.";

    $response = Http::timeout(30)
        ->withHeaders([
            'Authorization' => 'Bearer ' . config('services.groq.key'),
            'Content-Type'  => 'application/json',
        ])
        ->post('https://api.groq.com/openai/v1/chat/completions', [
            'model'    => config('services.groq.model'),
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => 'Tu es un assistant RH. Tu réponds uniquement avec un objet JSON valide.'
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt
                ],
            ],
            'temperature'     => 0.1,
            'max_tokens'      => 900,
            'response_format' => ['type' => 'json_object'],
        ]);

    if (!$response->successful()) {
        throw new RuntimeException('Erreur Groq : ' . $response->body());
    }

    $content = $response->json('choices.0.message.content');

    if (!$content) {
        throw new RuntimeException('Réponse vide depuis Groq.');
    }

    $decoded = json_decode($content, true);

    if (!is_array($decoded)) {
        throw new RuntimeException('JSON invalide reçu : ' . $content);
    }

    return $decoded;
}

    private function extractOutputText(array $payload): string
    {
        if (!empty($payload['output_text'])) {
            return $payload['output_text'];
        }

        $texts = [];

        foreach (($payload['output'] ?? []) as $item) {
            foreach (($item['content'] ?? []) as $content) {
                if (!empty($content['text'])) {
                    $texts[] = $content['text'];
                }
            }
        }

        return trim(implode("\n", $texts));
    }

private function extractJson(string $content): string
{
    $content = trim($content);

    $content = str_replace('```json', '', $content);
    $content = str_replace('```', '', $content);

    $start = strpos($content, '{');
    $end = strrpos($content, '}');

    if ($start === false || $end === false || $end <= $start) {
        return '{}';
    }

    return substr($content, $start, $end - $start + 1);
}

    private function percent($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        $value = (int) round((float) $value);

        return max(0, min(100, $value));
    }

 private function toText($value): ?string
{
    if ($value === null) {
        return null;
    }

    if (is_array($value)) {
        // Gérer les tableaux imbriqués
        $flat = array_map(function($item) {
            if (is_array($item)) {
                return implode(' ', array_filter(array_map('strval', $item)));
            }
            return strval($item);
        }, $value);

        return implode(', ', array_filter($flat)) ?: null;
    }

    if (is_bool($value)) {
        return $value ? 'Oui' : 'Non';
    }

    return (string) $value;
}
public function ranking(Offer $offer)
{
    $user = Auth::user();
    if ($user->role === 'admin_organisme' && $offer->organization_id !== $user->organization_id) {
        abort(403);
    }

    $applications = Application::with(['student', 'offer.organization'])
        ->where('offer_id', $offer->id)
        ->orderByRaw('CASE WHEN ai_match_rate IS NULL THEN 1 ELSE 0 END')
        ->orderByDesc('ai_match_rate')
        ->get();

    // Attribuer le rang uniquement aux candidats analysés
    $rank = 1;
    foreach ($applications as $app) {
        $app->ranking_position = ($app->ai_match_rate !== null) ? $rank++ : null;
    }

    return view('ai.ranking', compact('offer', 'applications'));
}

public function analyzeAll(Offer $offer)
{
    set_time_limit(0);
ini_set('max_execution_time', '0');

    $user = Auth::user();

    if ($user->role === 'admin_organisme' && $offer->organization_id !== $user->organization_id) {
        abort(403);
    }

    $applications = Application::with(['student', 'offer.organization'])
        ->where('offer_id', $offer->id)
        ->whereNotNull('cv_path')
        ->get();

    $success = 0;
    $errors = 0;

    foreach ($applications as $application) {
        try {
            $this->processApplicationAnalysis($application);
            $success++;
        } catch (\Throwable $e) {
            $errors++;
        }
    }

    if ($success === 0) {
        return back()->with('error', 'Aucune candidature analysée.');
    }

    return back()->with('success', $success . ' candidature(s) analysée(s). Erreurs : ' . $errors);
}
private function recommendationFromRate(?int $rate): ?string
{
    if ($rate === null) {
        return null;
    }

    if ($rate >= 80) {
        return 'Excellent';
    }

    if ($rate >= 60) {
        return 'Bon';
    }

    if ($rate >= 40) {
        return 'Moyen';
    }

    return 'Faible';
}
}