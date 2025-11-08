// Validação de datas: data_fim não pode ser antes da data_inicio
document.addEventListener("DOMContentLoaded", function () {
    const dataInicio = document.getElementById("data_inicio");
    const dataFim = document.getElementById("data_fim");

    function validarDatas() {
        if (dataInicio.value && dataFim.value && dataFim.value < dataInicio.value) {
            alert("A data de fim não pode ser anterior à data de início!");
            dataFim.value = "";
        }
    }

    if (dataInicio && dataFim) {
        dataInicio.addEventListener("change", validarDatas);
        dataFim.addEventListener("change", validarDatas);
    }
});