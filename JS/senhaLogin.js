document.addEventListener("DOMContentLoaded", () => {
  const senhaInput = document.getElementById("senha");
  const toggleSenhaBtn = document.getElementById("toggleConfirmar");

  // Função que alterna mostrar/ocultar senha
  function alternarVisibilidade() {
    const tipo = senhaInput.type === "password" ? "text" : "password";
    senhaInput.type = tipo;

    // Troca o ícone
    toggleSenhaBtn.textContent = tipo === "password" ? "👁️" : "🙈";
  }

  // Evento para o botão de visualizar senha
  toggleSenhaBtn.addEventListener("click", alternarVisibilidade);
});
