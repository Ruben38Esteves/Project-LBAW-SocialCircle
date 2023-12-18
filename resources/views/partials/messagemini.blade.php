<?php
    use App\Models\User;
    if ($message->sourceid == Auth::user()->id) {
        $user =  User::find($message->targetid);
    } else {
        $user =  User::find($message->sourceid);
    }
?>
<a class="user_header" href="/messages/{{$user->username}}">
    <div class="user_header_img">
        <img src="/images/default-user.jpg">
    </div>
    <ul class="user_names">
        <li class="user_full_name">
            <?php echo($user->firstname.' '.$user->lastname) ?>
        </li>
        <li class="user_username">
            <?php echo('@'.$user->username) ?>
        </li>
        <li class="user_message">
            <?php echo($message->message) ?>
        </li>
    </ul>
</a>