<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use JrdnRc\FplChecker\Laravel\Infrastructure\Socialite\SocialiteManager;

/**
 * Class SocialiteServiceProvider
 *
 * @author jrdn rc <jordan@jcrocker.uk>
 */
final class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'Laravel\Socialite\Contracts\Factory',
            function ($app) {
                return new SocialiteManager($app);
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Laravel\Socialite\Contracts\Factory'];
    }
}