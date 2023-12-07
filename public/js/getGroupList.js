function getGroupList(username) {
    fetch('/groups/' + username)
        .then(response => response.json())
        .then(data => {
            console.log(data);
            var groupListHTML = '<ul class="sidebar-friends"><li class="sidebar-top"><h1 id="page-title"><a href="{{ url(\'/home\') }}">SocialCircle</a></h1></li>';
            data.forEach(result => {
                 groupListHTML +='<li class="search-result"><a href="/group/'+result.groupid+'"><div class="miniUserPic"></div><p>'+result.name+'</p></a></li>'
            });
            groupListHTML += '<li><button onclick=\'restoreSidebar()\'>Go Back</button></li>'
            groupListHTML += '</ul>';
            const sidebar = document.querySelector('.sidebar');            
            const ul = sidebar.querySelector('ul');
            ul.style.display = 'none';
            sidebar.innerHTML += groupListHTML;            
        })
        
}