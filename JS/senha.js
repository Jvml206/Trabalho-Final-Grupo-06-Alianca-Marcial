document.addEventListener("DOMContentLoaded", () => {
  const senhaInput = document.getElementById("nova_senha");
  const confirmarInput = document.getElementById("confirmar_senha");
  const salvarBtn = document.getElementById("btnSalvar");
  const toggleSenhaBtn = document.getElementById("toggleSenha");
  const toggleConfirmarBtn = document.getElementById("toggleConfirmar");

  const requisitos = {
    tamanho: document.getElementById("minChar"),
    maiuscula: document.getElementById("maiuscula"),
    minuscula: document.getElementById("minuscula"),
    numero: document.getElementById("numero"),
    especial: document.getElementById("especial")
  };

  const forcaSenha = document.getElementById("forcaSenha");
  const msgConfirmacao = document.getElementById("msgConfirmacao");

  // Função para verificar os critérios da senha
  function verificarSenha() {
    const senha = senhaInput.value;

    const temTamanho = senha.length >= 10;
    const temMaiuscula = /[A-Z]/.test(senha);
    const temMinuscula = /[a-z]/.test(senha);
    const temNumero = /[0-9]/.test(senha);
    const temEspecial = /[!@#$%^&*(),.?":{}|<>]/.test(senha);

    atualizarRequisito(requisitos.tamanho, temTamanho);
    atualizarRequisito(requisitos.maiuscula, temMaiuscula);
    atualizarRequisito(requisitos.minuscula, temMinuscula);
    atualizarRequisito(requisitos.numero, temNumero);
    atualizarRequisito(requisitos.especial, temEspecial);

    // Calcula a força
    const nivel = [temTamanho, temMaiuscula, temMinuscula, temNumero, temEspecial].filter(Boolean).length;

    if (nivel <= 2) {
      forcaSenha.textContent = "Força: Fraca 🔴";
      forcaSenha.style.color = "red";
    } else if (nivel <= 4) {
      forcaSenha.textContent = "Força: Média 🟡";
      forcaSenha.style.color = "orange";
    } else {
      forcaSenha.textContent = "Força: Forte 🟢";
      forcaSenha.style.color = "green";
    }

    // só verifica confirmação se o campo confirmar já tiver algo digitado
    if (confirmarInput.value.trim() !== "") {
      verificarConfirmacao();
    }
  }

  // Atualiza o requisito visualmente
  function atualizarRequisito(elemento, condicao) {
    elemento.style.color = condicao ? "green" : "red";
  }

  // Verifica se confirmação e senha são iguais e se os requisitos estão válidos
  function verificarConfirmacao() {
    const senha = senhaInput.value;
    const confirmar = confirmarInput.value;

    const todosValidos = [...document.querySelectorAll("#requisitos li")]
      .every(li => li.style.color === "green");

    if (confirmar === senha && todosValidos) {
      confirmarInput.style.borderColor = "green";
      msgConfirmacao.textContent = "As senhas coincidem ✅";
      msgConfirmacao.style.color = "green";
      salvarBtn.disabled = false;
    } else if (confirmar !== "") {
      confirmarInput.style.borderColor = "red";
      msgConfirmacao.textContent = "As senhas não coincidem ❌";
      msgConfirmacao.style.color = "red";
      salvarBtn.disabled = true;
    } else {
      confirmarInput.style.borderColor = "";
      msgConfirmacao.textContent = "";
      salvarBtn.disabled = true;
    }
  }

  // Alterna visibilidade da senha
  function alternarVisibilidade(input, botao) {
    const tipo = input.type === "password" ? "text" : "password";
    input.type = tipo;
    botao.textContent = tipo === "password" ? "👁️" : "🙈";
  }

  // Eventos
  senhaInput.addEventListener("input", verificarSenha);
  confirmarInput.addEventListener("input", verificarConfirmacao);
  toggleSenhaBtn.addEventListener("click", () => alternarVisibilidade(senhaInput, toggleSenhaBtn));
  toggleConfirmarBtn.addEventListener("click", () => alternarVisibilidade(confirmarInput, toggleConfirmarBtn));
});
