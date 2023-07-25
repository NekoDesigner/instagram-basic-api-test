<?php

namespace App\Services;

use App\Models\Token;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class InstagramAPIService
{
    private $app_id;
    private $app_secret;
    private $instagramAppToken;
    public $access_token;

    /** Default user id is 'me' */
    private $user_id = 'me';
    /** The fields that been returned by the instagram basic api */
    private $fields = 'id';
    private $longLifeAccessTokenDuration = 5183944;

    public function __construct(
      private readonly string $api_auth_url = 'https://api.instagram.com',
      private readonly string $api_base_url = 'https://graph.instagram.com'
    ) {
      // Set app id and secret from config file.
      $this->app_id = config('services.instagram_basic.client_id');
      $this->app_secret = config('services.instagram_basic.client_secret');

      // Get the access token from the database. If not exists, use the one from the config file.
      $this->instagramAppToken = Token::where('app', 'INSTAGRAM')->first();
      $this->access_token = !empty($this->instagramAppToken) ? $this->instagramAppToken->token : config('services.instagram_basic.access_token');
    }

    /**
     * Set new access token
     * @param string $accessToken The new access token (long life access token)
     * @param string $expires_in The new access token expiration date
     */
    public function setAccessToken(string $accessToken, string $expires_in): void 
    {
      // Create or update the token from database
      if (empty($this->instagramAppToken)) {
        $this->instagramAppToken = Token::create([
          'app' => 'INSTAGRAM',
          'token' => $accessToken,
          'expires_in' => $expires_in
        ]);
      } else {
        $this->instagramAppToken->update([
          'token' => $accessToken,
          'expires_in' => $expires_in
        ]);
      }
      // Set the new access token
      $this->access_token = $accessToken;
    }

    /**
     * Set fields that been returned by the instagram basic api
     * Beware : some fields are only available for business account. For medias and user specific fields, check the documentation.
     * @see https://developers.facebook.com/docs/instagram-basic-display-api
     * @param array $fields An array of string
     */
    private function setFields(array $fields): void {
      $this->fields = join(",", $fields);
    }

    /**
     * Get oauth authorization url
     * Use this url to get the code and then get the access token
     * @return string The authorization url (string)
     */
    public function getAuthUrl(): string
    {
      $url = $this->api_auth_url . '/oauth/authorize?client_id=' . $this->app_id . '&redirect_uri=' . route('oauth-redirect') . '&scope=user_profile,user_media&response_type=code';
      return $url;
    }

    /**
     * Get refresh token url
     * Use this url to get the new long life access token
     * @param string $token The access token
     */
    public function getRefreshTokenUrl(string $token): string
    {
      $url = $this->api_base_url . '/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $token;
      return $url;
    }

    /**
     * Get long life access token url
     * Use this url to get the long life access token
     * @return string The long life access token url (string)
     * @throws \Exception If the api return an error``
     */
    public function getLongLifeAccessTokenUrl(): string
    {
      $url = $this->api_auth_url . '/oauth/access_token?grant_type=ig_exchange_token&client_secret=' . $this->app_secret . '&access_token=' . $this->access_token;
      return $url;
    }

    /**
     * Get access token from code
     * The code can be get from the authorization url (see getAuthUrl method)
     * @param string $code The code from the authorization url
     */
    public function getAccessToken(string $code): mixed
    {
      $url = $this->api_auth_url . '/oauth/access_token';
      $response = Http::asForm()->post($url, [
        'client_id' => $this->app_id,
        'client_secret' => $this->app_secret,
        'grant_type' => 'authorization_code',
        'redirect_uri' => route('oauth-redirect'),
        'code' => $code
      ]);
      return $response->json();
    }

    /**
     * Refresh access token
     * Use this method to refresh the access token
     * @return mixed The response from the api (json)
     * @throws \Exception If the api return an error
     */
    public function refreshAccessToken(): mixed
    {
      $url = $this->api_auth_url . '/oauth/refresh_access_token';
      $response = Http::asForm()->post($url, [
        'grant_type' => 'ig_refresh_token',
        'access_token' => $this->access_token
      ]);
      $apiData = $response->json();
      if (!empty($apiData['access_token'])) {
        $this->setAccessToken($apiData['access_token'], $apiData['expires_in']);
        return $apiData;
      }
      throw new \Exception('Error refreshing access token ' . $response->body());
    }

    /**
     * Get user medias
     * List of available fields: https://developers.facebook.com/docs/instagram-basic-display-api/reference/media#fields
     * Return cache data if exists
     * @return mixed The response from the api (json)
     */
    public function getUserMedias(): mixed {
      if (Cache::has('INSTAGRAM_MEDIAS')) {
        return json_decode(Cache::get('INSTAGRAM_MEDIAS'), true);
      }
      $timeBeforeRefreshCache = Carbon::now()->addMinutes(15);
      $this->setFields(['id', 'username', 'media_type', 'media', 'media_url', 'caption', 'timestamps', 'thumbnail_url', 'children']);
      $url = $this->api_base_url . '/' . $this->user_id . '/media?fields=' . $this->fields . '&access_token=' . $this->access_token;
      $response = Http::get($url);
      $userMedias = $response->json();

      foreach ($userMedias['data'] as $key => $media) {
        if (array_key_exists('children', $media)) {
            $children = $this->getMediaChildren($media['id']);
            $userMedias['data'][$key]['children'] = $children;
        }
      }

      Cache::put('INSTAGRAM_MEDIAS', json_encode($userMedias), $timeBeforeRefreshCache);
      return $userMedias;
    }

    /**
     * Get user
     * List of available fields: https://developers.facebook.com/docs/instagram-basic-display-api/reference/user#fields
     * return cache data if exists
     * @return mixed The response from the api (json)
     */
    public function getUser(): mixed {
      if (Cache::has('INSTAGRAM_USER')) {
        return json_decode(Cache::get('INSTAGRAM_USER'), true);
      }
      $timeBeforeRefreshCache = Carbon::now()->addMinutes(15);
      $this->setFields(['id', 'username', 'media_count', 'account_type']);
      $url = $this->api_base_url . '/' . $this->user_id . '?fields=' . $this->fields . '&access_token=' . $this->access_token;
      $response = Http::get($url);
      $userData = $response->json();
      if (empty($userData)) {
        throw new \Exception('Error getting user data ' . $response->body());
      }
      Cache::put('INSTAGRAM_USER', json_encode($userData), $timeBeforeRefreshCache);
      return $userData;
    }

    /**
     * Get media details
     * List of available fields: https://developers.facebook.com/docs/instagram-basic-display-api/reference/media#fields
     * @param string $mediaId The media id
     * @return mixed The response from the api (json)
     */
    public function getMediaDetails(string $mediaId): mixed {
      $this->setFields(['id', 'username', 'thumbnail_url', 'media_type', 'media_url', 'permalink', 'caption', 'timestamp', 'children']);
      $url = $this->api_base_url . '/' . $mediaId . '?fields=' . $this->fields . '&access_token=' . $this->access_token;
      $response = Http::get($url);
      return $response->json();
    }

    /**
     * Get media children
     * List of available fields: https://developers.facebook.com/docs/instagram-basic-display-api/reference/media#fields
     * @param string $mediaId The media id
     * @return mixed The response from the api (json)
     */
    public function getMediaChildren(string $mediaId) {
      $this->setFields(['id', 'username', 'thumbnail_url', 'media_type', 'media_url', 'permalink', 'timestamp']);
      $url = $this->api_base_url . '/' . $mediaId . '/children?fields=' . $this->fields . '&access_token=' . $this->access_token;
      $response = Http::get($url);
      return $response->json();
    }

    /**
     * Get long life access token duration
     * @return int The long life access token duration (in seconds)
     */
    public function getLongLifeAccessTokenDuration(): int {
      return $this->longLifeAccessTokenDuration;
    }
}

?>