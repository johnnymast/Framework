<?php

namespace App\Framework\Bootstrap\Modules;

use App\Framework\Application;
use App\Framework\Bootstrap\Interfaces\ModuleInterface;
use App\Framework\Bootstrap\Kernel;
use Firehed\WebAuthn\ChallengeManagerInterface;
use Firehed\WebAuthn\MultiOriginRelyingParty;
use Firehed\WebAuthn\RelyingPartyInterface;
use Firehed\WebAuthn\SessionChallengeManager;

class PasskeyModule implements ModuleInterface
{
    /**
     * Run the module.
     *
     * @param \App\Framework\Application      $app    Reference to the Application instance.
     * @param \App\Framework\Bootstrap\Kernel $kernel Reference to the Kernel instance.
     *
     * @return void
     */
    public static function run(Application $app, Kernel $kernel): void
    {
        $settings = config('auth.passkeys');
        $app->bind(RelyingPartyInterface ::class, new MultiOriginRelyingParty([$settings['host']], $settings['domain']));
        $app->bind(ChallengeManagerInterface::class, new SessionChallengeManager);
    }
}