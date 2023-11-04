const form = document.querySelector('form');
const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');
const utmType = urlParams.get('utm_type');
const utmGroup = urlParams.get('utm_group');
const utmSubject = urlParams.get('utm_subject');
const utmUrl = urlParams.get('utm_url');
const title = document.getElementById('title-form');
if(utmSubject != '')
    title.innerText =  utmSubject;


form.addEventListener('submit', async (event) => {
    event.preventDefault();

    

    const formData = new FormData(form);
    formData.append('id',id);
    formData.append('utm_type',utmType);
    formData.append('utm_group',utmGroup);
    formData.append('utm_subject',utmSubject);
    formData.append('utm_url',utmUrl);
    const response = await fetch('/getleedseee/controller/SendMail.php', {
    method: 'POST',
    body: formData
    });
    const data = await response.json();
    var msg = document.getElementById('msg');
    console.log(data);
    if(data.status == false){
        msg.classList.remove('alert-success');
        msg.classList.add('alert-danger');
        msg.innerHTML = '<strong>No hemos podido enviar el correo</strong> Inténtalo nuevamente en un minuto';
    }else{
        document.getElementById('email').value = '';
        msg.classList.remove('alert-danger');
        msg.classList.add('alert-success');
        msg.innerHTML = '<strong>Hemos enviado un correo electrónico, por favor revisa tu bandeja de entrada o spam</strong>';
    }

});



