<?php
// bootstrap/app.php — Laravel 12

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Render fait transiter les requêtes via un proxy interne. Sans ceci,
        // Laravel ne détecte pas que la connexion d'origine est en HTTPS et
        // génère des liens/formulaires en http://, d'où l'avertissement
        // "formulaire non sécurisé" du navigateur alors que le site est bien
        // servi en HTTPS.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'role'       => \App\Http\Middleware\CheckRole::class,
            'two_factor' => \App\Http\Middleware\TwoFactorMiddleware::class,
        ]);

        // PayGateGlobal notifie le paiement via un POST externe (webhook) :
        // il n'a pas de token CSRF Laravel, donc on l'exclut, sinon la
        // confirmation de paiement échoue systématiquement (419).
        $middleware->validateCsrfTokens(except: [
            'paygate-global/webhook',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (\Illuminate\Database\QueryException $e, \Illuminate\Http\Request $request) {
            // En local, on laisse Laravel afficher l'erreur normalement
            if (app()->environment('local')) {
                return null;
            }

            \Illuminate\Support\Facades\Log::error('Erreur base de données : ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Une erreur est survenue. Veuillez réessayer.'], 500);
            }

            return back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('error', 'Une erreur est survenue lors de l\'enregistrement. Vérifiez les informations saisies et réessayez.');
        });

        $exceptions->render(function (\Throwable $e, \Illuminate\Http\Request $request) {
            if ($e instanceof \Illuminate\Http\Exceptions\HttpResponseException
                || $e instanceof \Illuminate\Validation\ValidationException
                || $e instanceof \Illuminate\Auth\Access\AuthorizationException
                || $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                return null;
            }

            // En local, laisser Laravel afficher l'erreur
            if (app()->environment('local')) {
                return null;
            }

            \Illuminate\Support\Facades\Log::error('Erreur inattendue : ' . $e->getMessage(), ['exception' => $e]);

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Une erreur inattendue est survenue.'], 500);
            }

            return back()->with('error', 'Une erreur inattendue est survenue. Veuillez réessayer ou contacter le support.');
        });

    })->create();
