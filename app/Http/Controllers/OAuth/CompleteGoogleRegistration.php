<?php declare(strict_types = 1);

namespace JrdnRc\FplChecker\Laravel\Http\Controllers\OAuth;

use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use JrdnRc\FplChecker\Laravel\Http\Controllers\Controller;
use JrdnRc\FplChecker\Laravel\User;
use Laravel\Socialite\Contracts\Provider;
use Symfony\Component\HttpFoundation\Response;

final class CompleteGoogleRegistration extends Controller
{
    /** @var Provider */
    private $provider;
    /** @var Guard|SessionGuard */
    private $auth;

    /**
     * CompleteGoogleRegistration constructor.
     *
     * @param Provider $provider
     * @param Guard    $auth
     */
    public function __construct(Provider $provider, Guard $auth)
    {
        $this->provider = $provider;
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request) : Response
    {
        if (! $request->query->has('code')) {
            abort(422, 'Please provide a callback code');
        }

        $this->authenticateUser();

        return response('ok');
    }

    /**
     * @return void
     */
    private function authenticateUser()
    {
        $user = $this->provider->user();

        if (null === $user = User::where('google_id', $user->getId())->first()) {
            $user = User::fromGoogleUser($this->provider->user());
        }

        $this->auth->login($user, true);
    }
}
