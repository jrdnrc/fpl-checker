<?php declare(strict_types = 1);

namespace JrdnRc\FplChecker\Laravel\Http\Controllers\OAuth;

use JrdnRc\FplChecker\Laravel\Http\Controllers\Controller;
use Laravel\Socialite\Contracts\Provider;
use Symfony\Component\HttpFoundation\Response;

final class RegisterViaGoogle extends Controller
{
    /** @var Provider */
    private $provider;

    /**
     * RegisterViaGoogle constructor.
     *
     * @param Provider $provider
     */
    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return Response
     */
    public function __invoke() : Response
    {
        return $this->provider->redirect();
    }
}
