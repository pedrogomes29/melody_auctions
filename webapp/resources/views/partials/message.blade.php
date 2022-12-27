
@if(Auth::id()==$message->sender->id)
<div class="d-flex flex-row justify-content-end mb-4">
    <div>
        <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">{{$message->text}}</p>
        <p class="small me-3 mb-1 rounded-3 text-muted d-flex justify-content-end">{{$message->sender->username}}, {{$message->timeSince}}</p>
        <p hidden class = "messageDate">{{$message->date}}</p>
    </div>
    @if ($message->sender->photo)
        <img src="{{ asset($message->sender->photo) }}" alt="Sender Profile picture" class="{{$message->sender->username}} rounded-circle msg-pfp">
    @else
        <img src="{{ asset('default_images/default.jpg') }}" alt="Sender Profile picture" class="{{$message->sender->username}} rounded-circle msg-pfp">
    @endif
</div>
@else
<div class="d-flex flex-row justify-content-start mb-4">
    @if ($message->sender->photo)
        <img src="{{ asset($message->sender->photo) }}" alt="Sender Profile picture" class="{{$message->sender->username}} rounded-circle msg-pfp">
    @else
        <img src="{{ asset('default_images/default.jpg') }}" alt="Sender Profile picture" class="{{$message->sender->username}} rounded-circle msg-pfp">
    @endif
    <div>
        <p class="small p-2 ms-3 mb-1 rounded-3 another-person-text">{{$message->text}}</p>
        <p class="small ms-3 mb-1 rounded-3 text-muted">{{$message->sender->username}}, {{$message->timeSince}}</p>
        <p hidden class = "messageDate">{{$message->date}}</p>
    </div>
</div>
@endif



