<div id = "notificationsContainer">
    <div id = "notBtn" href = "#">
        <!--Number supports double digets and automaticly hides itself when there is nothing between divs -->
        <div id="notificationsBellContainer">
            <div id = "numberOfNotifications">{{$numUnreadNotifications?:''}}</div>
            <i id="notificationBell" class="fa fa-bell fa-4x" aria-hidden="true"></i>
        </div>
        <div id = "notifications">
            <div class = "display">
                @if(count($notifications)==0)
                    <div class = "cont nothing"> 
                        <div class = "cent">Looks Like you're all caught up!</div>
                    </div>
                @else
                    <div class = "cont">
                        @each('partials.notification',$notifications,'notification')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>