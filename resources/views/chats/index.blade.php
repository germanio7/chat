@extends('dashboard')
@section('content')

  <div>
    @if (auth()->user()->role == 'student')
      <a href="{{route('chats.create')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">New Chat</a>
    @endif
  </div>

  @foreach ($chats as $item)
    <div class="py-8 px-8 max-w-sm mx-auto bg-white rounded-xl shadow-md space-y-2 sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
      <div class="text-center space-y-2 sm:text-left">
        <div class="space-y-0.5">
          @if ($item->not_read_messages->count() > 0)
          <p class="text-lg text-black font-semibold">
            unread messages:  {{$item->not_read_messages->count()}}
          </p>
          @endif
        </div>
        <a href="{{ route('chats.show', $item->id) }}" class="px-4 py-1 text-sm text-purple-600 font-semibold rounded-full border border-purple-200 hover:text-white hover:bg-purple-600 hover:border-transparent focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-offset-2">{{$item->name}}</a>
      </div>
    </div>
    <br>
  @endforeach
@endsection

<script src="{{ asset('js/app.js') }}"></script>