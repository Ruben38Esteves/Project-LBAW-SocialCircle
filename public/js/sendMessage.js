function sendMessage(senderID, receiverID, receiverUsername, message) {
    const url = `/message/send/${receiverUsername}`;
  
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      },
      body: JSON.stringify({
        senderID: senderID,
        receiverID: receiverID,
        message: message
      })
    })
      .then(response => response.json())
      .then(data => {
        console.log(data);
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }