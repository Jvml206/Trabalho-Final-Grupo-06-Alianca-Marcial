document.addEventListener("DOMContentLoaded", function () {
    const selectAcademia = document.getElementById("fk_id_academia");
    const selectInstrutor = document.getElementById("fk_id_instrutor");

    if (selectAcademia && selectInstrutor) {

        // Guarda todas as opções originais de instrutores
        const todasOpcoes = Array.from(selectInstrutor.options);

        // Captura o instrutor que já estava salvo no PHP
        const instrutorSalvo = selectInstrutor.getAttribute("data-selected");

        function filtrarInstrutores() {
            const academiaSelecionada = selectAcademia.value;

            // Limpa o select
            selectInstrutor.innerHTML = '';

            // Primeira opção padrão
            const opcPadrao = new Option("Selecione o Instrutor", "", true, true);
            opcPadrao.disabled = true;
            selectInstrutor.append(opcPadrao);

            if (!academiaSelecionada) {
                selectInstrutor.disabled = true;
                return;
            }

            // Reinsere apenas instrutores da academia
            todasOpcoes.forEach(function (opcao) {
                if (opcao.dataset.academia == academiaSelecionada) {
                    selectInstrutor.append(opcao);
                }
            });

            selectInstrutor.disabled = false;

            // ❗ Aqui garantimos que o instrutor salvo fica selecionado
            if (instrutorSalvo) {
                selectInstrutor.value = instrutorSalvo;
            }
        }

        // Executa ao trocar
        selectAcademia.addEventListener("change", filtrarInstrutores);

        // Executa ao carregar a página
        filtrarInstrutores();
    }
});