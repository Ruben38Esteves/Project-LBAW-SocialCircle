<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
        <link href="{{ url('css/app.css') }}" rel="stylesheet">
        <script type="text/javascript">
            // Fix for Firefox autofocus CSS bug
            // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
        </script>
        <script type="text/javascript" src={{ url('js/app.js') }} defer>
        </script>
    </head>
    <body>
        <nav id = 'sidebar'>
            @section('sidebar')
            @show
        </nav>
        <main>
            <header>
                @if (Auth::check())
                    <a class="button" href="{{ url('/logout') }}"> Logout </a> <a href="profile/{{ Auth::user()->username }}">{{ Auth::user()->username }}</a>
                @endif
            </header>
            <section id="content">
                <div class="search-bar">
                    <form action="{{ url('/search?query') }}" method="GET">
                        <input type="text" name="query" id="search-input" placeholder="Search... Result will be displayed below without the need to press">
                        <div id="search-results"></div>
                    </form>
                </div>
                @yield('content')
            </section>
        </main>
    </body>
</html>

<script>
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value.trim();

        if (query.length > 0) {
            fetch(`/search?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    let resultsHtml = '<ul>';

                    data.forEach(result => {
                        resultsHtml += `
                            <li class="search-result">
                                <a href="/profile/${result.username}">
                                    <div class="miniUserPic"></div>
                                    <p>${result.username}</p>
                                </a>
                            </li>
                        `;
                    });

                    resultsHtml += '</ul>'; // End the unordered list

                    searchResults.innerHTML = resultsHtml;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            searchResults.innerHTML = '';
        }
    });
</script>