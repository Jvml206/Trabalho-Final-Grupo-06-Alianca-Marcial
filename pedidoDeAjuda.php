<?php
$nivelPermitido = ['Administrador', 'Atleta'];
require_once 'validaUser.php';

spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$PedidoAjuda = new PedidoAjuda();
$Atleta = new Atleta();

$idUsuario = $_SESSION['user_id'];
$tipoUsuario = $_SESSION['tipo_usuario'];

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $fotoAntiga = filter_input(INPUT_POST, 'imagemAntiga');
    $PedidoAjuda->setImagem($fotoAntiga);

    $PedidoAjuda->setTitulo(filter_input(INPUT_POST, "titulo", FILTER_SANITIZE_STRING));
    $PedidoAjuda->setDescricao(filter_input(INPUT_POST, "descricao", FILTER_SANITIZE_STRING));
    $PedidoAjuda->setValorNecessario(filter_input(INPUT_POST, "valor_necessario", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
    $PedidoAjuda->setValorAtingido(filter_input(INPUT_POST, "valor_atingido", FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION));
    $PedidoAjuda->setPix(filter_input(INPUT_POST, "pix", FILTER_SANITIZE_STRING));
    $PedidoAjuda->setStatusValidacao('pendente');

    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_NUMBER_INT);

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $permitidas)) {
            $nomeFoto = uniqid("pedidoDeAjuda_") . "." . $extensao;
            $destino = "Images/pedidoDeAjuda/" . $nomeFoto;
            $caminhoAntigo = "Images/pedidoDeAjuda/" . $fotoAntiga;

            if (!empty($fotoAntiga) && is_file($caminhoAntigo)) {
                unlink($caminhoAntigo);
            }

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                $PedidoAjuda->setImagem($nomeFoto);
            }
        }
    }

    if ($tipoUsuario === 'Administrador') {
        $PedidoAjuda->setFkIdAtleta(filter_input(INPUT_POST, "fk_id_atleta", FILTER_SANITIZE_NUMBER_INT));
        $idAtleta = filter_input(INPUT_POST, "fk_id_atleta", FILTER_SANITIZE_NUMBER_INT);
    } else {
        $dadosAtleta = $Atleta->search("fk_id_usuario", $idUsuario);
        $PedidoAjuda->setFkIdAtleta($dadosAtleta->id_atleta);
        $idAtleta = $dadosAtleta->id_atleta;
    }

    if (empty($id)):
        if ($tipoUsuario === 'Administrador') {
            $idAtleta = filter_input(INPUT_POST, "fk_id_atleta", FILTER_SANITIZE_NUMBER_INT);
            $idPedido = $PedidoAjuda->add();
            $mensagem = "Há um novo pedido de validação pendente.";
            $assunto = "Validação de Pedido de Ajuda";
            $enviado = $PedidoAjuda->validacaoPedido($idAtleta, $mensagem, $assunto, $idPedido);
        } else {
            $dadosAtleta = $Atleta->search("fk_id_usuario", $idUsuario);
            $idAtleta = $dadosAtleta->id_atleta;
            $idPedido = $PedidoAjuda->add();
            $mensagem = "Há um novo pedido de validação pendente.";
            $assunto = "Validação de Pedido de Ajuda";
            $enviado = $PedidoAjuda->validacaoPedido($idAtleta, $mensagem, $assunto, $idPedido);
        }

        if ($enviado) {
            echo "<script>window.alert('Cadastro de pedido de ajuda realizado com sucesso, um email para validação do pedido foi enviado para o instrutor, ele tem até 72 horas para validar o pedido.');window.location.href='index.php';window.location.href='pedidoDeAjuda.php';</script>";
        } else {
            echo "<script>window.alert('Erro ao cadastrar o pedido de ajuda ou ao enviar o pedido de validação para o instrutor.');window.open(document.referrer,'_self');</script>";
        }
    else:
        $pedidoAntigo = $PedidoAjuda->search("id_pedido_ajuda", $id);
        $statusAntigo = $pedidoAntigo->status_validacao;

        if ($PedidoAjuda->update('id_pedido_ajuda', $id)) {
            $novoValorNecessario = filter_input(INPUT_POST, 'valor_necessario');
            $novoValorAtingido = filter_input(INPUT_POST, 'valor_atingido');
            $novoTitulo = filter_input(INPUT_POST, 'titulo');
            $novaDescricao = filter_input(INPUT_POST, 'descricao');
            $novoPix = filter_input(INPUT_POST, 'pix');
            $imagemAntiga = filter_input(INPUT_POST, 'imagemAntiga');
            
            $somenteValorMudou =
            ($pedidoAntigo->valor_atingido != $novoValorAtingido) &&
                ($pedidoAntigo->valor_necessario == $novoValorNecessario) &&
                ($pedidoAntigo->titulo == $novoTitulo) &&
                ($pedidoAntigo->descricao == $novaDescricao) &&
                ($pedidoAntigo->pix == $novoPix);

            if ($tipoUsuario === 'Administrador') {
                $usuarioAtual = $PedidoAjuda->search("fk_id_atleta", (filter_input(INPUT_POST, "fk_id_atleta", FILTER_SANITIZE_NUMBER_INT)));
            } else {
                $dadosAtleta = $Atleta->search("fk_id_usuario", $idUsuario);
                $usuarioAtual = $PedidoAjuda->search("fk_id_atleta", $dadosAtleta->id_atleta);
            }

            $PedidoAjuda->setStatusValidacao($pedidoAntigo->status_validacao);
            if (!$somenteValorMudou) {
                $PedidoAjuda->setStatusValidacao('pendente');
                $mensagem = "Há um novo pedido de validação pendente.";
                $assunto = "Validação de Pedido de Ajuda";
                $enviado = $PedidoAjuda->validacaoPedido($usuarioAtual->fk_id_atleta, $mensagem, $assunto, $id);
            }

            if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
                $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($extensao, $permitidas)) {
                    $nomeFoto = uniqid("usuario_") . "." . $extensao;
                    $destino = "Images/usuario/" . $nomeFoto;
                    $caminhoAntigo = "Images/usuario/" . $imagemAntiga;

                    if (!empty($imagemAntiga) && is_file($caminhoAntigo)) {
                        unlink($caminhoAntigo);
                    }

                    if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                        $PedidoAjuda->setImagem($nomeFoto);
                    }
                }
            }

            $PedidoAjuda->update('id_pedido_ajuda', $id);

            if(!$somenteValorMudou) {
            echo "<script>window.alert('Pedido alterado com sucesso, o instrutor foi notificado para validar o pedido.');window.location.href='listaPedidoDeAjuda.php';</script>";
            exit;
            }else{
                echo "<script>window.alert('Valor atingido alterado com sucesso.');window.location.href='listaPedidoDeAjuda.php';</script>";
            }
        } else {
            echo "<script> window.alert('Erro ao alterar o pedido de ajuda ou ao enviar a notificação para o instrutor.');
        window.open(document.referrer, '_self'); </script>";
        }
    endif;

