<div  class="post">
    <a href="/profile/{{$comment->owner->username}}" class="post-link">   
    @each('partials.userheader', [$comment->owner], 'user')
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
    </div>
</div>