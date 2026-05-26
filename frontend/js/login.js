document.getElementById("loginForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const mensagem = document.getElementById("mensagem");

    try {
        const formData = new FormData();
        formData.append("email", email);
        formData.append("password", password);

        const resposta = await fetch("../api/login.php", {
            method: "POST",
            body: formData
        });

        const dados = await resposta.json();

        if (dados.success) {
            mensagem.innerHTML = `<div class="alert alert-success">${dados.message}</div>`;

            setTimeout(() => {
                window.location.href = "dashboard.html";
            }, 1000);
        } else {
            mensagem.innerHTML = `<div class="alert alert-danger">${dados.message}</div>`;
        }

    } catch (erro) {
        mensagem.innerHTML = `<div class="alert alert-danger">Erro ao ligar ao servidor.</div>`;
    }
});
