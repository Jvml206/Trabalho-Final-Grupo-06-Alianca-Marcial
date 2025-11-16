<nav class="navbar navbar-expand-lg nav-custom" data-bs-theme="dark">
    <div class="container-fluid">

        <img src="Images/logo.png" alt="Logo Aliança Marcial" class="logoNav">

        <div class="ms-auto">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaInstituicoes.php">Instituição Apoiadora</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaAcademia.php">Academias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaInstrutor.php">Instrutores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaAtleta.php">Atletas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaCampeonato.php">Campeonatos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaUsuario.php">Usuários</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="listaPedidoAjuda.php">Ajuda</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <?php

                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }
                    if (isset($_SESSION['user_name'])): ?>
                        <!-- Usuário Logado -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

                                <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                                <li><a class="dropdown-item text-danger" href="logout.php">Sair</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <!-- Usuário Não Logado -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><i class="bi bi-person-circle"></i> Entrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</nav>