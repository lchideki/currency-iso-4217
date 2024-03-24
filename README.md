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
APP_URL=http://localhost:8000

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

## Consultar Informações de Moeda

- **URL**: `http://localhost:8000/api/currency`
- **Método HTTP**: GET
- **Descrição**: Este endpoint permite consultar informações sobre uma moeda com base em seu número ou código.

## Parâmetros da Solicitação

| Parâmetro        | Tipo     | Descrição                                            |
|------------------|----------|------------------------------------------------------|
| `number`         | String   | Número da moeda que você deseja consultar            |
| `code`           | String   | Código da moeda que você deseja consultar            |
| `number_list`    | Array   | Array de Números da moeda que você deseja consultar  |
| `code_list`      | Array   | Array de Códigos da moeda que você deseja consultar  |

## Exemplo de Solicitação

- Consultar informações de uma moeda pelo número:

  ```http
  GET http://localhost:8000/api/currency?code=GEL

## Documentação do response Moeda

Esta documentação detalha as informações do retorno da consulta sobre a moeda.

## Detalhes da Moeda (Exemplo moeda LARI. Código "GEL").

- **Código da Moeda:** GEL
- **Número da Moeda:** 981
- **Dígitos Decimais:** 2
- **Nome da Moeda:** LARI

## Países que Utilizam a Moeda

- **Geórgia (Location)**
  - ![Bandeira da Geórgia](https://upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/22px-Flag_of_Georgia.svg.png) (Icon)

## Exemplo de Resposta JSON

Aqui está um exemplo de como um objeto JSON representando a moeda GEL (LARI):

```json
{
  "code": "GEL",
  "number": "981",
  "decimal_digits": 2,
  "currency": "LARI",
  "locations": [
    {
      "icon": "//upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/22px-Flag_of_Georgia.svg.png",
      "location": "Geórgia"
    }
  ]
}
```

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
