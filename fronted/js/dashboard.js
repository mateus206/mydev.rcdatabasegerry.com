let utilizadorAtual = null;

document.addEventListener("DOMContentLoaded", async () => {
    const sessaoValida = await verificarSessao();

    if (!sessaoValida) {
        window.location.href = "index.html";
        return;
    }

    document.getElementById("clienteForm").addEventListener("submit", guardarCliente);
    document.getElementById("btnCancelar").addEventListener("click", limparFormulario);
    document.getElementById("btnLogout").addEventListener("click", logout);

    await listarClientes();
});

async function verificarSessao() {
    try {
        const resposta = await fetch("../api/verificar_sessao.php");
        const dados = await resposta.json();

        if (!dados.success) {
            return false;
        }

        utilizadorAtual = dados.utilizador;

        document.getElementById("nomeUtilizador").textContent = utilizadorAtual.nome;
        document.getElementById("emailUtilizador").textContent = utilizadorAtual.email;

        return true;
    } catch (erro) {
        return false;
    }
}

async function listarClientes() {
    const tabela = document.getElementById("tabelaClientes");

    try {
        const resposta = await fetch("../api/listar_clientes.php");
        const dados = await resposta.json();

        if (!dados.success) {
            tabela.innerHTML = `<tr><td colspan="7" class="text-center text-danger">${dados.message}</td></tr>`;
            return;
        }

        if (dados.clientes.length === 0) {
            tabela.innerHTML = `<tr><td colspan="7" class="text-center">Não existem clientes registados.</td></tr>`;
            return;
        }

        tabela.innerHTML = "";

        dados.clientes.forEach(cliente => {
            const tr = document.createElement("tr");

            tr.innerHTML = `
                <td>${cliente.id}</td>
                <td>${cliente.nome}</td>
                <td>${cliente.nif}</td>
                <td>${cliente.email ?? ""}</td>
                <td>${cliente.telefone ?? ""}</td>
                <td>${cliente.morada ?? ""}</td>
                <td>
                    <button class="btn btn-warning btn-sm me-1">Editar</button>
                    <button class="btn btn-danger btn-sm">Apagar</button>
                </td>
            `;

            const btnEditar = tr.querySelector(".btn-warning");
            const btnApagar = tr.querySelector(".btn-danger");

            btnEditar.addEventListener("click", () => preencherFormulario(cliente));
            btnApagar.addEventListener("click", () => apagarCliente(cliente.id));

            tabela.appendChild(tr);
        });
    } catch (erro) {
        tabela.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Erro ao carregar clientes.</td></tr>`;
    }
}

async function guardarCliente(e) {
    e.preventDefault();

    const id = document.getElementById("clienteId").value;
    const nome = document.getElementById("nome").value.trim();
    const nif = document.getElementById("nif").value.trim();
    const email = document.getElementById("email").value.trim();
    const telefone = document.getElementById("telefone").value.trim();
    const morada = document.getElementById("morada").value.trim();
    const mensagem = document.getElementById("mensagem");

    const formData = new FormData();
    formData.append("id", id);
    formData.append("nome", nome);
    formData.append("nif", nif);
    formData.append("email", email);
    formData.append("telefone", telefone);
    formData.append("morada", morada);

    const url = id ? "../api/editar_clientes.php" : "../api/criar_clientes.php";

    try {
        const resposta = await fetch(url, {
            method: "POST",
            body: formData
        });

        const dados = await resposta.json();

        if (dados.success) {
            mensagem.innerHTML = `<div class="alert alert-success">${dados.message}</div>`;
            limparFormulario();
            await listarClientes();
        } else {
            mensagem.innerHTML = `<div class="alert alert-danger">${dados.message}</div>`;
        }
    } catch (erro) {
        mensagem.innerHTML = `<div class="alert alert-danger">Erro ao comunicar com o servidor.</div>`;
    }
}

function preencherFormulario(cliente) {
    document.getElementById("clienteId").value = cliente.id;
    document.getElementById("nome").value = cliente.nome;
    document.getElementById("nif").value = cliente.nif;
    document.getElementById("email").value = cliente.email ?? "";
    document.getElementById("telefone").value = cliente.telefone ?? "";
    document.getElementById("morada").value = cliente.morada ?? "";
    window.scrollTo({ top: 0, behavior: "smooth" });
}

function limparFormulario() {
    document.getElementById("clienteForm").reset();
    document.getElementById("clienteId").value = "";
    document.getElementById("mensagem").innerHTML = "";
}

async function apagarCliente(id) {
    if (!confirm("Tem a certeza que pretende apagar este cliente?")) {
        return;
    }

    const formData = new FormData();
    formData.append("id", id);

    try {
        const resposta = await fetch("../api/apagar_clientes.php", {
            method: "POST",
            body: formData
        });

        const dados = await resposta.json();

        if (dados.success) {
            document.getElementById("mensagem").innerHTML = `<div class="alert alert-success">${dados.message}</div>`;
            await listarClientes();
        } else {
            document.getElementById("mensagem").innerHTML = `<div class="alert alert-danger">${dados.message}</div>`;
        }
    } catch (erro) {
        document.getElementById("mensagem").innerHTML = `<div class="alert alert-danger">Erro ao apagar o cliente.</div>`;
    }
}

async function logout() {
    try {
        await fetch("../api/logout.php");
    } catch (erro) {}

    window.location.href = "index.html";
}
