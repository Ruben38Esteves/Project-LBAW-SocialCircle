/*
function getGroupList(username) {
    fetch('/groups/' + username)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            var groupListHTML = '<ul class="sidebar-friends"><li class="sidebar-top"><h1 id="page-title"><a href="{{ url(\'/home\') }}">SocialCircle</a></h1></li><li class="create_group_button"><button onclick="location.href=\'/group/create\'">Create Group</button></li>'; // Added a comma after the closing bracket of the onclick attribute
            data.forEach(result => {
                groupListHTML += '<li class="search-result"><a href="/group/' + result.groupid + '"><div class="miniUserPic"></div><p>' + result.name + '</p></a></li>';
            });
            groupListHTML += '<li><button onclick=\'restoreSidebar()\'>Go Back</button></li>';
            groupListHTML += '</ul>';
            const sidebar = document.querySelector('.sidebar');
            const ul = sidebar.querySelector('ul');
            ul.style.display = 'none';
            sidebar.innerHTML += groupListHTML;
        });
}
*/

function getGroupList(){
    const sidebar = document.querySelector('.sidebar');            
    const ul = sidebar.querySelector('ul');
    ul.style.display = 'none';
    const notifs = sidebar.querySelector('#groups');
    notifs.style.display = 'block';
}

function hideGroups(){
    const sidebar = document.querySelector('.sidebar');            
    const ul = sidebar.querySelector('ul');
    ul.style.display = 'block';
    const notifs = sidebar.querySelector('#groups');
    notifs.style.display = 'none';
}