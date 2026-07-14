<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Envoi d'emails transactionnels via l'API HTTP de Brevo.
 *
 * Pourquoi pas Mail::/Notification: (SMTP) ?
 * Render bloque les ports sortants SMTP (25, 465, 587) sur le plan gratuit.
 * L'API Brevo passe en HTTPS (port 443), donc elle fonctionne sur Render free.
 *
 * Nécessite BREVO_API_KEY dans les variables d'environnement (clé API,
 * différente du mot de passe SMTP — à générer dans Brevo > SMTP & API > API Keys).
 * L'adresse définie dans MAIL_FROM_ADDRESS doit être vérifiée dans Brevo
 * (Senders, Domains & Dedicated IPs > Senders > Single Sender Verification).
 */
class BrevoMailer
{
    public static function send(string $toEmail, string $toName, string $subject, string $htmlContent): bool
    {
        $apiKey = config('services.brevo.api_key');

        if (! $apiKey) {
            Log::error('BrevoMailer: BREVO_API_KEY manquant, email non envoyé.', ['to' => $toEmail]);
            return false;
        }

        try {
            $response = Http::withHeaders([
                'api-key'      => $apiKey,
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
            ])->timeout(10)->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name'  => config('mail.from.name'),
                    'email' => config('mail.from.address'),
                ],
                'to' => [
                    ['email' => $toEmail, 'name' => $toName],
                ],
                'subject'     => $subject,
                'htmlContent' => $htmlContent,
            ]);

            if ($response->failed()) {
                Log::error('BrevoMailer: échec envoi email.', [
                    'to'     => $toEmail,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            Log::error('BrevoMailer: exception lors de l\'envoi.', [
                'to'      => $toEmail,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
