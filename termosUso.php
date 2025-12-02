<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Aliança Marcial</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>
    <main class="termos-container">
        <h1 class="tituloh1">Termos de Uso</h1>
        <p class="regras-versao">Versão 1.0 — Aliança Marcial</p>

        <section class="termos-bloco">
            <h3>1. Aceitação dos Termos</h3>
            <p>Ao acessar ou utilizar a plataforma Aliança Marcial, o usuário concorda integralmente com estes Termos de
                Uso e com a Política de Privacidade.</p>
        </section>

        <section class="termos-bloco">
            <h3>2. Finalidade da Plataforma</h3>
            <p>A Aliança Marcial conecta praticantes de artes marciais a oportunidades de apoio financeiro comunitário.
                A plataforma permite envio de pedidos, avaliação de elegibilidade, acesso a mentores e apresentação de
                projetos relacionados ao esporte.</p>
        </section>

        <section class="termos-bloco">
            <h3>3. Idade Mínima</h3>
            <p>O uso é permitido para maiores de 12 anos.<br>
                Usuários menores de 18 devem usar a plataforma com acompanhamento de um responsável.</p>
        </section>

        <section class="termos-bloco">
            <h3>4. Cadastro e Veracidade das Informações</h3>
            <p>O usuário se compromete a:</p>
            <ul>
                <li>Fornecer dados reais, completos e atualizados.</li>
                <li>Não enviar documentos falsificados, relatos fabricados ou informações enganosas.</li>
                <li>Não criar múltiplas contas para obter vantagem.</li>
            </ul>
            <p>Contas suspeitas podem ser suspensas ou excluídas.</p>
        </section>

        <section class="termos-bloco">
            <h3>5. Envio de Solicitação de Apoio</h3>
            <p>Para solicitar apoio, o usuário deverá preencher os campos obrigatórios definidos pela plataforma, como:
            </p>
            <ul>
                <li>Relato da necessidade</li>
                <li>Comprovante de treino ou participação</li>
                <li>Avaliação do professor ou treinador</li>
            </ul>
            <p>O envio da solicitação não garante aprovação.</p>
        </section>

        <section class="termos-bloco">
            <h3>6. Critérios de Seleção e Priorização</h3>
            <p>A plataforma irá avaliar:</p>
            <ul>
                <li>Renda e situação socioeconômica</li>
                <li>Histórico esportivo</li>
                <li>Potencial competitivo</li>
                <li>Regularidade de treino</li>
                <li>Disponibilidade de verba no mês</li>
            </ul>
            <p>A avaliação segue critérios internos e pode mudar a qualquer momento.</p>
        </section>

        <section class="termos-bloco">
            <h3>7. Responsabilidades do Usuário</h3>
            <p>É proibido:</p>
            <ul>
                <li>Usar a plataforma para golpes, fraudes ou assédio</li>
                <li>Enviar conteúdo ofensivo, discriminatório, ilegal ou violento</li>
                <li>Tentar acessar recursos não autorizados ou manipular o funcionamento do sistema</li>
            </ul>
            <p>Violação resultará em suspensão imediata.</p>
        </section>

        <section class="termos-bloco">
            <h3>8. Responsabilidades da Plataforma</h3>
            <p>A Aliança Marcial não garante:</p>
            <ul>
                <li>Concessão de apoio financeiro</li>
                <li>Participação em campeonatos</li>
                <li>Resultados esportivos</li>
                <li>Funcionamento contínuo da plataforma sem falhas técnicas</li>
            </ul>
            <p>A plataforma funciona “como está”, podendo sofrer atualizações, interrupções ou ajustes.</p>
        </section>

        <section class="termos-bloco">
            <h3>9. Pagamentos</h3>
            <p>Para apoiar atletas, o usuário poderá contribuir via planos e métodos disponíveis, pelo Pix por meio de
                provedores de pagamento terceirizados.</p>
        </section>

        <section class="termos-bloco">
            <h3>10. Cancelamento e Exclusão de Conta</h3>
            <p>O usuário pode solicitar exclusão da conta a qualquer momento.<br>
                A plataforma pode suspender ou remover contas que violem estes termos ou tentem fraudar o sistema.</p>
        </section>

        <section class="termos-bloco">
            <h3>11. Propriedade Intelectual</h3>
            <p>Todo o design, marca, textos, fluxos e funcionalidades pertencem à Aliança Marcial.<br>É proibida a
                reprodução sem autorização.</p>
        </section>

        <section class="termos-bloco">
            <h3>12. Atualizações dos Termos</h3>
            <p>A plataforma poderá atualizar este documento sempre que necessário.<br>
                Alterações relevantes serão comunicadas aos usuários.</p>
        </section>

        <section class="termos-bloco">
            <h3>13. Foro e Legislação Aplicável</h3>
            <p>Estes termos seguem as leis brasileiras.<br>
                Eventuais conflitos serão resolvidos no foro de Pimenta Bueno/RO.</p>
        </section>
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