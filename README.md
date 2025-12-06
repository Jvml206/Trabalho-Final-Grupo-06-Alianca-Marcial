# Trabalho-Final-Grupo-06-Aliança-Marcial

Integrantes:

- Angeliny Cezário Narcizo de Oliveira
- João Victor Machado lopes
- Matheus Alexandre Alves Luz

## 🛠️ Tecnologias Utilizadas
- PHP  
- MySQL  
- HTML, CSS, Bootstrap
- JavaScript

---

## 🗃️ Estrutura do Projeto
```
/_parts
  _footer.php
  _navAdmin.php
  _navSite.php
/Classes
  Academia.class.php
  Campeonato.class.php
  Atleta.class.php
  CRUD.class.php
  Database.class.php
  InstituicaoApoiadora.class.php
  Instrutor.class.php
  PedidoAjuda.class.php
  Usuario.class.php
/CSS
  base.css
  baseAdmin.css
  baseSite.css
  bemVindo.css
  dashboard.css
  index.css
  login.css
/Images
  /academia
    NuncaTirarParaNãoApagarAPasta.jpg
    SemFoto.png
  /campeonato
    Aikidô.jpg
    Boxe.jpg
    Capoeira.jpg
    Jiu-Jitsu Brasileiro.jpg
    Judô.jpg
    Karatê.jpg
    Kung Fu.jpg
    MMA.jpg
    Muay Thai.jpg
    Taekwondo.jpg
  /instituicao_apoiadora
    NuncaTirarParaNãoApagarAPasta.jpg
    SemFoto.png
  /pedidoDeAjuda
    NuncaTirarParaNãoApagarAPasta.jpg
    SemFoto.png
  /usuario
    NuncaTirarParaNãoApagarAPasta.jpg
    SemFoto.png
  artes_marciais.png
  logo.png
/JS
  dataCampeonato.js
  instrutorDoAtleta.js
  paginacao.js
  pesquisaCards.js
  senha.js
  senhaLogin.js
/PHPMailer
  arquivos em 'https://github.com/PHPMailer/PHPMailer'
/SQL
  phpMyAdmin.sql
  scriptSQL.sql
  selects.sql
.gitignore
academia.php
academias.php
atleta.php
bemVindo.php
campeonato.php
campeonatos.php
config.ini
conta.ini
criar_conta.ini
dashboard.php
exclusaoConta.php
index.php
instituicaoApoiadora.php
instrutor.php
listaAcademia.php
listaAtleta.php
listaCampeonato.php
listaInstituicoes.php
listaInstrutor.php
listaPedidoDeAjuda.php
listaUsuario.php
login.php
logout.php
pedidoDeAjuda.php
pedidosDeAjuda.php
politicaPrivacidade.php
processaValidacaoAtleta.php
processaValidacaoInstrutor.php
processaValidacaoPedido.php
README.md
redefinir_senha.php
reset_senha.php
sobre.php
solicitar_recuparecao.php
termosUso.php
usuario.php
validarAtleta.php
validarInstrutor.php
validarPedido.php
validarUser.php
      
```

---

## 🗄️ Banco de Dados
Nome do banco: **Alianca_Marcial**

Schemas:
- public

Tabelas
- usuario
- RecuperacaoSenha
- academia
- atleta
- instrutor
- instituicao_apoiadora
- pedido_ajuda
- campeonato

Procedure
- dashboard_totais

---

## 🚀 Como Executar o Projeto
1. Clone este repositório  
2. Execute o script SQL no MySQL
3. Configure `config.ini`  
    ```
    [database]

    driver      = mysql
    host        = localhost
    port        = 3306
    dbname      = Alianca_Marcial
    username    = 'seu user'
    password    = "'sua senha'"

    [email]
    Host        = smtp.gmail.com
    SMTPAuth    = true;
    Username    = 'email que enviara a mensagem'
    Password    = 'senha de app'
    SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS
    Port        = 587
    ```
4. Acesse no navegador: `http://localhost/Trabalho-Final-Grupo-06-Alianca-Marcial`

---