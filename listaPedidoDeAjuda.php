<?php
$nivelPermitido = ['Administrador', 'Atleta'];
require_once 'validaUser.php';

$idUsuario = $_SESSION['user_id'];
$tipoUsuario = $_SESSION['tipo_usuario'];

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$PedidoAjuda = new PedidoAjuda();
$Atleta = new Atleta();
$atletas = $Atleta->all();

if ($tipoUsuario === 'Atleta') {

    // Busca o atleta pelo usuário logado
    $dadosAtleta = $Atleta->search("fk_id_usuario", $idUsuario);

    // 1. Se não existir → cadastro não finalizado
    if (!$dadosAtleta) {
        echo "<script>alert('Você ainda não finalizou seu cadastro como atleta.'); window.location.href='atleta.php';</script>";
        exit;
    }

    // 2. Se estiver aguardando validação
    if ($dadosAtleta->status_validacao === 'nao_validado') {
        echo "<script>alert('Seu cadastro de atleta ainda está pendente de aprovação.'); window.location.href='index.php';</script>";
        exit;
    }

    // 3. Se foi reprovado → informar motivo
    if ($dadosAtleta->status_validacao === 'invalido') {
        $motivo = $dadosAtleta->motivo_reprovacao ?? 'Sem motivo informado.';
        echo "<script>alert('Seu cadastro foi invalidado. Motivo: $motivo'); window.location.href='index.php';</script>";
        exit;
    }
}

if ($tipoUsuario === 'Atleta') {
    $pedidosAjuda = $PedidoAjuda->searchAll("fk_id_atleta", $dadosAtleta->id_atleta);
} else {
    // Administrador vê todos
    $pedidosAjuda = $PedidoAjuda->all();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Pedidos de Ajuda</title>
</head>

<body>
    <?php if ($tipoUsuario != 'Administrador'):
        require_once "_parts/_navSite.php";
    else:
        require_once "_parts/_navAdmin.php";
    endif;
    ?>

    <main class="container">
        <div class="mt-3">
            <h1 class="tituloh1">Pedidos de Ajuda</h1>
        </div>

        <?php if ($tipoUsuario != 'Administrador'): ?>
            <div class="mt-3">
                <a href="pedidoDeAjuda.php" class="btn btn-novo mb-3">Novo Pedido de Ajuda</a>
            </div>
        <?php endif; ?>

        <table class="table tabela text-center overflow-hidden table-hover align-middle dataTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Título</th>
                    <th>Atleta</th>
                    <th>Status</th>
                    <th>Meta</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($pedidosAjuda as $pedidoAjuda): ?>
                    <tr>
                        <td><?php echo $pedidoAjuda->id_pedido_ajuda ?></td>
                        <td><?php echo $pedidoAjuda->titulo ?></td>
                        <td><?php foreach ($atletas as $atleta) {
                            if ($atleta->id_atleta == $pedidoAjuda->fk_id_atleta) {
                                echo $atleta->nome_atleta;
                                break;
                            }
                        } ?></td>
                        <td>
                            <?= ($pedidoAjuda->status_validacao === 'aprovado') ? '<span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Aprovado
                                </span>' :
                                (($pedidoAjuda->expira_validacao != 0 && strtotime($pedidoAjuda->expira_validacao) < time()) ? '<span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Tempo de validação expirado </span>' : (($pedidoAjuda->status_validacao === 'reprovado') ? '<span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Reprovado </span>' : '<span class="badge bg-warning">
                                        <i class="bi bi-hourglass-split"></i> Pendente </span>')) ?>
                        </td>
                        <td>
                            <?php if ($pedidoAjuda->meta == "atingida" || $pedidoAjuda->valor_atingido >= $pedidoAjuda->valor_necessario): ?>
                                <?php $PedidoAjuda->metaAtingida($pedidoAjuda->id_pedido_ajuda) ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Atingida
                                </span>
                            <?php else: ?>
                                <?php if ($pedidoAjuda->status_validacao !== 'reprovado'): ?>
                                    <a href="pedidoDeAjuda.php?acao=marcar_atingida&id=<?= $pedidoAjuda->id_pedido_ajuda ?>"
                                        class="badge bg-warning text-decoration-none" title="Marcar como atingida"
                                        onclick="return confirm('Deseja marcar a meta como Atingida?');">
                                        <i class="bi bi-hourglass-split"></i> Pendente
                                    </a>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="bi bi-x-circle"></i> Não Atingida
                                    </span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-1 justify-content-center">
                                <?php if ($pedidoAjuda->meta == "atingida" || $pedidoAjuda->valor_atingido >= $pedidoAjuda->valor_necessario || $pedidoAjuda->status_validacao === 'reprovado'): ?>
                                    <button class="btn btn-primary btn-sm" disabled><i class="bi bi-pencil-square"></i></button>
                                <?php else: ?>
                                    <form action="<?php echo htmlspecialchars("pedidoDeAjuda.php") ?>" method="post"
                                        class="d-flex">
                                        <input type="hidden" name="id" value="<?php echo $pedidoAjuda->id_pedido_ajuda ?>">
                                        <button name="btnEditar" class="btn btn-primary btn-sm" type="submit" title="Editar"
                                            onclick="return confirm('Tem certeza que deseja editar o pedido de ajuda?');">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <form action="<?php echo htmlspecialchars("pedidoDeAjuda.php") ?>" method="post"
                                    class="d-flex">
                                    <input type="hidden" name="id" value="<?php echo $pedidoAjuda->id_pedido_ajuda ?>">
                                    <button name="btnDeletar" class="btn btn-danger btn-sm" type="submit" title="Deletar"
                                        onclick="return confirm('Tem certeza que deseja deletar o pedido de ajuda?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <!-- link jquery -->
    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js" type="text/javascript"></script>
    <!-- link datatable JS -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <!-- link datatable JS com bootstrap5 -->
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- paginacao.js -->
    <script src="JS/paginacao.js"></script>

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