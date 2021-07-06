include .docker
include .env

.PHONY: setup up down stop prune ps shell logs help

default: help

## help	:	Print commands help.
help :
	@fgrep -h "##" $(MAKEFILE_LIST) | fgrep -v fgrep | sed -e 's/\\$$//' | sed -e 's/##//'

## setup-env	:	Setup env variables.
setup-env:
	composer run setup

## setup	:	Setup tools containers.
setup:
	@echo "Starting up the tools containers for for $(PROJECT_NAME)..."
	docker network create traefik-proxy || true
	docker network create adeliom_tools || true
	mutagen project terminate -f _mutagen/config.yml || true
	docker-compose -p adeliom_tools --env-file=.docker -f docker-compose.setup.yml up -d --remove-orphans --force-recreate
	docker-compose --env-file=.docker up -d --remove-orphans --force-recreate
	mutagen project start -f _mutagen/config.yml

## up	:	Start up containers.
up:
	@echo "Starting up containers for for $(PROJECT_NAME)..."
	docker-compose --env-file=.docker pull
	mutagen project terminate -f _mutagen/config.yml || true
	docker-compose --env-file=.docker up -d --remove-orphans
	mutagen project start -f _mutagen/config.yml

## down	:	Stop containers.
down: stop

## start	:	Start containers without updating.
start:
	@echo "Starting containers for $(PROJECT_NAME) from where you left off..."
	mutagen project terminate -f _mutagen/config.yml || true
	@docker-compose --env-file=.docker start
	mutagen project start -f _mutagen/config.yml

## stop	:	Stop containers.
stop:
	@echo "Stopping containers for $(PROJECT_NAME)..."
	mutagen project terminate -f _mutagen/config.yml
	@docker-compose --env-file=.docker stop


## prune	:	Remove containers and their volumes.
##		You can optionally pass an argument with the service name to prune single container
##		prune mariadb	: Prune `mariadb` container and remove its volumes.
##		prune mariadb solr	: Prune `mariadb` and `solr` containers and remove their volumes.
prune:
	@echo "Removing containers for $(PROJECT_NAME)..."
	mutagen project terminate -f _mutagen/config.yml  || true
	@docker-compose --env-file=.docker down -v $(filter-out $@,$(MAKECMDGOALS))

## ps	:	List running containers.
ps:
	@docker-compose ps

## shell	:	Access `php` container via shell.
##		You can optionally pass an argument with a service name to open a shell on the specified container
shell:
	docker exec -ti -e COLUMNS=$(shell tput cols) -e LINES=$(shell tput lines) $(shell docker ps --filter name='$(PROJECT_NAME)_$(or $(filter-out $@,$(MAKECMDGOALS)), 'php')' --format "{{ .ID }}") sh

## logs	:	View containers logs.
##		You can optinally pass an argument with the service name to limit logs
##		logs php	: View `php` container logs.
##		logs nginx php	: View `nginx` and `php` containers logs.
logs:
	@docker-compose --env-file=.docker logs -f $(filter-out $@,$(MAKECMDGOALS))

# https://stackoverflow.com/a/6273809/1826109
%:
	@:
