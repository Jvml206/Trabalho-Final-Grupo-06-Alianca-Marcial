<?php $nivelPermitido = ['Administrador'];
require_once 'validaUser.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Usuários</title>
</head>

<body>
    <?php require_once "_parts/_navAdmin.php"; ?>

    <main class="container">
        <div class="mt-3">
            <h1 class="tituloh1">Usuários</h1>
        </div>
        <div class="mt-3">
            <a href="usuario.php" class="btn btn-novo mb-3">Novo Usuário</a>
        </div>
        <table class="table tabela text-center overflow-hidden table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Função</th>
                    <th>Validação</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>

            <body>
                <?php
                spl_autoload_register(function ($class) {
                    require_once "Classes/{$class}.class.php";
                });

                $u = new Usuario();
                $usuarios = $u->all();
                foreach ($usuarios as $usuario):
                    ?>
                    <tr>
                        <td><?php echo $usuario->id_usuario ?></td>
                        <td><?php echo $usuario->email ?></td>
                        <td><?php echo $usuario->tipo_usuario ?></td>
                        <td><?php if ($usuario->status_validacao == "valido"): ?>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Válida
                                </span>
                            <?php else: ?>
                                <?php if ($usuario->status_validacao !== 'invalido'): ?>
                                    <a href="usuario.php?acao=marcar_valida&id=<?= $usuario->id_usuario ?>"
                                        class="badge bg-success text-decoration-none" title="Marcar como válida"
                                        onclick="return confirm('Deseja marcar a conta como válida?');">
                                        <i class="bi bi-check-circle"></i> Validar
                                    </a>
                                    <a href="usuario.php?acao=marcar_invalida&id=<?= $usuario->id_usuario ?>"
                                        class="badge bg-danger text-decoration-none" title="Marcar como inválida"
                                        onclick="return confirm('Deseja marcar a conta como inválida?');">
                                        <i class="bi bi-x-circle"></i> Invalidar
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex gap-1 justify-content-center">
                                <form action="<?php echo htmlspecialchars("usuario.php") ?>" method="post" class="d-flex">
                                    <input type="hidden" name="id" value="<?php echo $usuario->id_usuario ?>">
                                    <button name="btnEditar" class="btn btn-primary btn-sm" type="submit" title="Editar"
                                        onclick="return confirm('Tem certeza que deseja editar o usuário?');">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </form>
                                <form action="<?php echo htmlspecialchars("usuario.php") ?>" method="post" class="d-flex">
                                    <input type="hidden" name="id_usuario" value="<?php echo $usuario->id_usuario ?>">
                                    <button name="btnDeletar" class="btn btn-danger btn-sm" type="submit" title="Deletar"
                                        onclick="return confirm('Tem certeza que deseja deletar o usuário?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </body>
        </table>
    </main>

    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
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