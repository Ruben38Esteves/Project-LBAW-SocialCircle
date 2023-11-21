<div class="post">
    <a href="/profile/{{$post->owner->username}}">
        <div class="postHeader">
            <div class="miniUserPic">
                
            </div>
            <div class="username">
                {{$post->owner->username}}
            </div>
        </div>
    </a>
    {{ $post->content }}
</div>