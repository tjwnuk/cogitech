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
```

App works on port :8080