elseif (filter_has_var(INPUT_POST, "btnDeletar")):
    $id_pedido_ajuda = intval(filter_input(INPUT_POST, "id"));
    $delPedidoAjuda = $PedidoAjuda->search("id_pedido_ajuda", $id_pedido_ajuda);

    $fotoApagar = "Images/pedidoDeAjuda/" . $delPedidoAjuda->imagem;
    if (!empty($delPedidoAjuda->imagem) && is_file($fotoApagar)) {
        unlink($fotoApagar);
    }

    if ($PedidoAjuda->delete("id_pedido_ajuda", $id_pedido_ajuda)) {
        header("location:listaPedidoDeAjuda.php");
    } else {
        echo "<script>alert('Erro ao excluir o pedido de ajuda.'); window.open(document.referrer, '_self');</script>";
    }
elseif (filter_has_var(INPUT_GET, "acao")):
    $id = intval(filter_input(INPUT_GET, "id"));
    $acao = filter_input(INPUT_GET, "acao", FILTER_SANITIZE_STRING);

    $status = ($acao == "marcar_atingida") ? "atingida" : "pendente";
    $PedidoAjuda->statusMeta($id, $status);
    header("location:listaPedidoDeAjuda.php");
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastro de Pedido de Ajuda</title>
</head>

