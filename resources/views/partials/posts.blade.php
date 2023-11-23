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
        <div id="edit_post" style="display: none">
            <form action="{{ route('posts.create') }}" method="POST">
                @csrf
                <br>
                <label for="content">Content:</label>
                <textarea name="content" required></textarea>
                <br>
                <!-- Add other form fields as needed -->
                <button type="submit">Edit</button>
                <button onclick="hide_post_changer()">Go Back</button>
            </form>
        </div>
    </div>
    <?php if(Auth::check() && Auth::user()->id == $post->owner->id): ?>
        <button id="edit_button" onclick="show_post_changer()" style="display: block">
            Edit
        </button>
    <?php endif; ?>
</div>
<script>
    function show_post_changer() {
        document.getElementById("edit_post").style.display = "block";
        document.getElementById("edit_button").style.display = "none";
    }
    function hide_post_changer() {
        document.getElementById("edit_post").style.display = "none";
        document.getElementById("edit_button").style.display = "block";
    }
</script>