function getMessageList(){
    const sidebar = document.querySelector('.sidebar');            
    const ul = sidebar.querySelector('ul');
    ul.style.display = 'none';
    const notifs = sidebar.querySelector('#messages');
    notifs.style.display = 'block';
}

function hideMessages(){
    const sidebar = document.querySelector('.sidebar');            
    const ul = sidebar.querySelector('ul');
    ul.style.display = 'block';
    const notifs = sidebar.querySelector('#messages');
    notifs.style.display = 'none';
}