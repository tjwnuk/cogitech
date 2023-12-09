# Cogitech recruitment task

## Author
Tomasz Wnuk \
tjwnuk@proton.me \
https://github.com/tjwnuk 

## Description

This is recruitment task for Cogitech.\
This repo utilises template from \
https://github.com/ger86/symfony-docker

## Install
clone this repo \
```
cd cogitech
cd .docker
docker compose up -d
docker exec -it symfony_dockerized-php-1 bash
composer install

#initialize database entity
php bin/console make:migration
```

## Use
App works on port :8080 . Please run browser and open `http://localhost:8080`. After starting the container refresh the page.

### Fetch the data
1. Run container
```bash
cd cogitech/.docker/
docker-compose up -d
```

2. List working containers
```bash
docker ps
```

3. find php container and get into it, for example
```bash
docker exec -it symfony_dockerized-php-1 bash
```

4. Fetch data from the website
```bash
php bin/console fetch
```

### Clear the database

You can simply clear the database, just log into container
```bash
docker exec -it symfony_dockerized-php-1 bash
```
and run the command
```bash
php bin/console clear
```