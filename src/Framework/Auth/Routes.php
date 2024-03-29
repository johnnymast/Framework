<?php

use App\Framework\Auth\Http\Controllers\LoginController;
use App\Framework\Auth\Http\Controllers\LoginPasskeyController;
use App\Framework\Auth\Http\Controllers\OAuthController;
use App\Framework\Auth\Http\Controllers\RegisterPasskeyController;
use App\Framework\Auth\Http\Controllers\RegisterController;
use App\Framework\Auth\Http\Controllers\ResetPasswordController;
use App\Framework\Auth\Http\Controllers\VerificationController;
use App\Framework\Auth\Http\Middleware\AuthRequiredMiddleware;
use App\Framework\Csrf\Http\Middleware\CSRFMiddleware;
use Slim\Routing\RouteCollectorProxy;

app()->group('', function (RouteCollectorProxy $group) {
    $group->post('/login', callable: [LoginController::class, 'login'])->setName('auth.login');

    $group->get('/register', callable: [RegisterController::class, 'showRegistrationForm'])->setName('auth.register');

    $group->post('/register', callable: [RegisterController::class, 'register'])->setName('auth.register');

    $group->get('/login', callable: [LoginController::class, 'showLoginForm'])->setName('auth.login');

    $group->get('/verify_email', callable: [VerificationController::class, 'showActivationPage'])->setName(
        'auth.verification'
    );

    $group->get('/verify_email/{token}', callable: [VerificationController::class, 'confirmToken'])->setName(
        'auth.verification'
    );

    $group->get('/password/reset', callable: [ResetPasswordController::class, 'showLinkRequestForm'])->setName(
        'auth.password.request'
    );

    $group->post('/password/reset', callable: [ResetPasswordController::class, 'request'])->setName(
        'auth.password.request'
    );

    $group->get('/password/reset/{password_token}', callable: [ResetPasswordController::class, 'showResetForm']
    )->setName(
        'auth.password.request'
    );

    $group->post('/password/reset/{password_token}', callable: [ResetPasswordController::class, 'reset'])->setName(
        'auth.password.request'
    );

    $group->get('/password/sent', callable: [ResetPasswordController::class, 'showLinkSentPage'])->setName(
        'auth.password.mailsent'
    );
})->add(CSRFMiddleware::class);

app()->group('', function (RouteCollectorProxy $group) {
    $group->get('/passkey/register', callable: [RegisterPasskeyController::class, 'precentLinkRequestData'])->setName(
        'auth.passkey.request'
    );

    $group->post('/passkey/verify', callable: [RegisterPasskeyController::class, 'verifyLinkRequestData'])->setName(
        'auth.passkey.verify'
    );


})->add(AuthRequiredMiddleware::class);


app()->post('/passkey/find', callable: [LoginPasskeyController::class, 'findLinkData'])->setName(
    'auth.passkey.find'
);

app()->post('/passkey/login', callable: [LoginPasskeyController::class, 'login'])->setName(
    'auth.passkey.login'
);


app()->get('/auth/oauth/{service}/login', [OAuthController::class, "login"])->setName(
    'auth.oauth.login'
);

app()->get('/auth/oauth/{service}/register', [OAuthController::class, "register"])->setName(
    'auth.oauth.register'
);

app()->get('/auth/oauth/{service}/redirect', [OAuthController::class, "redirect"])->setName(
    'auth.oauth.redirect'
);


//->add(CSRFMiddleware::class)

app()->post('/logout', callable: [LoginController::class, 'logout'])->setName('auth.logout');
