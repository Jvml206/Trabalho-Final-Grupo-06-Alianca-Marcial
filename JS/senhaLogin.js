document.addEventListener("DOMContentLoaded", () => {
    const senhaInput = document.getElementById("senha");
    const toggleSenhaBtn = document.getElementById("toggleConfirmar");
    const icon = document.getElementById("iconSenha");

    function alternarVisibilidade() {
        const tipoSenha = senhaInput.type === "password" ? "text" : "password";
        senhaInput.type = tipoSenha;

        if (tipoSenha === "password") {
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    }

    toggleSenhaBtn.addEventListener("click", alternarVisibilidade);
});