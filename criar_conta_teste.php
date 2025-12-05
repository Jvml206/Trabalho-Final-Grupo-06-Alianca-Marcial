<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


spl_autoload_register(function ($class) {
    require_once "Classes/{$class}.class.php";
});

$Usuario = new Usuario();
$Academia = new Academia();
$Atleta = new Atleta();
$Instrutor = new Instrutor();
$usuario = $Usuario->all();
$instrutor = $Instrutor->all();
$atleta = $Atleta->all();
$academia = $Academia->all();

if (filter_has_var(INPUT_POST, "btnCadastrar")):

    $Usuario->setNomeUsuario(filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_STRING));
    $Usuario->setEmail(filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL));
    $Usuario->setSenha('senha');
    $Usuario->setTipoUsuario(filter_input(INPUT_POST, "tipo_usuario", FILTER_SANITIZE_STRING));
    
    $Atleta->setDataNascimento(filter_input(INPUT_POST, "data_nascimento", FILTER_SANITIZE_STRING));
    $Atleta->setBiografia(filter_input(INPUT_POST, "biografia", FILTER_SANITIZE_STRING));
    $Atleta->setSexo(filter_input(INPUT_POST, "sexo", FILTER_SANITIZE_STRING));
    $Atleta->setEsporte(filter_input(INPUT_POST, "esporte", FILTER_SANITIZE_STRING));
    $Atleta->setPeso(filter_input(INPUT_POST, "peso"));
    $Atleta->setCategoria(filter_input(INPUT_POST, "categoria", FILTER_SANITIZE_STRING));
    
    $id_usuario = filter_input(INPUT_POST, "id_usuario", FILTER_SANITIZE_NUMBER_INT);
    $nomeUsuario = filter_input(INPUT_POST, "nome_usuario", FILTER_SANITIZE_STRING);
    
    $Atleta->setFkIdUsuario($id_usuario);
    $Atleta->setFkIdAcademia(filter_input(INPUT_POST, "fk_id_academia", FILTER_SANITIZE_NUMBER_INT));
    $Atleta->setFkIdInstrutor(filter_input(INPUT_POST, "fk_id_instrutor", FILTER_SANITIZE_NUMBER_INT));
    $Atleta->setNomeAtleta($nomeUsuario);


    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extensao, $permitidas)) {
            $nomeFoto = uniqid("usuario_") . "." . $extensao;
            $destino = "Images/usuario/" . $nomeFoto;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                $Usuario->setFoto($nomeFoto);
            }
        }
    }

    if (empty($id_usuario)):
        if ($Usuario->add()) {
            // Monta mensagem personalizada
            $mensagem = "<p>{$Usuario->getNomeUsuario()},</p>
            <p>Seu cadastro foi realizado com sucesso! 🎉</p>
            <p>Antes de acessar sua conta, é necessário criar uma senha de acesso.</p>";

            // Envia o e-mail de recuperação/criação de senha
            $Usuario->solicitarRecuperacaoSenha(
                $Usuario->getEmail(),
                $mensagem,
                'Bem-vindo ao Sistema da Cooperativa Aliança Marcial'
            );

            echo "<script>alert('Cadastro de usuário realizado com sucesso! Um e-mail para definição de senha foi enviado para o endereço cadastrado.');window.location.href='usuario.php';</script>";
        } else {
            echo "<script>alert('Erro ao cadastrar o usuário.');window.open(document.referrer,'_self');</script>";
        }
    endif;
endif;
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/baseSite.css?v=<?php echo time(); ?>">
    <link rel="icon" href="Images/logo.png">
    <title>Cadastre-se</title>
</head>

