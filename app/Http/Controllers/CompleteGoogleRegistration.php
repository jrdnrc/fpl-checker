<?php declare(strict_types = 1);

namespace JrdnRc\FplChecker\Laravel\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Contracts\Provider;
use Symfony\Component\HttpFoundation\Response;

final class CompleteGoogleRegistration extends Controller
{
    /** @var Provider */
    private $provider;

    /**
     * CompleteGoogleRegistration constructor.
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
    public function __invoke(Request $request) : Response
    {
        if (! $request->query->has('code')) {
            abort(422, 'Please provide a callback code');
        }

        $user = $this->provider->user();
    }
}
