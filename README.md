# CURRENCY-ISO-4217

## Descrição

É aplicação onde o será passado um código ou numero ISO 4217 (padrão internacional que define códigos de três letras para as moedas), e a aplicação realiza o crawling em uma fonte externa retornando os dados desta moeda.

## Requisitos

Certifique-se de ter as seguintes ferramentas instaladas localmente antes de começar:
- [Docker](https://www.docker.com/get-started)
- [Docker Compose](https://docs.docker.com/compose/install/)
- [Git](https://docs.docker.com/compose/install/)
## Configuração do Ambiente com Docker

Este projeto utiliza Docker para facilitar a configuração do ambiente de desenvolvimento. Siga os passos abaixo para iniciar a aplicação:

1. Clone este repositório:

```bash
git clone https://github.com/lchideki/currency-iso-4217.git
```

2. Navegue até o diretório do projeto:

```bash
cd nome-do-projeto
```

3. Execute o comando a seguir para copiar o conteúdo de ".env.example" para  o arquivo ".env":

```bash
cp .env.example .env
```

4. Utilize as seguintes configurações para a conexão do redis no arquivo ".env":

```bash
REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```
    
5. Execute o Docker Compose para construir e iniciar os contêineres:

```bash
docker-compose up -d --build
```

6. Instale as dependências do Laravel via Composer:

```bash
docker-compose exec app composer install
```

7. Execute o comando para iniciar a aplicação

```bash
docker-compose exec app php artisan serve --host=0.0.0.0 --port=8000
```

8. A aplicação estará disponível em `http://localhost:8000`

## Comandos Úteis do Docker

- Iniciar a aplicação:

    ```bash
    docker-compose up -d
    ```

- Parar a aplicação:

    ```bash
    docker-compose down
    ```

- Ver os logs da aplicação:

    ```bash
    docker-compose logs -f
    ```

- Acessar o shell do contêiner app:

    ```bash
    docker-compose exec app bash
    ```

## Contribuição

Se você encontrar problemas ou tiver sugestões para melhorias, sinta-se à vontade para abrir uma [issue](https://github.com/lchideki/currency-iso-4217/issues) ou enviar um [pull request](https://github.com/seu-usuario/nome-do-projeto/pulls).

## Licença

Este projeto está licenciado sob a [MIT License](LICENSE).
