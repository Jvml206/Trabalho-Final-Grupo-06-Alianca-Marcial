<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Aliança Marcial</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>
    <main class="mainSobre">
        <h1 class="tituloh1">Sobre Nós</h1>

        <div class="porque">
            <h3 class="porqueTitulo">Por que o site existe?</h3>
            <p class="porqueTexto">
                A plataforma Aliança Marcial foi criada para diminuir a desigualdade dentro das artes marciais. Muitas
                pessoas talentosas são obrigadas a parar de treinar ou não participam de campeonatos por falta de
                dinheiro, falta de apoio ou falta de oportunidade. O site existe para conectar atletas que precisam de
                ajuda com pessoas dispostas a apoiar.
            </p>
        </div>
        <div class="proposito">
            <h3 class="propositoTitulo">Qual é o propósito do site?</h3>
            <p class="propositoTexto">
                O propósito é simples: transformar mensalidades acessíveis em apoio real para atletas. Pequenas
                contribuições de vários membros viram um fundo coletivo que paga treinos, equipamentos e despesas
                esportivas. O objetivo é manter atletas treinando e evoluindo.
            </p>
        </div>
        <div class="finalidade">
            <h3 class="finalidadeTitulo">Qual é a finalidade do projeto?</h3>
            <ul class="listaFinalidade">
                <li class="listaFinalidadeItem">Conectar atletas que precisam de ajuda com pessoas dispostas a apoiar;
                </li>
                <li class="listaFinalidadeItem">Ajudar atletas a continuar no esporte;</li>
                <li class="listaFinalidadeItem">Fornecer uma plataforma segura e transparente para arrecadação de
                    fundos;</li>
                <li class="listaFinalidadeItem">Incentivar a prática esportiva e o desenvolvimento de talentos nas artes
                    marciais;</li>
                <li class="listaFinalidadeItem">Criar uma comunidade unida por disciplina e evolução;</li>
                <li class="listaFinalidadeItem">Promover a inclusão social através do esporte;</li>
                <li class="listaFinalidadeItem">Permitir que qualquer pessoa, mesmo com pouco dinheiro, faça parte do
                    impacto.</li>
            </ul>
        </div>
        <div class="ajuda">
            <h3 class="ajudaTitulo">Como uma pessoa pode ajudar?</h3>
            <p class="ajudaSub">Existem duas formas:</p>
            <ol type="1">
                <li class="listaAjudaNumero">Se tornando um cooperado (R$20)</li>
                <ul type="disc">
                    <li class="listaAjudaNumeroItem">O valor vai direto para o fundo de apoio aos atletas.</li>
                </ul>
                <li class="listaAjudaNumero">Divulgando o projeto</li>
                <ul type="disc">
                    <li type="" class="listaAjudaNumeroItem">Compartilhar o site, indicar atletas e trazer membros ajuda a
                        aumentar o alcance e o número de apoiados.</li>
                </ul>
            </ol>
        </div>
        <div class="pagamento">
            <h3 class="pagamentoTitulo">Como funciona o pagamento?</h3>
            <p class="pagamentoSub">Pagamentos podem ser feitos via:</p>
            <ol type="1">
                <li class="listaPagamentoNumero">Pix (rápido e sem taxas)</li>
                <ul type="disc">
                    <li class="listaPagamentoNumeroItem">Após o pagamento, o sistema registra a assinatura e o usuário passa
                        a contribuir automaticamente para o fundo mensal que apoia atletas.</li>
                </ul>
            </ol>
        </div>
    </main>
    <footer>
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Botão do VLibras -->
    <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>

    <!-- Script do VLibras -->
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
        new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>

</html>