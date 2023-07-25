@extends('base')

@section('content')

<h1 class="text-2xl font-bold mb-4">Instagram basic API</h1>
<hr />
<p class="my-4">
  This is my personnal Instagram account. You can customize the library by folliwings steps inside the <b>README.md</b> file.<br />
  Documentation de l'API Instagram utilisé pour cette démo: <a class="text-blue-500 hover:underline" href="https://developers.facebook.com/docs/instagram-basic-display-api" target="_blank">https://developers.facebook.com/docs/instagram-basic-display-api</a>
</p>
<div class="p-4 bg-white rounded-lg">
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
      @foreach ($medias as $media)
        <div class="max-w-xs rounded overflow-hidden border border-gray-200 bg-white">
          @if ($media['media_type'] == 'VIDEO')
            <video class="w-full h-48 object-cover" controls>
              <source src="{{ $media['media_url'] }}" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          @elseif ($media['media_type'] == 'IMAGE')
            <img class="w-full h-48 object-cover transform hover:scale-110 transition-transform duration-300" src="{{ $media['media_url'] }}" alt="Card Image">
            @else
            <div class="relative">
              <div class="absolute top-1 right-1 px-2 py-1 bg-fuchsia-500 text-white text-xs z-10 rounded-md">Carousel</div>
              <div class="swiper">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                  <!-- Slides -->
                  @foreach ($media['children']['data'] as $child)
                    @if ($child['media_type'] == 'IMAGE')
                      <div class="swiper-slide">
                        <img class="w-full h-48 object-cover" src="{{ $child['media_url'] }}" alt="Card Image">
                      </div>
                    @endif
                    @if ($child['media_type'] == 'VIDEO')
                      <div class="swiper-slide">
                        <video class="w-full h-48 object-cover" controls>
                          <source src="{{ $child['media_url'] }}" type="video/mp4">
                          Your browser does not support the video tag.
                        </video>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
            </div>
          @endif
          <div class="px-6 pt-4 pb-2">
            <div class="text-slate-600 text-lg mb-2">
              @if ($media['media_type'] == 'VIDEO')
                <i class="fa-solid fa-film mr-2"></i>
              @elseif ($media['media_type'] == 'CAROUSEL_ALBUM')
                <i class="fa-solid fa-images mr-2"></i>
              @else
                <i class="fa-solid fa-image mr-2"></i>
              @endif
              {{ $media['caption'] ?? $media['media_type'] }}
            </div>
          </div>
          @if ($media['media_type'] == 'CAROUSEL_ALBUM')
            <div class="px-6 py-2">
              <p class="text-slate-400">Swipez pour voir les images du carousel</p>
              <div class="text-right mt-4">
                <div class="bg-blue-500 text-white inline-block rounded-md px-2 py-1">{{count($media['children']['data'])}} médias</div>
              </div>
            </div>
          @endif
        </div>
      @endforeach
  </div>
</div>

@endsection
