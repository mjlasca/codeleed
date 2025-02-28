const form = document.querySelector("form");
const table = document.querySelector("table");
const msg = document.querySelector(".msg-response");
const codeH = document.querySelector("#code");
const urlRedirectH = document.querySelector("#url_redirect");
const categoryH = document.querySelector("#category");
const editH = document.querySelector("#edit");
const urlDownloadH = document.querySelector("#url_download");
const modalBtn = document.querySelector("#modalBot");
const modalSub = document.querySelector("#modal-sub");

console.log(localStorage.getItem("admeee"));
if (modalBtn && localStorage.getItem("admeee") != "true") {
  modalBtn.click();
}
if (modalSub) {
  const errorModal = document.querySelector(".error-modal");
  modalSub.addEventListener("click", function () {
    const passAdm = document.querySelector("#passadm");
    const date = new Date();
    if (passAdm) {
      if (passAdm.value == "eeE" + date.getDate() + (date.getMonth() + 1)) {
        localStorage.setItem("admeee", true);
        modalBtn.click();
      } else {
        errorModal.textContent = "Contraseña incorrecta";
      }
    }
  });
}
codeList();
codeH.addEventListener("input", function (event) {
  codeList(this.value);
});
form.addEventListener("submit", async (event) => {
  event.preventDefault();
  msg.textContent = "";
  const obj = {
    code: codeH.value,
    category: categoryH.value,
    url_redirect: urlRedirectH.value,
    edit: editH.value,
    url_download: urlDownloadH.value,
  };

  createOrUpdate(obj);
});
async function createOrUpdate(data) {
  try {
    const response = await fetch("../../route/route.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });
    const result = await response.json();
    if (result.success == true) {
      codeList();
      if (data.edit != "")
        msg.textContent = "Se ha editado el registro con éxito";
      else msg.textContent = "Se ha creado el registro con éxito";
      form.reset();
      form.querySelector(".create-reg").classList.add("hide");
      return result;
    } else {
      return false;
    }
  } catch (error) {
    msg.textContent =
      "Oops, Estás intentando crear un registro con un código existente";
    console.error("Error en la petición:", error);
  }
}

async function updateReg(code) {
  const regis = await getLeedCode(code);
  console.log(regis);
  if (regis) {
    codeH.value = regis.code;
    categoryH.value = regis.category;
    urlDownloadH.value = regis.url_download;
    urlRedirectH.value = regis.url_redirect;
    editH.value = code;
    codeH.setAttribute("readonly", true);
    form.querySelector(".create-reg").classList.remove("hide");
  }
}
async function deleteReg(code) {
  if (confirm("¿Estás seguro de realizar esta acción?")) {
    const data = {
      codeDelete: code,
      delete: true,
    };
    try {
      const response = await fetch("../../route/route.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
      });
      const result = await response.json();
      if (result) {
        location.reload();
        return result;
      } else {
        return false;
      }
    } catch (error) {
      console.error("Error en la petición:", error);
    }
  }
}
async function getLeedCode(code) {
  const data = {
    get_code: code,
  };
  try {
    const response = await fetch("../../route/route.php", {
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
async function codeList(search = "") {
  try {
    const response = await fetch(
      "../../route/route.php?list_code=true&search=" + search
    );
    const result = await response.json();
    if (result) {
      const listTable = document.getElementById("list-code");
      listTable.innerHTML = "";
      let tds = "";
      Object.keys(result).forEach((key) => {
        tds += `<tr>
                    <td>${result[key].code}</td>
                    <td>${result[key].url_download}</td>
                    <td>${result[key].category}</td>
                    <td>${result[key].url_redirect}</td>
                    <td class="actions">
                        <button class="btn btn-primary" onclick="updateReg('${result[key].code}')">M</button>
                        <button class="btn btn-danger" onclick="deleteReg('${result[key].code}')">E</button>
                    </td>
                    
                    </tr>`;
      });
      listTable.innerHTML = tds;
    } else {
      return false;
    }
  } catch (error) {
    console.error("Error en la petición:", error);
  }
}
