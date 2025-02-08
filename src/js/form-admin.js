const form = document.querySelector('form');
const table = document.querySelector('table');
codeList();
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const codeH = document.querySelector('#code');
    const urlRedirectH = document.querySelector('#url_redirect');
    const categoryH = document.querySelector('#category');
    const obj = {
        code : codeH.value,
        category : categoryH.value,
        url_redirect : urlRedirectH.value
    }
    createOrUpdate(obj);

});
async function createOrUpdate(data) {
    try {
        const response = await fetch("../../route/route.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        if (result.success == true) {
            return result;
        } else {
            return false;
        }
    } catch (error) {
        console.error("Error en la petición:", error);
    }
}

async function codeList() {
    try {
        const response = await fetch("../../route/route.php?list_code=true");
        const result = await response.json();
        if (result) {
            const listTable = document.getElementById('list-code');
            let tds = "";
            Object.keys(result).forEach(key => {
                tds += `<tr>
                    <td>${result[key].code}</td>
                    <td>${result[key].url_redirect}</td>
                    <td>${result[key].category}</td>
                    <td class="actions">
                        <button class="btn btn-primary" onclick="update(${result[key].code})">M</button>
                        <button class="btn btn-danger" onclick="delete(${result[key].code})">E</button>
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



