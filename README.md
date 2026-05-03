## 1. Configurando o Banco de Dados

Abra o arquivo `config/database.php` e preencha as credenciais de conexão nas linhas 5 a 8:

![Configuração do database.php](images/image-5.png)

Em seguida, acesse a pasta `database/` e abra o arquivo `schema.sql`. Execute cada bloco de código clicando em **RUN** sequencialmente, do início ao fim do arquivo.

![Executando o schema.sql](images/image-6.png)

---

## 2. Clonando o Repositório

Após o `git clone`, seu terminal estará posicionado na raiz do projeto:

```bash
git clone https://github.com/itamarHenrique/w5i-case-tecnico.git
```

![Terminal na raiz do projeto](images/image.png)

---

## 3. Acessando o Diretório `/public`

O projeto deve ser iniciado a partir do diretório `/public`. Navegue até ele com:

```bash
cd public
```

![Acessando a pasta public](images/image-1.png)

---

## 4. Iniciando o Servidor Local

Dentro de `/public`, inicie o servidor embutido do PHP:

```bash
php -S localhost:8000
```

![Servidor PHP rodando](images/image-2.png)

> **Nota:** A porta `8000` é apenas um exemplo — qualquer porta disponível pode ser usada.

---

## 5. Abrindo no Navegador

Com o servidor em execução, dê um duplo clique no link exibido entre parênteses no terminal:

![Link no terminal](images/image-3.png)

O projeto abrirá automaticamente no navegador:

![Projeto no navegador](images/image-4.png)

> As abas **Setor** e **Prioridade** estão disponíveis no canto superior direito da página.