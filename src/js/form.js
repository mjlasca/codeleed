const form = document.querySelector('form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const code = form.querySelector('#code');
    const mail = form.querySelector('#email');
    const error = form.querySelector('.with-errors');
    const cancelBtn = form.querySelector('.cancel-btn');
    error.textContent = "";
    console.log(mail.value);
    if(mail.value != ''){
        const obj = {
            'code_send' : code.value,
            'mail_send' : mail.value,
            'sendmail' : true
        };
        const restSend = await senMailerLite(obj);
    }else{
        if(!mail.parentNode.classList.contains('hide'))
            mail.parentNode.classList.add('hide');
        const data = await validateCode(code);
        if(data == undefined){
            error.textContent = "No existe el código ingresado";
        }
        else{
            if(data.url_redirect == ''){
                mail.parentNode.classList.remove('hide');
                code.setAttribute('readonly',true);
                cancelBtn.classList.remove('hide');
            }
            else
                location.href = data.url_redirect;
        }
    }
    
});

async function validateCode(code) {
    const data = {
        code_leed: code.value
    };
    try {
        const response = await fetch("src/route/route.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result) {
            return result[0];
        } else {
            return false;
        }
    } catch (error) {
        console.error("Error en la petición:", error);
    }
}

async function senMailerLite(data) {
    try {
        const response = await fetch("src/route/route.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        console.log(result);
        if (result && result.success == true) {
            alert("Se ha enviado los datos con éxito");
        } else {
            alert(result.msg);
        }
        location.reload();
    } catch (error) {
        console.error("Error en la petición:", error);
    }
}

