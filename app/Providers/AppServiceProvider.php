<?php declare(strict_types=1);

namespace JrdnRc\FplChecker\Laravel\Providers;

use Illuminate\Support\ServiceProvider;
use JrdnRc\FplChecker\Laravel\Infrastructure\Decoding\Base64StringDecoder;
use JrdnRc\FplChecker\Laravel\Infrastructure\Decoding\Decoder;
use JrdnRc\FplChecker\Laravel\Infrastructure\Decoding\DecoderPool;
use JrdnRc\FplChecker\Laravel\Infrastructure\Decoding\FplUrlDecoder;
use JrdnRc\FplChecker\Laravel\Infrastructure\Google\Gmail\GmailClient;
use Laravel\Dusk\DuskServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }

        $this->app->bind(Decoder::class, function () {
            return new DecoderPool(
                new Base64StringDecoder,
                new FplUrlDecoder
            );
        });
    }
}
