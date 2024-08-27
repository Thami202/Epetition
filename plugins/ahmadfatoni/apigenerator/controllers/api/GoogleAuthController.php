<?php namespace AhmadFatoni\ApiGenerator\Controllers\api;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Google_Client;


class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $client = new Google_Client();
        $client->setClientId('75432176991-js6qvkeb4nlkagcg8j02iaf0gpvdte6u.apps.googleusercontent.com');
        $client->setClientSecret('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');
        $client->setRedirectUri('http://localhost:8081/epetitionoctober/signin');
        $client->addScope("email");
        $client->addScope("openid");
        $client->setAccessType("offline");
        $client->setApprovalPrompt("force");
        $authUrl = $client->createAuthUrl();
       // dd($authUrl); 
        return redirect()->away($authUrl);
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        //Development
        $client->setClientId('75432176991-js6qvkeb4nlkagcg8j02iaf0gpvdte6u.apps.googleusercontent.com');
        $client->setClientSecret('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');
        //Production
        //$client->setClientId('15775916265-uc6degsmhdfac13s8b7ufnu1tdqittfq.apps.googleusercontent.com');
        //$client->setClientSecret('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');
        $client->setRedirectUri('http://localhost:8081/epetitionoctober/signin');
        $client->addScope("email");
        $client->addScope("profile");
        $client->setAccessType("offline");
        $client->setApprovalPrompt("force");
        $accessToken = $client->fetchAccessTokenWithAuthCode($request->code);
        //dd($accessToken);
        $client->setAccessToken($accessToken);
    
        $google_user = $client->verifyIdToken($accessToken['id_token']);
        \Log::info(json_encode($google_user));
        $user =  Auth::findByEmail($google_user['email']);// User::where('email', $google_user['email'])->first();
        
        if (!$user) {
            $user = User::register([
                'name' => $google_user['name'],
                'email' => $google_user['email'],
                'password' => bcrypt(str_random(16)),
                'password_confirmation' => bcrypt(str_random(16)),
                'surname' => '$google_user[]'
            ]);
        }
    
        Auth::login($user);
        if (auth()->check()) {
            return redirectTo('/my-petitions');

        }else{
            return redirectTo('/signin');
        }
          //http://localhost:8081/epetitionoctober/dashboard
    }
}    

