@section('sidebar')
    <?php 
    use App\Models\Message;
    $notifications = Auth::user()->notifications;
    $friends = Auth::user()->friends;
    $groups = Auth::user()->groups;
    $messages = [];
    foreach ($friends as $friend) {
        $message1 = Message::where('sourceid', $friend->id)->where('targetid', Auth::user()->id)->latest('sent_at')->first();
        $message2 = Message::where('sourceid', Auth::user()->id)->where('targetid', $friend->id)->latest('sent_at')->first();
        if ($message1 != null) {
            if($message2 != null) {
                if ($message1->sent_at > $message2->sent_at) {
                    $messages[]= $message1;
                } else {
                    $messages[]= $message2;
                }
            } else {
                $messages[]= $message1;
            }
        }else{
            if($message2 != null) {
                $messages[]= $message2;
            }
        }
    }
    ?>
    <aside class = 'sidebar'>
        <ul class = 'list-unstyled sidebar-itens-positioning'>
            <li class="sidebar-top">
                <h1 id="page-title"><a href="{{ url('/home') }}">SocialCircle</a></h1>
            </li>
            <li>
                <button class="mainbutton" onclick="location.href='{{url('/home')}}'">Home</button>
            </li>
            <li>
                <button class="mainbutton" onclick='getMessageList()'>Messages</button>
            </li>
            <li>
                <button class="mainbutton" onclick='getGroupList()'>Groups</button>
            </li>
            <li>
                <button class="mainbutton" onclick='getFriendList()'>Friends</button>
            </li>
            <li>
                <button class="mainbutton" onclick='getNotificationList()'>Notifications</button>
            </li>
        </ul>
        <div id='friends' style="display: none">
            <h1 id="page-title"><a href="{{ url('/home') }}">SocialCircle</a></h1>
            @each('partials.userheader',$friends, 'user')
            <button onclick='hideFriends()'>Go Back</button>
        </div>
        <div id='groups' style="display: none">
            <h1 id="page-title"><a href="{{ url('/home') }}">SocialCircle</a></h1>
            @each('partials.groupheader',$groups, 'group')
            <button onclick='hideGroups()'>Go Back</button>
        </div>
        <div id='messages' style="display: none">
            <h1 id="page-title"><a href="{{ url('/home') }}">SocialCircle</a></h1>
            @each('partials.messagemini',$messages, 'message')
            <button onclick='hideMessages()'>Go Back</button>
        </div>
        <div id='notifications' style="display: none">
            <h1 id="page-title"><a href="{{ url('/home') }}">SocialCircle</a></h1>
            @each('partials.notification', $notifications, 'notification')
            <button onclick='hideNotifs()'>Go Back</button>
        </div>
    </aside>
@endsection
            
            