<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\BrevoMailer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class TwoFactorController extends Controller
{
    public function show(): View
    {
        return view('auth.two-factor');
    }

    public function resend(Request $request): RedirectResponse
    {
        $sent = $this->generateAndSendCode($request->user());

        if (! $sent) {
            return back()->withErrors([
                'code' => "L'envoi de l'email a échoué. Réessayez dans un instant ou contactez le support si le problème persiste.",
            ]);
        }

        return back()->with('status', 'Un nouveau code vous a été envoyé par email.');
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ], [
            'code.required' => 'Veuillez saisir le code reçu par email.',
            'code.size'     => 'Le code doit contenir exactement 6 chiffres.',
        ]);

        $user = $request->user();

        if (! $user->two_factor_expires_at || now()->gt($user->two_factor_expires_at)) {
            return back()->withErrors(['code' => 'Ce code a expiré. Demandez-en un nouveau.']);
        }

        if (! Hash::check($request->code, $user->two_factor_code)) {
            return back()->withErrors(['code' => 'Code incorrect. Vérifiez votre email et réessayez.']);
        }

        $user->update([
            'two_factor_code'       => null,
            'two_factor_expires_at' => null,
        ]);

        $request->session()->put('two_factor_verified', true);

        // Si l'utilisateur venait d'une page précise (ex : une offre
        // d'emploi) avant de devoir se connecter, on l'y renvoie directement
        // plutôt que vers le tableau de bord générique.
        $default = match ($user->role) {
            'admin'     => route('admin.index'),
            'recruteur' => route('dashboard'),
            default     => route('job-offers.index'),
        };

        return redirect()->intended($default);
    }

    public static function generateAndSendCode(\App\Models\User $user): bool
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->update([
            'two_factor_code'       => bcrypt($code),
            'two_factor_expires_at' => now()->addMinutes(5),
        ]);

        $html = "<p>Bonjour {$user->name},</p>"
            . "<p>Voici votre code de connexion à usage unique :</p>"
            . "<h2 style=\"letter-spacing:4px;\">{$code}</h2>"
            . "<p>Ce code est valable <strong>5 minutes</strong>.</p>"
            . "<p>Si vous n'êtes pas à l'origine de cette demande, ignorez cet email et votre compte restera sécurisé.</p>"
            . "<p>L'équipe TravailTogo</p>";

        return BrevoMailer::send(
            $user->email,
            $user->name,
            'Votre code de vérification — TravailTogo',
            $html
        );
    }
}
