<?php

namespace App\Http\Controllers;

use App\Models\Factures;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function query(Request $request)
    {
        $data = $request->validate([
            'message' => 'required|string|min:2',
        ]);

        $message = trim($data['message']);
        $identifier = $this->extractInvoiceIdentifier($message);
        $intent = $this->inferIntent($message);

        // If no identifier, treat as a general assistant question and answer via LLM
        if ($identifier === null) {
            $fallback = $this->buildGeneralFallback($message);
            $prompt = $this->buildGeneralPrompt($message);
            $responseText = $this->callLlamaWithPrompt($prompt, $fallback);

            return response()->json([
                'response' => $responseText,
            ]);
        }

        $invoice = null;
        if ($identifier !== null) {
            // Try exact NumeroFacture match first
            $invoice = Factures::where('NumeroFacture', $identifier)->first();

            // If numeric, try by primary key idFacture
            if (!$invoice && ctype_digit($identifier)) {
                $invoice = Factures::where('idFacture', (int) $identifier)->first();
            }

            // Fallback: fuzzy NumeroFacture search
            if (!$invoice) {
                $invoice = Factures::where('NumeroFacture', 'like', "%{$identifier}%")->first();
            }
        }

        if (!$invoice) {
            $plainFallback = $this->buildNotFoundFallback($identifier, $message);
            $responseText = $this->callLlama(
                $message,
                [
                    'found' => false,
                    'identifier' => $identifier,
                ],
                $plainFallback
            );

            return response()->json([
                'response' => $responseText,
            ]);
        }

        // Prepare structured context
        $context = [
            'found' => true,
            'invoice' => [
                'idFacture'     => $invoice->idFacture ?? null,
                'NumeroFacture' => $invoice->NumeroFacture ?? null,
                'Statut'        => $invoice->Statut ?? null,
                'DateEcheance'  => $invoice->DateEcheance ?? null,
                'DateEntree'    => $invoice->DateEntree ?? null,
                'DateRemise'    => $invoice->DateRemise ?? null,
                'DateImpaye'    => $invoice->DateImpaye ?? null,
                'MontantTotal'  => $invoice->MontantTotal ?? null,
                'ModeReglement' => $invoice->ModeReglement ?? null,
                'Service'       => $invoice->Service ?? null,
            ],
            'intent' => $intent,
        ];

        // Fallback message if LLM not reachable
        $fallback = $this->buildTemplateAnswer($message, $context);

        $responseText = $this->callLlama($message, $context, $fallback);

        return response()->json([
            'response' => $responseText,
        ]);
    }

    private function extractInvoiceIdentifier(string $message): ?string
    {
        // 1) Patterns with #
        if (preg_match('/#\s*([A-Za-z0-9\-]+)/u', $message, $m)) {
            return $m[1];
        }

        // 2) After keywords like invoice/facture/inv/fact
        if (preg_match('/\b(?:invoice|facture|fact|inv|n°|num(?:[ée]ro)?(?:\s+de)?\s+facture)\b\s*#?\s*([A-Za-z0-9\-]+)/iu', $message, $m)) {
            return $m[1];
        }

        // 3) Last resort: a token that looks like F123/INV-123/123
        if (preg_match('/\b([A-Z]{1,4}-?\d{2,6}|\d{2,6})\b/u', $message, $m)) {
            return $m[1];
        }

        return null;
    }

    private function inferIntent(string $message): string
    {
        $m = mb_strtolower($message, 'UTF-8');

        $isStatus  = str_contains($m, 'status') || str_contains($m, 'statut') || str_contains($m, 'état') || str_contains($m, 'etat');
        $isDue     = str_contains($m, 'due') || str_contains($m, 'échéance') || str_contains($m, 'echeance') || str_contains($m, 'deadline');
        $isAmount  = str_contains($m, 'amount') || str_contains($m, 'montant') || str_contains($m, 'total');
        $isPayment = str_contains($m, 'payment') || str_contains($m, 'paiement') || str_contains($m, 'réglé') || str_contains($m, 'regle') || str_contains($m, 'impay');

        if ($isStatus && $isDue) return 'status_due';
        if ($isStatus) return 'status';
        if ($isDue) return 'due_date';
        if ($isAmount) return 'amount';
        if ($isPayment) return 'payment_status';
        return 'general';
    }

    private function buildTemplateAnswer(string $message, array $context): string
    {
        if (!($context['found'] ?? false)) {
            $id = $context['identifier'] ?? '';
            return $id ? "Aucune facture trouvée pour l’identifiant {$id}." : "Aucune facture correspondante trouvée.";
        }

        $inv = $context['invoice'];
        $num  = $inv['NumeroFacture'] ?? ($inv['idFacture'] ?? '');
        $stat = $inv['Statut'] ?? 'inconnu';
        $due  = $inv['DateEcheance'] ?? null;
        $amt  = $inv['MontantTotal'] ?? null;

        $dueStr = $due ? Carbon::parse($due)->format('Y-m-d') : 'date non disponible';

        switch ($context['intent']) {
            case 'status_due':
            case 'status':
                return "La facture {$num} est {$stat}" . ($due ? " et échéance le {$dueStr}." : '.');
            case 'due_date':
                return $due ? "La facture {$num} est due le {$dueStr}." : "La facture {$num} n’a pas de date d’échéance disponible.";
            case 'amount':
                return $amt !== null ? "Le montant de la facture {$num} est de {$amt} DH." : "Le montant de la facture {$num} n’est pas disponible.";
            case 'payment_status':
                return "Statut de paiement de la facture {$num}: {$stat}.";
            default:
                $parts = [];
                $parts[] = "statut: {$stat}";
                if ($due) $parts[] = "échéance: {$dueStr}";
                if ($amt !== null) $parts[] = "montant: {$amt} DH";
                $summary = implode(', ', $parts);
                return "Facture {$num}: {$summary}.";
        }
    }

    private function buildNotFoundFallback(?string $identifier, string $message): string
    {
        $lang = $this->detectLanguage($message);
        if ($lang === 'fr') {
            return $identifier
                ? "Je n’ai pas trouvé de facture pour l’identifiant {$identifier}. Vous pouvez demander: ‘Statut facture #F123’, ‘Échéance facture 456’, ou ‘Montant facture #INV-789’."
                : "Je n’ai pas trouvé cette facture. Essayez avec un numéro, par ex.: ‘Statut facture #F123’.";
        }
        return $identifier
            ? "I couldn’t find an invoice for identifier {$identifier}. You can ask: ‘Status invoice #F123’, ‘Due date invoice 456’, or ‘Amount invoice #INV-789’."
            : "I couldn’t find that invoice. Try with a number, e.g.: ‘Status invoice #F123’.";
    }

    private function detectLanguage(string $text): string
    {
        $t = mb_strtolower($text, 'UTF-8');
        if (preg_match('/[éèêàâîôûùç]/u', $t)) return 'fr';
        if (str_contains($t, 'bonjour') || str_contains($t, 'facture') || str_contains($t, 'statut') || str_contains($t, 'échéance') || str_contains($t, 'echeance')) return 'fr';
        return 'en';
    }

    private function buildGeneralPrompt(string $userMessage): string
    {
        return <<<PROMPT
You are a friendly assistant for an invoice dashboard. If the user greets you or asks for help, respond briefly (one short sentence) in the user's language, explaining what you can do: check invoice status, due date, amount, and payment status when provided an invoice number (e.g., #F123). Invite them to ask a question.

User message: {$userMessage}
PROMPT;
    }

    private function buildGeneralFallback(string $userMessage): string
    {
        $lang = $this->detectLanguage($userMessage);
        if ($lang === 'fr') {
            return "Bonjour ! Je peux vous aider à connaître le statut, la date d’échéance, le montant ou le paiement d’une facture (ex. ‘Statut facture #F123’). Que puis-je faire pour vous ?";
        }
        return "Hi! I can help you with invoice status, due date, amount, or payment (e.g., ‘Status invoice #F123’). How can I help?";
    }

    private function callLlama(string $userMessage, array $context, string $fallback): string
    {
        $prompt = $this->buildPrompt($userMessage, $context);
        return $this->callLlamaWithPrompt($prompt, $fallback);
    }

    private function callLlamaWithPrompt(string $prompt, string $fallback): string
    {
        try {
            // Try Ollama local endpoint first
            $resp = Http::timeout(8)->post('http://localhost:11434/api/generate', [
                'model'  => 'llama3.2',
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if ($resp->ok()) {
                $json = $resp->json();
                $text = $json['response'] ?? null;
                if (is_string($text) && trim($text) !== '') {
                    return trim($text);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('LLM call failed (Ollama): ' . $e->getMessage());
        }

        // Fallback to template answer
        return $fallback;
    }

    private function buildPrompt(string $userMessage, array $context): string
    {
        $ctx = json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return <<<PROMPT
You are a helpful assistant for an invoice dashboard. Answer concisely in one short sentence, in the language of the user.
- Only use the provided data; do not hallucinate.
- Prefer format like: "Invoice #<number> is <status> and due on <date>." or "Le montant de la facture <num> est de <montant> DH."

User question: {$userMessage}
Data: {$ctx}
PROMPT;
    }
} 