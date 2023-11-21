<div class="post">
    <a href="/profile/{{$post->owner->username}}" class="post-link">
        <div class="post-header">
            <div class="miniUserPic">
                
            </div>
            <div class="username">
                {{$post->owner->username}}
            </div>
        </div>
    </a>
    <div class="post-content">
        {{ $post->content }}
    </div>
</div>