const form = document.querySelector('form');
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const code = form.querySelector('#code');
    const mail = form.querySelector('#email');
    const error = form.querySelector('.with-errors');
    error.textContent = "";
    if(!mail.parentNode.classList.contains('hide'))
        mail.parentNode.classList.add('hide');
    const data = await validateCode(code);
    console.log(data);
    if(data == undefined){
        error.textContent = "No existe el código ingresado";
    }
    else{
        if(data.url_redirect == '')
            mail.parentNode.classList.remove('hide');
        else
            location.href = data.url_redirect;
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



