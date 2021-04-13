# Transfer API - Based on Lumen PHP Framework
[![Lumen Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

## Iniciando o projeto

O Projeto possui suporte para uso do docker. Para isso é necessário que você tenha instalado o [Docker](https://docs.docker.com/engine/install/) e [Docker-Compose](https://docs.docker.com/compose/install/) na sua maquina.

1. Clonar o projeto e entar na pasta  _transfer-api/docker/_ para executar os comandos necessários
~~~bash
git clone https://github.com/mts-emanoel/transfer-api.git && cd transfer-api && cd docker
~~~

2. Dentro do diretório raiz os arquivos .env já estão com os valores padrão. Basta entrar em _transfer-api/docker/_ e iniciar os conteiners com o docker-compose
~~~bash
docker-compose up -d nginx mysql phpmyadmin workspace
~~~

3. Entre no bash em _workspace_ e posteriormente execute os respectivos comandos para instalação do projeto, migração e geração do token privado jwt
~~~bash
docker-compose exec workspace bash
~~~

5. Instalação dos pacotes via composer e migração do banco de dados; Por fim a saída com _exit_
~~~bash
composer install && php artisan migrate && exit
~~~

4. Quando precisar parar os conteiners utilize o comando abaixo 
~~~bash
docker-compose down
~~~


## [Documentação](https://documenter.getpostman.com/view/13030272/TzJoDzxX#9da8b6bd-8ed4-4584-8060-a7ad9286cb9a)

Toda documentação foi gerada atravez do [Postman](https://www.postman.com/).
 - Leia ou baixe a documentação, acesse: 
   - [https://documenter.getpostman.com/view/13030272/TzJoDzxX#9da8b6bd-8ed4-4584-8060-a7ad9286cb9a](https://documenter.getpostman.com/view/13030272/TzJoDzxX#9da8b6bd-8ed4-4584-8060-a7ad9286cb9a)

## Serviços Padrão

### Acesso ao projeto
- URL Base: https://localhost:8000/

### PhpMyAdmin
- Porta: 8081
- URL: https://localhost:8081/
- Credenciais de acesso:
  - Server: mysql
  - User: default
  - Password: secret

### NGINX
- HTTP: porta 8000

### MYSQL
- Host: mysql
- Porta: 3309
- Banco de Dados: default
- Usuário: default
- Senha: secret