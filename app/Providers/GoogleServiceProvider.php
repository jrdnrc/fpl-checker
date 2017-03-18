<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Providers;

use Google_Client;
use Illuminate\Support\ServiceProvider;
use Google_Service_Gmail as Gmail;
use Google_Service_Plus as GPlus;
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
            $client = new Google_Client;
            $client->setAuthConfig(json_decode(config('services.native_google.oauth'), true));
            $client->setScopes(
                [
                    Gmail::GMAIL_READONLY,
                    GPlus::USERINFO_EMAIL,
                    GPlus::USERINFO_PROFILE,
                    GPlus::PLUS_ME,
                ]
            );

            $client->setRedirectUri(route('post_google_register'));
            $client->setAccessType('offline');
            $client->setApprovalPrompt('force');

            return $client;
        });

        $this->app->bind(Provider::class, function () {
            return $this
                ->app
                ->make(Factory::class)
                ->driver('google')
                ->scopes(
                    [
                        Gmail::GMAIL_READONLY,
                        GPlus::USERINFO_EMAIL,
                        GPlus::USERINFO_PROFILE,
                        GPlus::PLUS_ME,
                    ]
                );
        });
    }
}