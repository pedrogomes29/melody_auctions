
<div class="modal fade" id="{{$POPUP_ID}}" tabindex="-1" aria-labelledby="{{$POPUP_TITLE_ID}}" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="{{$POPUP_TITLE_ID}}">{{$POPUP_TITLE}}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @yield('popup-body')
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        
        @yield('popup-footer')
      </div>
    </div>
  </div>
</div>