function getNameForm(){
    const nameform = document.querySelector('#profile-name-edit');   
    nameform.style.display = 'block';
    const nameformbutton = document.querySelector('#profile-edit-name');
    nameformbutton.style.display = 'none';
}

function hideNameForm(){
    const nameform = document.querySelector('#profile-name-edit');   
    nameform.style.display = 'none';
    const nameformbutton = document.querySelector('#profile-edit-name');
    nameformbutton.style.display = 'block';
}

function getAboutMeForm(){
    const aboutMeform = document.querySelector('#profile-about-edit');   
    aboutMeform.style.display = 'block';
    const aboutMeformbutton = document.querySelector('#profile-edit-aboutme');
    aboutMeformbutton.style.display = 'none';
}

function hideAboutMeForm(){
    const aboutMeform = document.querySelector('#profile-about-edit');   
    aboutMeform.style.display = 'none';
    const aboutMeformbutton = document.querySelector('#profile-edit-aboutme');
    aboutMeformbutton.style.display = 'block';
}

function getPictureForm(){
    const imageForm = document.querySelector('#profile-picture-edit');
    imageForm.style.display = 'block';
    const notifs = sidebar.querySelector('#profile-edit-image');
    notifs.style.display = 'none';
}

function hidePictureForm(){
    const imageForm = document.querySelector('#profile-picture-edit');
    imageForm.style.display = 'none';
    const notifs = sidebar.querySelector('#profile-edit-image');
    notifs.style.display = 'block';
}