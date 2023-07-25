@extends('base')

@section('content')
<h1 class="text-2xl font-bold mb-4">Instagram Basic API - Paramètres</h1>
<hr />

<p class="my-4">Update your Instagram Basic API if needed.</p>
<p class="text-sm text-gray-500">For getting a new long life access token, please click <a href="{{$longLifeAccessTokenUrl}}" target="_blank" class="text-blue-400 underline">here</a>.</p>
<p class="mb-8 text-sm text-gray-500">If you don't have any access token, please, go to <a href="">README</a> page for follow steps.</p>

@include('components.flash-message')

<form action="{{route('update')}}" method="post">
  @csrf
  <div class="flex">
    <div class="flex align-middle justify-between bg-white w-1/2 rounded-xl focus:outline-none focus:ring focus:border-blue-500" >
      <input 
        class="w-full bg-transparent py-2 px-4"
        type="password" 
        name="token" 
        id="token" 
        value="{{ $accessToken ?? '' }}"
        placeholder="Instagram Access Token">
      <button id="toggle-access-token" type="button" class="border-l-2 border-x-gray-200 pl-3 pr-4 cursor-pointer">
        <i class="fa-regular fa-eye"></i>
      </button>
    </div>
    <input 
      class="bg-purple-500 px-4 py-2 rounded-xl text-white ml-2 hover:cursor-pointer hover:bg-purple-700" 
      type="submit" 
      value="Mettre à jour"
    >
  </div>
</form>

<hr class="my-8" />

<div class="bg-blue-100 p-4 rounded-xl border-double border-4 border-blue-400 inline-block max-w-md">
  <p class="text-blue-500 text-sm leading-6"><span class="text-xl">💡</span> You need to configure <code class="text-red-800 font-mono py-0.5 px-1">INSTAGRAM_APP_ID</code> and <code class="text-red-800 font-mono py-0.5 px-1">INSTAGRAM_APP_SECRET</code> keys in your 
    <code class="text-red-800 font-mono py-0.5 px-1">.env</code> file.</p>
</div>

@endsection

@section('scripts')
<script>
  document.getElementById('toggle-access-token').addEventListener('click', function() {
    const tokenInput = document.getElementById('token');
    const type = tokenInput.getAttribute('type') === 'password' ? 'text' : 'password';
    tokenInput.setAttribute('type', type);
    this.innerHTML = type === 'password' ? '<i class="fa-regular fa-eye"></i>' : '<i class="fa-regular fa-eye-slash"></i>';
  });
</script>
@endsection
