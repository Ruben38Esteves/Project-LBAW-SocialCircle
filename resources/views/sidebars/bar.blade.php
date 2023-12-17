@section('sidebar')
    <?php 
    $notifications = Auth::user()->notifications;
    $friends = Auth::user()->friends;
    $groups = Auth::user()->groups;
    ?>
    <aside class = 'sidebar'>
        <ul class = 'list-unstyled sidebar-itens-positioning'>
            <li class="sidebar-top">
                <h1 id="page-title"><a href="{{ url('/home') }}">SocialCircle</a></h1>
            </li>
            <li>
                <button onclick="location.href='{{url('/home')}}'">Home</button>
            </li>
            <li>
                <button onclick="location.href='{{url('/home')}}'">Messages</button>
            </li>
            <li>
                <button onclick='getGroupList("{{Auth::user()->username}}")'>Groups</button>
            </li>
            <li>
                <button onclick='getFriendList("{{Auth::user()->username}}")'>Friends</button>
            </li>
            <li>
                <button onclick='getNotificationList("{{Auth::user()->username}}")'>Notifications</button>
            </li>
        </ul>
        <div id='notifications' style="display: none">
            <h1 id="page-title"><a href="{{ url('/home') }}">SocialCircle</a></h1>
            @each('partials.notification', $notifications, 'notification')
            <button onclick='hideNotifs()'>Go Back</button>
        </div>
        <div id='friends' style="display: none">
            <h1 id="page-title"><a href="{{ url('/home') }}">SocialCircle</a></h1>
            @each('partials.userheader',$friends, 'user')
            <button onclick='hideFriends()'>Go Back</button>
        </div>
    </aside>
@endsection
            
            