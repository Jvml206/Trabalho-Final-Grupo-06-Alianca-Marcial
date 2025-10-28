# Trabalho-Final-Grupo-06-Aliança-Marcial

Integrantes:

Angeline
João Victor Machado lopes
Matheus

## 🛠️ Tecnologias Utilizadas
- PHP  
- MySQL  
- HTML, CSS, Bootstrap

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
config.ini
README.md
      
```

---

## 🗄️ Banco de Dados
Nome do banco: **Alianca_Marcial**

Schemas:
- public

Tabelas
- dim_instrutores

---

## 🚀 Como Executar o Projeto
1. Clone este repositório  
2. Crie o database Alianca_Marcial no pgAdmin
3. Acesse o Query Tool do database
4. Execute o script SQL  
5. Configure `config.ini`  
    ```
    ['database']
    
    driver      = pgsql
    host        = localhost
    port        = 5432
    dbname      = Alianca_Marcial
    username    = 'seu user'
    password    = "'sua senha'"
    ```
4. Acesse no navegador: `http://localhost/Trabalho-Final-Grupo-06-Alianca-Marcial`

---
