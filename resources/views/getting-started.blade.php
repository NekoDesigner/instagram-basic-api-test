@extends('base')

@section('content')

<h1 class="text-2xl font-bold mb-4">Instagram Basic API - Getting started</h1>
<hr />

<p class="my-4">
  This is a simple Instagram Basic API application. You can use this application for getting your Instagram feed. <br />
  For getting started, you need to follow these steps:
  <ol class="list-decimal list-inside p-4">
    <li class="mb-2">Create a new instagram application from <a class="text-blue-500 underline" href="https://developers.facebook.com/apps" target="_blank">https://developers.facebook.com/apps</a></li>
    <li class="mb-2">Add a test user</li>
  </ol>
</p>

<p class="my-4">
  After these steps, you need to configure <code class="text-red-800 font-mono py-0.5 px-1">INSTAGRAM_APP_ID</code> and <code class="text-red-800 font-mono py-0.5 px-1">INSTAGRAM_APP_SECRET</code> keys in your 
  <code class="text-red-800 font-mono py-0.5 px-1">.env</code> file. <br />
  Then, you need to get user authorization and an access token. <br />
  <ol class="list-decimal list-inside p-4">
    <li class="mb-4">
      Call this url (with GET method) for getting user authorization with correct data: <code class="text-red-800 font-mono py-0.5 px-1">https://api.instagram.com/oauth/authorize?client_id={your-client-id}&redirect_uri={your-redirect-uri}&scope=user_profile,user_media&response_type=code</code>
    </li>
    <li class="mb-4">
      Get your access token by calling with POST method : <code class="text-red-800 font-mono py-0.5 px-1">https://api.instagram.com/oauth/access_token</code> with these body: <br /><br />
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <tbody class="bg-white divide-y divide-gray-200">
            <tr>
              <th class="px-6 py-4 whitespace-nowrap bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">client_id</th>
              <td class="px-6 py-4 whitespace-nowrap">Your instagram app id</td>
            </tr>
            <tr>
              <th class="px-6 py-4 whitespace-nowrap bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">app_secret</th>
              <td class="px-6 py-4 whitespace-nowrap">Your instagram secret key</td>
            </tr>
            <tr>
              <th class="px-6 py-4 whitespace-nowrap bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">grant_type</th>
              <td class="px-6 py-4 whitespace-nowrap">authorization_code</td>
            </tr>
            <tr>
              <th class="px-6 py-4 whitespace-nowrap bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">redirect_uri</th>
              <td class="px-6 py-4 whitespace-nowrap">A valid redirect URI</td>
            </tr>
            <tr>
              <th class="px-6 py-4 whitespace-nowrap bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">code</th>
              <td class="px-6 py-4 whitespace-nowrap">The code get from previous step (without the #_)</td>
            </tr>
          </tbody>
        </table>
      </div>
    </li>
    <li class="mb-4">
      Now, getting a long life access token with the GET method withj : <code class="text-red-800 font-mono py-0.5 px-1">https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret={your-client-secret}&access_token={your-access-token}</code>
    </li>
    <li class="mb-4">
      Finally, set the <code class="text-red-800 font-mono py-0.5 px-1">.env</code> variables <code class="text-red-800 font-mono py-0.5 px-1">INSTAGRAM_ACCESS_TOKEN</code> and <code class="text-red-800 font-mono py-0.5 px-1">INSTAGRAM_EXPIRES_IN</code> with the values get from previous step.
    </li>
  </ol>
</p>

<p class="text-center pb-8"><span class="text-xl">🎉</span> Great ! You can now use the Instagram Basic API service provider ! Enjoy !</p>

@endsection