@php
    
    use App\Models\Image; 
    $image = Image::where('imageid', $user->profilepictureid)->first();

@endphp
<a class="user_header" href="/profile/{{$user->username}}">
    @if ($image)
    <div class="user_header_img">
        <img src="/images/{{$image->imagepath}}">
    </div>
    @else
    <div class="user_header_img">
        <img src="/images/default-user.jpg">
    </div>
    @endif
    <ul class="user_names">
        <li class="user_full_name">
            <?php echo($user->firstname.' '.$user->lastname) ?>
        </li>
        <li class="user_username">
            <?php echo('@'.$user->username) ?>
        </li>
    </ul>
</a>


