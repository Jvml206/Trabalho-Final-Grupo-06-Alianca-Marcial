function enviarFormulario(url, codigo) {

    // Cria o formulário
    const form = document.createElement('form');
    form.method = 'POST'; // ou 'GET' se necessário
    form.action = url;

    // Cria o input e adiciona ao formulário
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'id'; // nome do campo enviado
    input.value = codigo;

    form.appendChild(input);

    // Adiciona o formulário ao corpo da página
    document.body.appendChild(form);

    // Envia o formulário
    form.submit();
}

const email = document.querySelector('#email');
const confirma = document.querySelector('#confirmaEmail');
const mensagem = document.querySelector('#mensagem');
const form = document.querySelector('#form_valida_email');
mensagem.style.display = "none";

function validarEmails() {


    if (confirma.value.trim().length === 0) {
        mensagem.textContent = "";
        mensagem.style.display = "none";
        return;
    }

    if (email.value.trim() !== confirma.value.trim()) {
        mensagem.textContent = "❌ E-mails não conferem";
        mensagem.className = "alert alert-danger mt-2 mb-3";
        mensagem.style.display = "block";
        return false;
    } else {
        mensagem.style.display = "block";
        mensagem.className = "alert alert-success mt-2 mb-3";
        mensagem.textContent = "✅ Emails iguais"; return true;
    }
}

// Validação em tempo real
confirma.addEventListener('input', validarEmails);

// Impede envio se emails não forem iguais
form.addEventListener('submit', function (e) {
    if (!validarEmails()) {
        e.preventDefault();
        alert("Corrija o email antes de enviar.");
    }
});