// Verificar se o atleta existe em tempo real
function configurarVerificacaoUsuario(listaUsuarios) {
    const nomeAtleta = document.querySelector('#nome_atleta');
    const mensagemUsuario = document.querySelector('#mensagemUsuario');

    if (!nomeAtleta || !mensagemUsuario) return; // Evita erro se o elemento não existir

    mensagemUsuario.style.display = "none";

    function verificarUsuario() {
        const nome = nomeAtleta.value.trim();

        // Se o campo estiver vazio, limpa mensagem
        if (nome.length === 0) {
            mensagemUsuario.textContent = "";
            mensagemUsuario.style.display = "none";
            return;
        }

        // Verifica se o nome existe na lista
        const existe = listaUsuarios.some(u => u.toLowerCase() === nome.toLowerCase());

        mensagemUsuario.style.display = "block";

        if (existe) {
            mensagemUsuario.className = "alert alert-success mt-2 mb-3";
            mensagemUsuario.textContent = "✅ Usuário encontrado!";
        } else {
            mensagemUsuario.className = "alert alert-danger mt-2 mb-3";
            mensagemUsuario.textContent = "❌ Usuário não encontrado!";
        }
    }

    // Verifica quando o campo perde o foco
    nomeAtleta.addEventListener('blur', verificarUsuario);
}