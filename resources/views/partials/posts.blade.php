<div id="post_{{$post->postid}}" class="post">
    @each('partials.userheader', [$post->owner], 'user')
    <div class="post-content">
        <div class="post_text_content" style="display: block">
            {{ $post->content }}
        </div>
        <div class="likes">
            <a>{{count($post->likes)}} ❤️</a>
        </div>
        <div class="edit_post" style="display: none">
            <form action="{{ url('/posts/edit/'.$post->postid) }}" method="POST">
                @csrf
                @method('PUT')
                <br>
                <label for="content">Content:</label>
                <textarea name="content" placeholder="{{ $post->content }}" required></textarea>
                <br>
                <button type="submit">Edit</button>
                <button onclick="hide_post_changer('post_{{$post->postid}}')">Go Back</button>
            </form>
        </div>
    </div>
    <ul class="post_buttons">
        <li>
            @if (Auth::user()->likedPost($post))
            <form action="{{ route('dislike',['postid'=>$post->postid]) }}" method="POST">
                @csrf
                <button type="submit" id="delete_button">
                    Dislike
                </button>
            </form>
            @else
            <form action="{{ route('like',['postid'=>$post->postid]) }}" method="POST">
                @csrf
                <button type="submit" id="delete_button">
                    ❤️
                </button>
            </form>
            @endif
        </li>
        <?php if(Auth::check() && (Auth::user()->id == $post->owner->id || Auth::user()->isAdmin())): ?>
            <li>
                <button id="edit_button" onclick="show_post_changer('post_{{$post->postid}}')" >
                    Edit
                </button>
            </li>
            <li>
                <form action="{{ url('/posts/delete/'.$post->postid) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="delete_button">
                        Delete
                    </button>
                </form>
            </li>
        <?php endif; ?>
    </ul>   
    <div class="post-footer">
        <div class="post-date">
            {{ $post->updated_at }}
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
    <section class="post-form">
        @auth
            <form action="{{ url('/comments/create/'.$post->postid) }}" method="POST">
                @csrf
                @method('PUT')
                <br>
                <input type="text" name="content" required></textarea>
                <br>
                <button type="submit">Comment</button>
            </form>
        @endauth
    </section>
    <?php $comments = $post->comments; ?>
    @each('partials.comments', $comments, 'comment')
</div>
<script>
    function show_post_changer(postId) {
        let post = document.getElementById(postId);
        post.getElementsByClassName("edit_post")[0].style.display = "block";
        post.getElementsByClassName("post_buttons")[0].style.display = "none";
        post.getElementsByClassName("post_text_content")[0].style.display = "none";
    }
    function hide_post_changer(postId) {
        let post = document.getElementById(postId);
        post.getElementsByClassName("edit_post")[0].style.display = "none";
        post.getElementsByClassName("post_buttons")[0].style.display = "inline-block";
        post.getElementsByClassName("post_text_content")[0].style.display = "block";
    }
</script>