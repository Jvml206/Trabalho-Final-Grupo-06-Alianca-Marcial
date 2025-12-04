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
        <h1 class="tituloh1">Política de Privacidade – Aliança Marcial</h1>
        <p class="regras-versao">Versão 1.0 — 30/11/2025</p>

        <section class="termos-bloco">
            <h3>1. Introdução</h3>
            <p>
                A Plataforma Aliança Marcial valoriza a privacidade e a proteção dos dados pessoais dos usuários.
                Esta Política de Privacidade explica:
            </p>
            <ul>
                <li>Quais dados são coletados</li>
                <li>Como são utilizados</li>
                <li>Com quem podem ser compartilhados</li>
                <li>Como são protegidos</li>
                <li>Quais direitos o usuário possui segundo a LGPD</li>
            </ul>
            <p>
                Ao utilizar a plataforma, o usuário declara estar ciente e de acordo com os termos abaixo.
            </p>
        </section>

        <section class="termos-bloco">
            <h3>2. Dados Pessoais Coletados</h3>

            <h4>2.1. Dados informados pelo usuário</h4>
            <ul>
                <li>Nome completo</li>
                <li>E-mail e telefone</li>
                <li>Foto do usuário</li>
            </ul>

            <h4>2.2. Dados coletados automaticamente</h4>
            <ul>
                <li>Endereço IP</li>
                <li>Data/hora de acesso</li>
                <li>Informações de dispositivo e navegador</li>
                <li>Cookies essenciais ao funcionamento</li>
            </ul>

            <h4>2.3. Dados de pagamento (cooperados)</h4>
            <ul>
                <li>Identificação da transação</li>
                <li>Comprovantes de pagamento (via PIX)</li>
            </ul>

            <p>A plataforma não armazena senhas de banco ou dados sensíveis de meios de pagamento.</p>
        </section>

        <section class="termos-bloco">
            <h3>3. Finalidades do Tratamento dos Dados</h3>
            <ul>
                <li>Cadastro e autenticação do usuário</li>
                <li>Validação esportiva e socioeconômica</li>
                <li>Solicitação e concessão de apoio financeiro</li>
                <li>Acompanhamento de treino e desempenho</li>
                <li>Relatórios para avaliação dos professores</li>
                <li>Pagamentos e auditorias</li>
                <li>Prevenção a fraudes</li>
                <li>Cumprimento de obrigações legais</li>
            </ul>
        </section>

        <section class="termos-bloco">
            <h3>4. Bases Legais Utilizadas (LGPD)</h3>
            <ul>
                <li>Execução de contrato</li>
                <li>Consentimento</li>
                <li>Legítimo interesse</li>
                <li>Cumprimento de obrigação legal</li>
                <li>Proteção do crédito</li>
            </ul>
        </section>

        <section class="termos-bloco">
            <h3>5. Compartilhamento de Dados</h3>
            <p>Os dados não são vendidos nem repassados a terceiros.</p>
            <p>Podem ser compartilhados com:</p>
            <ul>
                <li>Equipe administrativa interna</li>
                <li>Professores/treinadores</li>
                <li>Autoridades competentes</li>
                <li>Serviços técnicos (servidor, autenticação)</li>
            </ul>
        </section>

        <section class="termos-bloco">
            <h3>6. Armazenamento e Segurança da Informação</h3>
            <ul>
                <li>Criptografia de senhas</li>
                <li>Controle de acesso</li>
                <li>Logs de autenticação</li>
                <li>Monitoramento de atividades suspeitas</li>
                <li>Servidores seguros</li>
            </ul>
        </section>

        <section class="termos-bloco">
            <h3>7. Retenção e Exclusão dos Dados</h3>
            <p>Os dados são mantidos enquanto a conta estiver ativa ou para fins legais.</p>
            <p>O usuário pode solicitar:</p>
            <ul>
                <li>Exclusão dos dados</li>
                <li>Correção de informações</li>
            </ul>
        </section>

        <section class="termos-bloco">
            <h3>8. Direitos do Usuário (LGPD)</h3>
            <ul>
                <li>Acessar seus dados</li>
                <li>Corrigir informações</li>
                <li>Solicitar anonimização ou eliminação</li>
                <li>Revogar consentimento</li>
                <li>Consultar compartilhamentos</li>
            </ul>
        </section>

        <section class="termos-bloco">
            <h3>9. Uso de Cookies</h3>
            <p>Utilizamos apenas cookies essenciais:</p>
            <ul>
                <li>Autenticação</li>
                <li>Manutenção de sessão</li>
                <li>Segurança</li>
            </ul>
        </section>

        <section class="termos-bloco">
            <h3>10. Alterações da Política</h3>
            <p>A política poderá ser atualizada a qualquer momento. Mudanças importantes serão avisadas aos usuários.
            </p>
        </section>

        <section class="termos-bloco">
            <h3>11. Contato do Encarregado (DPO)</h3>
            <p>E-mail: <strong>cooperativaaliancamarcial@gmail.com</strong></p>
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