$(document).ready(function () {
    $('.dataTable').dataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
        },
        pageLength:10,
        lengthMenu:[5,10,25,50,100]
    });
});