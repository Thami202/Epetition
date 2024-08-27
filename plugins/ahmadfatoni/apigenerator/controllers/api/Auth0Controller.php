<?php namespace AhmadFatoni\ApiGenerator\Controllers\api;
use Auth0\SDK\Auth0;
use GuzzleHttp\Psr7\Request;
use Nyholm\Psr7\Factory\Psr17Factory;
use Illuminate\Routing\Controller;

class Auth0Controller extends Controller
{
    public function login()
    {
        $auth0 = new Auth0([
            'domain' => config('app.auth0_domain'),
            'client_id' => config('app.auth0_client_id'),
            'client_secret' => config('app.auth0_client_secret'),
            'redirect_uri' => config('app.auth0_redirect_uri'),
            'logout_redirect_uri' => config('app.auth0_logout_redirect_uri'),
        ]);

        return $auth0->login();
    }

    public function callback(Request $request)
    {
        $auth0 = new Auth0([
            'domain' => config('app.auth0_domain'),
            'client_id' => config('app.auth0_client_id'),
            'client_secret' => config('app.auth0_client_secret'),
            'redirect_uri' => config('app.auth0_redirect_uri'),
            'logout_redirect_uri' => config('app.auth0_logout_redirect_uri'),
        ]);

        $userInfo = $auth0->getUser();
        // Store or process user information as needed
        // e.g. create/update user in your database, set authenticated user, etc.

        // Redirect to desired route after successful authentication
        return redirect()->intended('/');
    }

    public function logout()
    {
        $auth0 = new Auth0([
            'domain' => config('app.auth0_domain'),
            'client_id' => config('app.auth0_client_id'),
            'client_secret' => config('app.auth0_client_secret'),
            'redirect_uri' => config('app.auth0_redirect_uri'),
            'logout_redirect_uri' => config('app.auth0_logout_redirect_uri'),
        ]);

        // Perform any additional logout logic as needed

        // Redirect to Auth0 logout URL
        return redirect($auth0->getLogoutUrl());
    }
}
