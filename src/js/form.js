const form = document.querySelector("form");
const params = new URLSearchParams(window.location.search);
const source = params.get('utm_source');
form.addEventListener("submit", async (event) => {
  event.preventDefault();
  const code = form.querySelector("#code");
  const mail = form.querySelector("#email");
  const error = form.querySelector(".with-errors");
  const cancelBtn = form.querySelector(".cancel-btn");
  error.textContent = "";
  if (mail.value != "") {
    form.querySelector("#sub").textContent = "Enviando el correo...";
    const obj = {
      code_send: code.value.toUpperCase(),
      mail_send: mail.value,
      sendmail: true,
      code_source: source ?? 'sin_fuente'
    };
    const restSend = await senMailerLite(obj);
  } else {
    if (!mail.parentNode.classList.contains("hide"))
      mail.parentNode.classList.add("hide");
    const data = await validateCode(code);
    if (data == undefined) {
      error.textContent = "No existe el código ingresado";
    } else {
      if (data.url_redirect == "") {
        mail.parentNode.classList.remove("hide");
        code.setAttribute("readonly", true);
        cancelBtn.classList.remove("hide");
      } else location.href = data.url_redirect;
    }
  }
});

async function validateCode(code) {
  const data = {
    code_leed: code.value.toUpperCase(),
  };
  try {
    const response = await fetch("src/route/route.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
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
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });
    const result = await response.json();
    if (result && result.success == true) {
      window.location.href = "https://elautomatizador.io/lm_registrocontinuar/";
    } else {
      form.querySelector("#sub").textContent = "Enviar";
      alert(result.msg);
      location.reload();
    }
  } catch (error) {
    console.error("Error en la petición:", error);
  }
}
