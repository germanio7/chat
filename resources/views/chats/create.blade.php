@extends('dashboard')
@section('content')

  @foreach ($users as $item)
    <form action="{{route('chats.store')}}" method="post" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="receiver_id" value="{{$item->id}}">
      <div class="py-8 px-8 max-w-sm mx-auto bg-white rounded-xl shadow-md space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
        <div class="text-center space-y-2 sm:text-left">
          <div class="space-y-0.5">
            <p class="text-lg text-black font-semibold">
              {{$item->email}}
            </p>
          </div>
          <button type="submit" class="px-4 py-1 text-sm text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">Crear Chat</button>
        </div>
      </div>
    </form>
  @endforeach
@endsection

<script src="{{ asset('js/app.js') }}"></script>