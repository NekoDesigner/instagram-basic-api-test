<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateLongLifeTokenRequest;
use App\Services\InstagramAPIService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(InstagramAPIService $instagram)
    {
        // Time settings before refreshing Instagram API calls
        $userData = $instagram->getUser();
        $userMedias = $instagram->getUserMedias();

        // Return formatted data to view
        return view('instagram', [
            'user' => $userData,
            'medias' => $userMedias['data']
        ]);
    }

    public function settings(InstagramAPIService $instagram)
    {
        $userData = $instagram->getUser();
        $longLifeAccessToken = $instagram->getRefreshTokenUrl($instagram->access_token);
        return view('settings', [ "user" => $userData, "accessToken" => $instagram->access_token, "longLifeAccessTokenUrl" => $longLifeAccessToken ]);
    }

    public function update(InstagramAPIService $instagram, UpdateLongLifeTokenRequest $request)
    {
        $instagram->setAccessToken($request->input('token'), $instagram->getLongLifeAccessTokenDuration());
        return redirect()->route('settings')->with('success', 'Token updated successfully');
    }

    public function gettingStarted(InstagramAPIService $instagram)
    {
        $userData = $instagram->getUser();
        return view('getting-started', [ "user" => $userData ]);
    }

    public function oauthRedirect(Request $request)
    {
        // TODO: implement the oauth redirect for production and pre-production
        return 'oauth-redirect';
    }
}
