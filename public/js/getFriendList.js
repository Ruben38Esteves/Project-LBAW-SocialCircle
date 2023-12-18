/*
function getFriendList(username) {
    fetch('/friends/' + username)
        .then(response => response.json())
        .then(data => {
            var friendListHTML = '<ul class="sidebar-friends"><li class="sidebar-top"><h1 id="page-title"><a href="{{ url(\'/home\') }}">SocialCircle</a></h1></li>';
            data.forEach(result => {
                 friendListHTML +='<li class="search-result"><a href="/profile/'+result.username+'"><div class="miniUserPic"></div><p>'+result.username+'</p></a></li>'
            });
            friendListHTML += '<li><button onclick=\'restoreSidebar()\'>Go Back</button></li>'
            friendListHTML += '</ul>';
            const sidebar = document.querySelector('.sidebar');            
            const ul = sidebar.querySelector('ul');
            ul.style.display = 'none';
            sidebar.innerHTML += friendListHTML;            
        })
}
*/

function getFriendList(){
    const sidebar = document.querySelector('.sidebar');            
    const ul = sidebar.querySelector('ul');
    ul.style.display = 'none';
    const notifs = sidebar.querySelector('#friends');
    notifs.style.display = 'block';
}

function hideFriends(){
    const sidebar = document.querySelector('.sidebar');            
    const ul = sidebar.querySelector('ul');
    ul.style.display = 'block';
    const notifs = sidebar.querySelector('#friends');
    notifs.style.display = 'none';
}