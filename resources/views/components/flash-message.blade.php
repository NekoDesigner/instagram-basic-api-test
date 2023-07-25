@if ($message = Session::get('success'))
<div class="bg-green-500 p-4 rounded-lg shadow-lg text-white my-8">
  <div class="flex items-center">
    <svg class="w-6 h-6 mr-2 fill-current" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
      <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1.707-6.293l3-3a1 1 0 011.414 1.414l-4 4a1 1 0 01-1.414 0l-2-2a1 1 0 111.414-1.414l1.293 1.293z"/>
    </svg>
    <span>{{$message}}</span>
  </div>
</div>
@endif

@if ($message = Session::get('danger'))
<div class="bg-red-500 p-4 rounded-lg shadow-lg text-white my-8">
  <div class="flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6 mr-2">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
    </svg>
    <span>{{$message}}</span>
  </div>
</div>
@endif
