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
        <div id="post_content" style="display: block">
            {{ $post->content }}
        </div>
        <div id="edit_post" style="display: none">
            <form action="{{ url('/posts/edit/'.$post->postid) }}" method="POST">
                @csrf
                @method('PUT')
                <br>
                <label for="content">Content:</label>
                <textarea name="content" placeholder="{{ $post->content }}" required></textarea>
                <br>
                <button type="submit">Edit</button>
                <button onclick="hide_post_changer()">Go Back</button>
            </form>
        </div>
    </div>
    <?php if(Auth::check() && Auth::user()->id == $post->owner->id): ?>
        <div id="post_buttons" style="display: inline-block">
            <button id="edit_button" onclick="show_post_changer()" >
                Edit
            </button>
            <button id="delete_button">
                Delete
            </button>
        </div>
    <?php endif; ?>
</div>
<script>
    function show_post_changer() {
        document.getElementById("edit_post").style.display = "block";
        document.getElementById("post_buttons").style.display = "none";
        document.getElementById("post_content").style.display = "none";
    }
    function hide_post_changer() {
        document.getElementById("edit_post").style.display = "none";
        document.getElementById("post_buttons").style.display = "inline-block";
        document.getElementById("post_content").style.display = "block";
    }
</script>