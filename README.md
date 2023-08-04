# Artur Work Manager

## Wymagania

PHP 7.4+
php-sqlite

## Instalacja

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install
```

## Uruchomienie

### Lokalnie

```
php -S localhost:9999 -t public
```


### Na serwerze

Wskaż katalog `public` jako document root w Apache.


## Obsługa

### Tworzenie użytkowników

```
php cli.php users:create
```
