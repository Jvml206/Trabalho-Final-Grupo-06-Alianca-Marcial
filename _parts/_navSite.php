<nav class="navbar navbar-expand-lg nav-custom" data-bs-theme="dark">
    <div class="container-fluid">

        <header class="d-flex flex-column align-items-center">
            <img src="Images/logo.png" alt="Logo Aliança Marcial" class="logoNav">
        </header>

        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-2"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Início</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pedidosDeAjuda.php">Ajudar</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sobre.php">Sobre</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="apoiadores.php">Apoiadores</a>
                </li>
                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['user_name']) && isset($_SESSION['tipo_usuario'])):
                    $tipoUsuario = $_SESSION['tipo_usuario'];
                    $idUsuario = $_SESSION['user_id'];
                    spl_autoload_register(function ($class) {
                        require_once "Classes/{$class}.class.php";
                    });
                    $Atleta = new Atleta();
                    ?>
                    <?php if ($tipoUsuario === 'Atleta' && $Atleta->verificarPorUsuario($idUsuario) === True): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="listaPedidoDeAjuda.php">Ajuda</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav">
                <?php
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($_SESSION['user_name']) && isset($_SESSION['tipo_usuario'])):
                    $tipoUsuario = $_SESSION['tipo_usuario'];
                    $idUsuario = $_SESSION['user_id'];
                    $nomeUsuario = htmlspecialchars($_SESSION['user_name']);
                    $idUsuario = $_SESSION['user_id'];
                    spl_autoload_register(function ($class) {
                        require_once "Classes/{$class}.class.php";
                    });
                    $Instrutor = new Instrutor();
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $nomeUsuario; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

                            <?php if ($tipoUsuario === 'Administrador'): ?>
                                <li><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                            <?php elseif ($tipoUsuario === 'Instrutor'): ?>
                                <li><a class="nav-link" href="instrutor.php">Meus Dados</a></li>
                            <?php elseif ($tipoUsuario === 'Usuário'): ?>
                                <li><a class="nav-link" href="conta.php">Meus Dados</a></li>
                            <?php elseif ($tipoUsuario === 'Atleta'): ?>
                                <li><a class="nav-link" href="atleta.php">Meus Dados</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="nav-link sair" href="logout.php">Sair</a></li>
                        </ul>
                    </li>

                <?php else: ?>
                    <!-- Usuário não logado -->
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="bi bi-person-circle"></i> Entrar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>