<body>
    <?php require_once "_parts/_navSite.php"; ?>

    <main class="container cadastro">
        <h1 class="tituloh1">Cadastre-se</h1>

        <form action="criar_conta_teste.php" method="post" class="row g-3 mt-3" enctype="multipart/form-data"
            id="form_valida_email">
            <div class="cadUsuario">
                <div class="dadosUsuario">
                    <div>
                        <label for="nome_usuario" class="form-label tituloDado">Nome do Usuário</label>
                        <input type="text" name="nome_usuario" id="nome_usuario" placeholder="Digite o Nome do Usuário"
                            required class="form-control">
                    </div>

                    <div class="usuario">
                        <label for="email" class="form-label tituloDado">Email</label>
                        <input type="email" name="email" id="email" placeholder="Digite o Email do Usuário" required
                            class="form-control">
                    </div>

                    <div class="usuario">
                        <label for="confirmaEmail" class="form-label tituloDado">Confirme o Email</label>
                        <input type="email" name="confirmaEmail" id="confirmaEmail"
                            placeholder="Digite a confirmação do E-mail" required class="form-control">
                        <div id="mensagem" class="alert alert-danger mt-2 mb-3"></div>
                    </div>

                    <div class="usuario">
                        <label for="tipo_usuario" class="form-label tituloDado">Tipo de Usuário</label>
                        <select id="tipo_usuario" name="tipo_usuario" class="form-select"
                            aria-label="Selecione o Tipo de Usuário" required>
                            <option selected disabled>Selecione o Tipo de Usuário</option>
                            <option value="Atleta">Atleta</option>
                            <option value="Instrutor">Instrutor</option>
                            <option value="Usuário">Usuário</option>
                        </select>
                    </div>
                </div>

                <div class="fotoCadUsuario">
                    <label for="foto" class="form-label tituloDado">Foto</label>
                    <input type="file" name="foto" id="foto" accept="image/*" class="form-control" required>
                    <img src="Images/usuario/SemFoto.png" alt="Foto do Usuário" class="mt-2 foto-usuario-cadastro"
                        id="fotoColocada">
                </div>
            </div>

            <!-- ====== FORMULÁRIO DE ATLETA (Fora do form principal, escondido inicialmente) ====== -->
            <div id="formAtleta" style="display:none; margin-top:20px;">

                <div class="col-md-6">
                    <label for="fk_id_academia" class="form-label tituloDado">Academia</label>
                    <select name="fk_id_academia" class="form-select" id="fk_id_academia" required>
                        <option value="" disabled <?= (!isset($dadosAtleta->fk_id_academia)) ? 'selected' : '' ?>>
                            Selecione a
                            Academia
                        </option>
                        <?php foreach ($academia as $ac): ?>
                            <option value="<?= $ac->id_academia ?>" <?= (($dadosAtleta->fk_id_academia ?? null) == $ac->id_academia) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($ac->nome_fantasia) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="fk_id_instrutor" class="form-label tituloDado">Instrutor</label>
                    <select name="fk_id_instrutor" class="form-select" id="fk_id_instrutor" required disabled
                        data-selected="<?= $dadosAtleta->fk_id_instrutor ?? '' ?>">
                        <option value="" disabled <?= (!isset($dadosAtleta->fk_id_instrutor)) ? 'selected' : '' ?>>
                            Selecione o Instrutor</option>
                        <?php foreach ($instrutor as $ins): ?>
                            <option value="<?= $ins->id_instrutor ?>" data-academia="<?= $ins->fk_id_academia ?>">
                                <?= htmlspecialchars($ins->nome_instrutor) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="esporte" class="form-label tituloDado">Esporte</label>
                    <select id="esporte" name="esporte" class="form-select" aria-label="Default select example">
                        <option disabled <?= (!isset($dadosAtleta->esporte)) ? 'selected' : '' ?>>Selecione o Esporte
                        </option>
                        <option value="Aikidô" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Aikidô') ? 'selected' : '' ?>>Aikidô</option>
                        <option value="Boxe" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Boxe') ? 'selected' : '' ?>>
                            Boxe</option>
                        <option value="Capoeira" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Capoeira') ? 'selected' : '' ?>>Capoeira</option>
                        <option value="Jiu-Jitsu Brasileiro" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Jiu-Jitsu Brasileiro') ? 'selected' : '' ?>>Jiu-Jitsu Brasileiro
                        </option>
                        <option value="Judô" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Judô') ? 'selected' : '' ?>>
                            Judô</option>
                        <option value="Karatê" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Karatê') ? 'selected' : '' ?>>Karatê</option>
                        <option value="Kung Fu" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Kung Fu') ? 'selected' : '' ?>>Kung Fu</option>
                        <option value="MMA" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'MMA') ? 'selected' : '' ?>>MMA
                        </option>
                        <option value="Muay Thai" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Muay Thai') ? 'selected' : '' ?>>Muay Thai</option>
                        <option value="Taekwondo" <?= (isset($dadosAtleta->esporte) && $dadosAtleta->esporte == 'Taekwondo') ? 'selected' : '' ?>>Taekwondo</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="categoria" class="form-label tituloDado">Categoria</label>
                    <input type="text" name="categoria" id="categoria" placeholder="Digite a categoria do Atleta"
                        required class="form-control" value="<?php echo $dadosAtleta->categoria ?? null; ?>">
                </div>

                <div class="col-md-4">
                    <label for="data_nascimento" class="form-label tituloDado">Data de Nascimento</label>
                    <input type="date" name="data_nascimento" id="data_nascimento" required class="form-control"
                        value="<?php echo $dadosAtleta->data_nascimento ?? null; ?>">
                </div>

                <div class="col-md-3">
                    <label for="sexo" class="form-label tituloDado">Sexo</label>
                    <select id="sexo" name="sexo" class="form-select" aria-label="Default select example">
                        <option disabled <?= (!isset($dadosAtleta->sexo)) ? 'selected' : '' ?>>Selecione o sexo</option>
                        <option value="F" <?= (isset($dadosAtleta->sexo) && $dadosAtleta->sexo == 'F') ? 'selected' : '' ?>>
                            Feminino
                        </option>
                        <option value="M" <?= (isset($dadosAtleta->sexo) && $dadosAtleta->sexo == 'M') ? 'selected' : '' ?>>
                            Masculino
                        </option>
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="peso" class="form-label tituloDado">Peso</label>
                    <input type="text" name="peso" id="peso"
                        placeholder="Digite o peso. Ex.:102.65 (Coloque as casas depois do ponto)" required
                        class="form-control" value="<?php echo $dadosAtleta->peso ?? null; ?>">
                </div>


                <div class="col-12">
                    <label for="biografia" class="form-label tituloDado">Biografia</label>
                    <textarea type="text" name="biografia" id="biografia"
                        placeholder="Digite uma biografia, no mínimo 200 caracteres" required minlength="200"
                        class="form-control"><?php echo $dadosAtleta->biografia ?? null; ?></textarea>
                </div>

                <div class="col-12 mt-3 d-flex gap-2 justify-content-center">
                    <button type="submit" name="btnCadastrar" id="btnCadastrar" class="btn-padrao">Salvar</button>
                    <a href="login.php" class="btn btn-voltar">Voltar</a>
                </div>
            </div>
        </form>

        <div class="divisor"></div>
        <p class="p-concorda">Ao continuar, você concorda com nossos <a href="termosUso.php"
                class="a-concorda">Termos</a> e <a href="politicaPrivacidade.php" class="a-concorda">Política de
                Privacidade</a>.</p>
        <div class="divisor"></div>
    </main>


    <footer class="footer">
        <?php require_once "_parts/_footer.php"; ?>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoSelect = document.getElementById('tipo_usuario');
            const formAtletaWrapper = document.getElementById('formAtleta');
            const formAtleta = document.getElementById('formAtleta');


            if (!tipoSelect || !formAtletaWrapper) return;

            function mostrarAtleta(show) {
                if (show) {
                    formAtletaWrapper.style.display = 'block';
                    // reativa required nos campos do atleta (se desejar)
                    formAtleta.querySelectorAll('[data-required-on-show]').forEach(el => el.setAttribute('required', 'required'));
                } else {
                    formAtletaWrapper.style.display = 'none';
                    // remove required para não bloquear submit do form principal
                    formAtleta.querySelectorAll('[required]').forEach(el => el.removeAttribute('required'));
                }
            }

            // inicial: se o select já tiver um valor selecionado (ex.: edição), ajustar
            if (tipoSelect.value === 'Atleta') {
                mostrarAtleta(true);
            } else {
                mostrarAtleta(false);
            }

            // evento de mudança
            tipoSelect.addEventListener('change', function () {
                mostrarAtleta(this.value === 'Atleta');
            });

            // botão cancelar dentro do form de atleta (se quiser fechar)
            const btnCancelar = document.getElementById('btnCancelarAtleta');
            if (btnCancelar) {
                btnCancelar.addEventListener('click', function (e) {
                    e.preventDefault();
                    tipoSelect.value = ''; // reseta o select (opcional)
                    mostrarAtleta(false);
                    tipoSelect.focus();
                });
            }
        });

    </script>
    <script src="JS/instrutorDoAtleta.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function enviarFormulario(url, codigo) {
                const formTemp = document.createElement('form');
                formTemp.method = 'POST';
                formTemp.action = url;
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'id';
                input.value = codigo;
                formTemp.appendChild(input);
                document.body.appendChild(formTemp);
                formTemp.submit();
            }

            const email = document.querySelector('#email');
            const confirma = document.querySelector('#confirmaEmail');
            const mensagem = document.querySelector('#mensagem');
            const form = document.querySelector('#form_valida_email');

            if (!email || !confirma || !mensagem || !form) return;

            mensagem.style.display = "none";

            function emailValido(valor) {
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return regex.test(valor);
            }

            function validarEmails() {
                const valEmail = email.value.trim();
                const valConf = confirma.value.trim();

                if (valEmail.length === 0 && valConf.length === 0) {
                    mensagem.textContent = "";
                    mensagem.style.display = "none";
                    return false;
                }

                if (valEmail.length > 0 && !emailValido(valEmail)) {
                    mensagem.textContent = "❌ Formato de e-mail inválido";
                    mensagem.className = "alert alert-danger mt-2 mb-3";
                    mensagem.style.display = "block";
                    return false;
                }

                if (valConf.length > 0 && !emailValido(valConf)) {
                    mensagem.textContent = "❌ Formato de e-mail inválido na confirmação";
                    mensagem.className = "alert alert-danger mt-2 mb-3";
                    mensagem.style.display = "block";
                    return false;
                }

                if (valEmail.length > 0 && valConf.length > 0) {
                    if (valEmail !== valConf) {
                        mensagem.textContent = "❌ E-mails não conferem";
                        mensagem.className = "alert alert-danger mt-2 mb-3";
                        mensagem.style.display = "block";
                        return false;
                    } else {
                        mensagem.textContent = "✅ E-mails iguais";
                        mensagem.className = "alert alert-success mt-2 mb-3";
                        mensagem.style.display = "block";
                        return true;
                    }
                }

                mensagem.textContent = "";
                mensagem.style.display = "none";
                return false;
            }

            email.addEventListener('input', validarEmails);
            confirma.addEventListener('input', validarEmails);

            form.addEventListener('submit', function (e) {
                const ok = validarEmails();
                if (!ok) {
                    e.preventDefault();
                    alert("Corrija o email antes de enviar.");
                    if (!emailValido(email.value.trim())) {
                        email.focus();
                    } else {
                        confirma.focus();
                    }
                }
            });
        });
    </script>

    <script>
        document.getElementById('foto').addEventListener('change', function (event) {
            const img = document.getElementById('fotoColocada');
            img.src = URL.createObjectURL(event.target.files[0]);
        })
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