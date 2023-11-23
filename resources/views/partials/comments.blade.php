<div  class="post">
    <a href="/profile/{{$comment->owner->username}}" class="post-link">
        <div class="post-header">
            <div class="miniUserPic">
                
            </div>
            <div class="username">
                {{$comment->owner->username}}
            </div>
        </div>
    </a>
    <div class="post-content">
        <div class="post_text_content" style="display: block">
            {{ $comment->content }}
        </div>
    </div>
    <div class="post-footer">
        <div class="post-date">
            {{ $comment->created_at }}
        </div>
        <!--
        <div class="post-likes">
            <button id="like_button">
                Like
            </button>
            <button id="dislike_button">
                Dislike
            </button>
        </div>
        -->
    </div>
</div>