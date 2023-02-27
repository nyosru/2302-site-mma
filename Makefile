include .env
.PHONY: config

SHELL = /bin/sh

export DOCKER_BUILDKIT=0
export COMPOSE_DOCKER_CLI_BUILD=0

USER_ID=$(shell id -u)
GROUP_ID=$(shell id -g)

export USER_ID
export GROUP_ID

# queue:
# 	docker-compose run --rm app php artisan queue:work horizon

# phpbf:
# 	docker-compose run --rm app vendor/bin/phpcbf app

# ide:
# 	docker-compose run --rm app php artisan ide-helper:generate
# 	docker-compose run --rm app php artisan ide-helper:models -M
# 	docker-compose run --rm app php artisan ide-helper:meta

# route:
# 	docker-compose run --rm app composer dump-autoload
# 	docker-compose run --rm app php artisan optimize

# permissions:
# chmod -R 777 ./storage

# buildapp:
# 	docker-compose run --rm app composer install
# docker-compose run --rm app php artisan optimize
# docker-compose run --rm app php artisan optimize:clear
# make permissions
# docker-compose run --rm app rsync -a /www/ /www-export/

# node:
# 	docker-compose run --rm node bash

# build:
# 	docker-compose build --build-arg APP_ENV=local

config_local:
	cp ./.env.local ./www-data/.env

config_beta:
	cp ./.env.beta ./www-data/.env

up:
	if [ -d /var/run/docker.sock ];then \
	sudo chown ${USER} /var/run/docker.sock ;\
	fi
	if [ -d /run/docker.sock ];then \
	sudo chown ${USER} /run/docker.sock ;\
	fi	
# docker-compose up -d
	docker-compose up 
# docker-compose up --build

down:
	docker-compose down

# policy:
# 	docker-compose run --rm app php artisan install:policy

# redocker:
# 	docker images -a | grep "mm" | awk '{print $3}' | xargs docker rmi -f
# 	docker-compose down --remove-orphans

migrations:
	docker-compose run --rm app php artisan migrate

install_local: config_local buildapp migrations
# install_local: config_local migrations

install_beta: config_beta buildapp migrations

run_local: down up install_local
# run_local: up install_local

run_beta: down up install_beta

# restart: down up

