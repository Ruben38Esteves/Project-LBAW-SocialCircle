/*
function getNotificationList(username) {
    fetch('/notifications/' + username)
        .then(response => response.json())
        .then(data => {
            var notificationListHTML = '<ul class="sidebar-friends"><li class="sidebar-top"><h1 id="page-title"><a href="{{ url(\'/home\') }}">SocialCircle</a></h1></li>';
            data.forEach(result => {
                if(result.viewed == 'true'){
                    notificationListHTML +='<li class="search-result"><div class="notification-Viewed"><p>'+result.text+'</p><p>'+result.date+'</p></div></a></li>';
                }else{
                    notificationListHTML +='<li class="search-result"><div class="notification-notViewed"><p>'+result.text+'</p><p>'+result.date+'</p></div></a><form action="/notification/'+result.id+'/markViewed" method="POST"><?php @CSRF ?><textarea name="content" style="display = "none" "></textarea><button type="submit">Viewed</button></form></li>';
                }
                notificationListHTML += '';
            });
            notificationListHTML += '<li><button onclick=\'restoreSidebar()\'>Go Back</button></li>'
            notificationListHTML += '</ul>';
            const sidebar = document.querySelector('.sidebar');            
            const ul = sidebar.querySelector('ul');
            ul.style.display = 'none';
            sidebar.innerHTML += notificationListHTML;            
        })
}
*/

function getNotificationList(){
    const sidebar = document.querySelector('.sidebar');            
    const ul = sidebar.querySelector('ul');
    ul.style.display = 'none';
    const notifs = sidebar.querySelector('#notifications');
    notifs.style.display = 'block';
}

function hideNotifs(){
    const sidebar = document.querySelector('.sidebar');            
    const ul = sidebar.querySelector('ul');
    ul.style.display = 'block';
    const notifs = sidebar.querySelector('#notifications');
    notifs.style.display = 'none';
}