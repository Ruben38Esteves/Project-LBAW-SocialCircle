@php 
    use App\Models\User;
    use App\Models\Image;
@endphp


<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            <?php
            $url = $_SERVER['REQUEST_URI'];

            if (endsWith($url, '/home')) {
                echo 'Home';
            } elseif (preg_match('/\/profile\/\w+/', $url)) {
                echo 'Profile';
            } elseif (endsWith($url, '/about')) {
                echo 'About';
            } elseif (startsWith($url, '/messages/')) {
                echo 'Messages';
            } else {
                echo config('app.name', 'Laravel');
            }

            function endsWith($haystack, $needle) {
                $length = strlen($needle);
                if ($length == 0) {
                    return true;
                }
                return (substr($haystack, -$length) === $needle);
            }

            function startsWith($haystack, $needle) {
                return strpos($haystack, $needle) === 0;
            }
            ?>
        </title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer></script>
        <script type="text/javascript" src={{ asset('js/getFriendList.js') }}></script>
        <script type="text/javascript" src={{ asset('js/getGroupList.js') }}></script>
        <script type="text/javascript" src={{ asset('js/getNotifications.js') }}></script>
        <script type="text/javascript" src={{ asset('js/getMessageList.js') }}></script>
    </head>
    <body>
        <nav id = 'sidebar'>
            @section('sidebar')
            @show
        </nav>
        <main>
            <header>
                @if (Auth::check())
                    <a class="button" href="{{ url('/logout') }}"> Logout </a> <a href="../profile/{{ Auth::user()->username }}">{{ Auth::user()->username }}</a>
                @endif
            </header>
            <section id="content">
                <div class="search-bar">
                <form action="{{ url('/search?query') }}" method="GET" id="search-form">
                    <input type="text" name="query" id="search-input" placeholder="Search users and groups...">
                    <div id="search-results"></div>
                </form>
                </div>
                @yield('content')
            </section>
        </main>
    </body>
</html>


<!-- JS script retorna sem precisar de enter-->


<script>

// desativar enter

const searchForm = document.getElementById('search-form');

searchForm.addEventListener('keydown', function(event) {
    if (event.key === 'Enter') {
        event.preventDefault(); 
        searchInput.blur(); 
    }
});



const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');

searchInput.addEventListener('input', function() {
    const query = searchInput.value.trim();

    if (query.length > 0) {
        fetch(`/search?query=${query}`)
            .then(response => response.json())
            .then(data => {
                let resultsHtml = '<ul class="searchResultsList">';

                if (data.users.length > 0) {
                    resultsHtml += '<li class="search-result-header">Users</li>';

                    data.users.forEach(result => {
                        resultsHtml += `
                            <li class="search-result-user">
                                <a href="/profile/${result.username}">
                                    <img src="/images/${result.imagepath}" class="searchPicture" alt="profile-picute"></img>
                                    <p>${result.username}</p>
                                </a>
                            </li>
                        `;
                    });
                }

                if (data.groups.length > 0) {
                    resultsHtml += '<li class="search-result-header">Groups</li>';

                    data.groups.forEach(result => {
                        resultsHtml += `
                            <li class="search-result-group">
                                <a href="/group/${result.groupid}">
                                    <p>${result.name}</p>
                                </a>
                            </li>
                        `;
                    });
                }

                if (data.posts.length > 0) {
                    resultsHtml += '<li class="search-result-header">Posts</li>';

                    data.posts.forEach(result => {
                        resultsHtml += `
                            <li class="search-result-post">
                                <div class= "user-in-postSearch">
                                <a href="/profile/${result.username}">
                                    <img src="/images/${result.imagepath}" class="searchPicture" alt="profile-picture"></img>
                                    <p>${result.username}</p>
                                </a>
                                </div>
                                <p class="searchPostContent">${result.content}</p>
                            </li>
                        `;
                    });
                }

                

                searchResults.innerHTML = resultsHtml;
                
                if (data.users.length === 0 && data.groups.length === 0 && data.posts.length === 0) {
                    searchResults.innerHTML = '<li class="search-result-header">No Results</li>';
                }
                resultsHtml += '</ul>';
            })
            .catch(error => {
                console.error('Error:', error);
            });
    } else {
        searchResults.innerHTML = '';
    }
});
</script>