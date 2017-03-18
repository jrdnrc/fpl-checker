<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Infrastructure\Socialite;

use Illuminate\Contracts\Routing\UrlGenerator;


/**
 * Class SocialliteManager
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
final class SocialiteManager extends \Laravel\Socialite\SocialiteManager
{
    /**
     * Build an OAuth 2 provider instance.
     *
     * @param  string $provider
     * @param  array  $config
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        $redirect = $this->createUrlFromString($config['redirect']);

        return new $provider(
            $this->app['request'],
            $config['client_id'],
            $config['client_secret'],
            $redirect
        );
    }

    /**
     * @param string $str
     * @return string
     * @throws \InvalidArgumentException
     */
    private function createUrlFromString(string $str): string
    {
        /** @var UrlGenerator $urlGenerator */
        $urlGenerator = $this->app->make(UrlGenerator::class);

        if ($urlGenerator->isValidUrl($str)) {
            return $str;
        }

        return $urlGenerator->route($str);
    }
}