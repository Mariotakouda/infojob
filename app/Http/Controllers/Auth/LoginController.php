<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(Request $request): View
    {
        $redirect = $this->safeRedirect($request->query('redirect'));

        // Mémorise la page d'origine (ex : une offre d'emploi) pour pouvoir
        // y renvoyer l'utilisateur juste après sa connexion, y compris
        // lorsqu'une double authentification (2FA) s'intercale entre-temps.
        if ($redirect) {
            $request->session()->put('url.intended', $redirect);
        }

        return view('auth.login', ['redirect' => $redirect]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withInput($request->only('email'))->withErrors([
                'email' => "Trop de tentatives. Réessayez dans {$seconds} secondes.",
            ]);
        }

        // La cible de redirection peut arriver soit via le champ caché du
        // formulaire (POST), soit avoir déjà été mémorisée en session lors
        // de l'affichage du formulaire (GET ?redirect=...).
        $redirect = $this->safeRedirect($request->input('redirect'));
        if ($redirect) {
            $request->session()->put('url.intended', $redirect);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            $user = Auth::user();

            // ── 2FA uniquement pour admin et recruteur ───────────────────────
            if (in_array($user->role, ['admin', 'recruteur'])) {
                TwoFactorController::generateAndSendCode($user);
                // L'URL mémorisée en session ('url.intended') est conservée
                // et sera utilisée après validation du code 2FA.
                return redirect()->route('two-factor.show');
            }

            // ── Citoyen : retour vers la page d'origine si connue ─────────────
            return redirect()->intended(route('job-offers.index'));
        }

        RateLimiter::hit($throttleKey, 60);

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Ces identifiants ne correspondent à aucun compte.']);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Ne conserve que les chemins internes à l'application (protection
     * contre les redirections ouvertes / open redirect).
     */
    private function safeRedirect(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        // Doit commencer par un seul slash (chemin relatif) et ne pas être
        // une URL protocole-relative ("//host/...") ni absolue ("http://...").
        if (! Str::startsWith($url, '/') || Str::startsWith($url, '//') || Str::contains($url, '://')) {
            return null;
        }

        return $url;
    }
}
