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
  CRUD.class.php
  Database.class.php
/CSS
  base.css
  baseAdmin.css
  baseSite.css
/Script_BD
  scriptSQL.sql
.gitignore
academia.php
atleta.php
campeonato.php
config.ini
dashboard.php
index.php
instrutor.php
listaAcademia.php
listaAtleta.php
listaCampeonato.php
listaInstrutor.php
listaUsuario.php
login.php
logout.php
README.md
redefinir_senha.php
reset_senha.php
solicitar_recuparecao.php
usuario.php
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
- instrutor
- instituicao_apoiadora
- pedido_ajuda
- campeonato

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