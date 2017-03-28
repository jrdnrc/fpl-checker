<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Providers;

use Google_Client;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\ServiceProvider;
use Google_Service_Gmail as Gmail;
use Google_Service_Plus as GPlus;
use JrdnRc\FplChecker\Laravel\User;
use Laravel\Socialite\Contracts\Factory;
use Laravel\Socialite\Contracts\Provider;

/**
 * Class GoogleServiceProvider
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
final class GoogleServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register() : void
    {
        $this->app->bind(Google_Client::class, function () {
            $config = $this->app['config']->get('services.google');
            $client = new Google_Client;
            $client->setApplicationName('FPL Checker');
            $client->setClientId($config['client_id']);
            $client->setClientSecret($config['client_secret']);
            $client->setRedirectUri(route(env('GOOGLE_REDIRECT_ROUTE')));

            $client->setScopes(
                [
                    Gmail::GMAIL_READONLY,
                    GPlus::USERINFO_EMAIL,
                    GPlus::USERINFO_PROFILE,
                    GPlus::PLUS_ME,
                ]
            );

            $client->setAccessType('offline');
            $client->setApprovalPrompt('force');

            /**
             * Need to create a new client for each additional user
             */
            if ($this->isUserAuthenticated()) {
                /** @var User $user */
                $user = $this->app->make(Guard::class)->user();
                $client->setAccessToken($user->token());
            }

            return $client;
        });

        $this->app->bind(Provider::class, function () {
            return $this
                ->app
                ->make(Factory::class)
                ->driver('google')
                ->with(['access_type' => 'offline', 'prompt' => 'consent select_account'])
                ->stateless()
                ->scopes(
                    [
                        Gmail::GMAIL_READONLY,
                        GPlus::USERINFO_EMAIL,
                        GPlus::USERINFO_PROFILE,
                    ]
                );
        });
    }

    /**
     * @return bool
     */
    private function isUserAuthenticated() : bool
    {
        return $this->app->make(Guard::class)->check();
    }
}