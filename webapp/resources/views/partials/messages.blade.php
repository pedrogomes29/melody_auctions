<section id="chatbox">
    <div class="container py-5">
  
      <div class="row d-flex justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-6">
  
          <div class="card shadow-none" id="chat2">
            <div class="card-header d-flex justify-content-between align-items-center p-3">
              <h5 class="mb-0">Chat</h5>
            </div>
            <div id="chatboxContent" class="card-body" data-mdb-perfect-scrollbar="true">
                @each('partials.message', $messages, 'message')
            </div>
            @if(Auth::check())
                <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                @if (Auth::user()->photo)
                    <img src="{{ asset(Auth::user()->photo) }}" class="{{Auth::user()->username}} rounded-circle me-3 msg-pfp" alt="Sender Profile picture">
                @else
                    <img src="{{ asset('default_images/default.jpg') }}" class="{{Auth::user()->username}} rounded-circle me-3 msg-pfp" alt="Sender Profile picture">
                @endif
                <input type="text" class="form-control form-control-lg" id="messageInput"
                    placeholder="Type message">
                <a class="ms-3"><i id="send-msg-button" class="fa fa-paper-plane" aria-hidden="true"></i></a>
                </div>
            @endif
          </div>
  
        </div>
      </div>
  
    </div>
  </section>