<body>
    <?php if ($tipoUsuario != 'Administrador'):
        require_once "_parts/_navSite.php";
    else:
        require_once "_parts/_navAdmin.php";
    endif; ?>

    <main class="container">
        <?php
        if (filter_has_var(INPUT_POST, "btnEditar")) {
            $id = intval(filter_input(INPUT_POST, "id"));
            $pedidoAjuda = $PedidoAjuda->search("id_pedido_ajuda", $id);
        }
        ?>

        <h2 class="text-center">Cadastro de Pedido de Ajuda</h2>

        <form action="pedidoDeAjuda.php" method="post" class="row g3 mt-3" enctype="multipart/form-data">

            <input type="hidden" value="<?php echo $pedidoAjuda->id_pedido_ajuda ?? null; ?>" name="id">
            <input type="hidden" name="imagemAntiga" value="<?php echo $pedidoAjuda->imagem ?? ''; ?>">

            <?php if ($tipoUsuario === 'Administrador'): ?>
                <div class="mb-3">
                    <label for="fk_id_atleta" class="form-label">Nome do Atleta</label>
                    <select name="fk_id_atleta" class="form-select" id="fk_id_atleta" required>
                        <option disabled <?= (!isset($dadosAtleta->id_atleta)) ? 'selected' : '' ?>>
                            Selecione o Atleta
                        </option>
                        <?php
                        $listaAtletas = $Atleta->all();
                        foreach ($listaAtletas as $at):
                            $nome = htmlspecialchars($at->nome_atleta);
                            $selected = ($pedidoAjuda->fk_id_atleta ?? null) == $at->id_atleta ? 'selected' : '';
                            ?>
                            <option value="<?= $at->id_atleta ?>" <?= $selected ?>>
                                <?= $nome ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>

            <div class="col-md-12">
                <label for="titulo" class="form-label">Título do pedido</label>
                <input type="text" name="titulo" id="titulo" placeholder="Digite o Título do Pedido" required
                    class="form-control" value="<?php echo $pedidoAjuda->titulo ?? null; ?>">
            </div>

            <div class="col-md-12">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" placeholder="Digite a Descrição do Pedido" required
                    class="form-control"><?php echo $pedidoAjuda->descricao ?? null; ?></textarea>
            </div>

            <div class="col-md-3">
                <label for="valor_necessario" class="form-label">Valor de ajuda necessário</label>
                <input type="text" name="valor_necessario" id="valor_necessario"
                    placeholder="Digite o valor necessário. Em R$" required class="form-control"
                    value="<?php echo $pedidoAjuda->valor_necessario ?? null; ?>">
            </div>

            <div class="col-md-3">
                <label for="valor_atingido" class="form-label">Valor de ajuda atingido</label>
                <input type="text" name="valor_atingido" id="valor_atingido"
                    placeholder="Digite o valor atingido. Em R$" required class="form-control"
                    value="<?php echo $pedidoAjuda->valor_atingido ?? null; ?>">
            </div>

            <div class="col-md-6">
                <label for="pix" class="form-label">Pix para realizar a ajuda</label>
                <input type="text" name="pix" id="pix" placeholder="Digite o Pix" required class="form-control"
                    value="<?php echo $pedidoAjuda->pix ?? null; ?>">
            </div>

            <div class="col-md-6">
                <label for="imagem" class="form-label">Foto</label>
                <input type="file" name="imagem" id="imagem" accept="image/*" class="form-control" <?php echo empty($pedidoAjuda->imagem) ? 'required' : null ?>>
            </div>

            <div class="col-md-6">
                <?php if (!empty($pedidoAjuda->imagem)): ?>
                    <img src="Images/pedidoDeAjuda/<?php echo $pedidoAjuda->imagem; ?>" alt="Foto do Pedido de Ajuda"
                        class="mt-2">
                <?php endif; ?>
            </div>

            <div class="col-12 mt-3 d-flex gap-2">
                <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn btn-marrom">Salvar</button>
                <a href="listaPedidoDeAjuda.php" class="btn btn-outline-danger">Voltar</a>
            </div>
        </form>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $('#valor_necessario').mask('000000000000000000000,00', { reverse: true });
        $('#valor_atingido').mask('000000000000000000000,00', { reverse: true });
        $('form').on('submit', function () {
            $('#valor_necessario').val($('#valor_necessario').val().replace(',', '.'));
            $('#valor_atingido').val($('#valor_atingido').val().replace(',', '.'));
        });
    </script>
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