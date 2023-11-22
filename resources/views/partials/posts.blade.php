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
        <span id="post-content-{{ $post->id }}">{{ $post->content }}</span>
        <textarea id="post-edit-{{ $post->id }}" class="post-edit" style="display: none;">{{ $post->content }}</textarea>
    </div>
    @auth
        @if ($post->owner->id === auth()->user()->id)
            <a href="#" class="edit-text" data-post-id="{{ $post->id }}" onclick="editPost({{ $post->id }})">Edit</a>
            <a href='#' class="save-text" data-post-id="{{ $post->id }}" onclick="savePost({{ $post->id }})" style="display: none;">Save</a>
            <a href='#' class="delete-text" data-post-id="{{ $post->id }}">Delete</a>
        @endif
    @endauth
</div>

<script>
    function editPost(postId) {
        document.getElementById('post-content-' + postid).style.display = 'none';
        document.getElementById('post-edit-' + postid).style.display = 'block';

        // mostrar botao save
        document.querySelector('.edit-text[data-post-id="' + postid + '"]').style.display = 'none';
        document.querySelector('.save-text[data-post-id="' + postid + '"]').style.display = 'inline-block';
    }

    function savePost(postId) {
        
        var newContent = document.getElementById('post-edit-' + postid).value;

       
        var url = '/posts/' + postid + '/edit';
        var data = { content: newContent };

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
            .then(response => {
                if (response.ok) {
                    // Update the post content in the view
                    document.getElementById('post-content-' + postid).textContent = newContent;

                    // Hide edit textarea and show post content
                    document.getElementById('post-edit-' + postid).style.display = 'none';
                    document.getElementById('post-content-' + postid).style.display = 'block';

                    // Hide save button and show edit button
                    document.querySelector('.save-text[data-post-id="' + postid + '"]').style.display = 'none';
                    document.querySelector('.edit-text[data-post-id="' + postid + '"]').style.display = 'inline-block';

                    console.log('Post updated successfully');
                } else {
                    console.error('Error:', response.status);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }
</script>