# Desafio Capyba PHP/Laravel

Desenvolvimento de uma API utilizando PHP/Laravel.

## 🛠️Instalação 

- Instalar o PHP 8.2 -> https://www.php.net/downloads.php
- Instalar o composer (pode ser a ultima versão) -> https://getcomposer.org/download/
- Clonar este repositório ->
```
git clone https://github.com/jacksonlmp/desafio_capyba_php.git
```

## 🛞Rodar o projeto
Após ter os itens instalados, entre no diretório do projeto
```
cd desafio_capyba_php
```
Execute o comando do composer para instalar as dependêncidas
```
composer install
```
Rode as migrations
```
php artisan migrate
```
Rode os seeders dos filmes
```
php artisan db:seed
```
Rode o servidor
```
php artisan serve
```

Obs: Não consegui fazer com que a verificação de e-mail funcionasse corretamente, uma alternativa que fiz foi enviar o token de verificação para o log
**/storage/logs/laravel.log**
Uma vez feita a requisição pelo endpoint **Enviar token de verificação**, o log receberá o token como se fosse em formato de e-mail.


