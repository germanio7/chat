@extends('dashboard')
@section('content')

@php
   $aux = array_filter($chat->users, function($item){
            return $item['id'] != auth()->user()->id;
        });

   $aux = array_values($aux);

   $receiver = \App\Models\User::findOrFail($aux[0]['id']);
@endphp

<div class="flex-1 p:2 sm:p-6 justify-between flex flex-col h-screen">
    <div class="flex sm:items-center justify-between py-3 border-b-2 border-gray-200">
       <div class="flex items-center space-x-4">
          <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="" class="w-10 sm:w-16 h-10 sm:h-16 rounded-full">
          <div class="flex flex-col leading-tight">
             <div class="text-2xl mt-1 flex items-center">
                <span class="text-gray-700 mr-3">{{ $receiver->name }}</span>
                <span class="text-green-500">
                   <svg width="10" height="10">
                      <circle cx="5" cy="5" r="5" fill="currentColor"></circle>
                   </svg>
                </span>
             </div>
             <span class="text-lg text-gray-600">{{ $chat->name }}</span>
          </div>
       </div>
    </div>
    <div id="messages" class="flex flex-col space-y-4 p-3 overflow-y-auto scrollbar-thumb-blue scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2 scrolling-touch">
       @foreach ($chat->messages as $item)
         @if ($item->sender_id != auth()->user()->id)
            <div class="chat-message">
               <div class="flex items-end">
                  <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
                     <div><span class="px-4 py-2 rounded-lg inline-block rounded-bl-none bg-gray-300 text-gray-600">{{ $item->content }}</span></div>
                  </div>
                  <img src="https://images.unsplash.com/photo-1549078642-b2ba4bda0cdb?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-1">
               </div>
            </div> 
         @else
            <div class="chat-message">
               <div class="flex items-end justify-end">
                  <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-1 items-end">
                     <div><span class="px-4 py-2 rounded-lg inline-block rounded-br-none bg-blue-600 text-white ">{{ $item->content }}</span></div>
                  </div>
                  <img src="https://images.unsplash.com/photo-1590031905470-a1a1feacbb0b?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=3&amp;w=144&amp;h=144" alt="My profile" class="w-6 h-6 rounded-full order-2">
               </div>
            </div>
         @endif
       @endforeach
    </div>
    <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 sm:mb-0">
       <div class="relative flex">
          <form class="w-full" id="formulario" method="POST" action="{{ route('messages.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="chat_id" value="{{$chat->id}}">
            <input name="content" type="text" placeholder="Write Something" class="w-full focus:outline-none focus:placeholder-gray-400 text-gray-600 placeholder-gray-600 pl-12 bg-gray-200 rounded-full py-3">
            <div class="absolute right-0 items-center inset-y-0 hidden sm:flex">
               <button type="submit" class="inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out text-white bg-blue-500 hover:bg-blue-400 focus:outline-none">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-6 w-6 transform rotate-90">
                     <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                  </svg>
               </button>
            </div>
         </form>
       </div>
    </div>
 </div>
 
 <style>
 .scrollbar-w-2::-webkit-scrollbar {
   width: 0.25rem;
   height: 0.25rem;
 }
 
 .scrollbar-track-blue-lighter::-webkit-scrollbar-track {
   --bg-opacity: 1;
   background-color: #f7fafc;
   background-color: rgba(247, 250, 252, var(--bg-opacity));
 }
 
 .scrollbar-thumb-blue::-webkit-scrollbar-thumb {
   --bg-opacity: 1;
   background-color: #edf2f7;
   background-color: rgba(237, 242, 247, var(--bg-opacity));
 }
 
 .scrollbar-thumb-rounded::-webkit-scrollbar-thumb {
   border-radius: 0.25rem;
 }
 </style>
 
 <script>
     const el = document.getElementById('messages')
     el.scrollTop = el.scrollHeight
 </script>

@endsection

<script src="{{ asset('js/app.js') }}"></script>

<script>

  let chat = @json($chat);

  Echo.private(`chat.${chat.id}`)
    .listen('NewMessage', (e) => {
        document.getElementById("messages").append(e.message.content);
    });
</script>


