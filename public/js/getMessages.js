function getMessages(idAuthUser, idOtherUser, usernameAuthUser, usernameOtherUser) {
    fetch('/message/' + usernameOtherUser)
        .then(response => response.json())
        .then(data => {
            console.log(idAuthUser, idOtherUser, usernameAuthUser, usernameOtherUser);
            console.log(data);
            var messageListHTML = '<ul class="message-chat">';

            data.forEach(message => {
                console.log(message);
                if (message.sourceid == idAuthUser) {
                    console.log('dentro');
                    messageListHTML += '<li class="authenticated-user-message"><p class="message-text">' + message.message + '</p><p class="message-date">' + message.sent_at +'</p></li>';
                } else if (message.sourceid == idOtherUser) {
                    messageListHTML += '<li class="other-user-message"><p class="message-text">' + message.message + '</p><p class="message-date">' + message.sent_at +'</p></li>';
                }
            });

            messageListHTML += '</ul>';
            document.getElementById('messages-container').innerHTML = messageListHTML;
        });
}