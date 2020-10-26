# Define the docker-compose binary via ENV variable
DOCKER_COMPOSE  ?= sudo -E docker-compose
# Default for Linux & Docker for Mac, set ENV variable when running i.e. docker-machine under macOS
DOCKER_HOST     ?= localhost
DOCKER ?= sudo -E docker

UNAME_S := $(shell uname -s)
ifeq ($(UNAME_S), Darwin)
	OPEN_CMD        ?= open
	DOCKER_HOST_IP  ?= $(shell echo $(DOCKER_HOST) | sed 's/tcp:\/\///' | sed 's/:[0-9.]*//')
else
	OPEN_CMD        ?= xdg-open
	DOCKER_HOST_IP  ?= 127.0.0.1
endif

# Targets
# -------

default: help

build: ##@base build images in test-stack
	#
	# Building images from docker-compose definitions
	#
	$(DOCKER_COMPOSE) build --pull

up: ##@base start stack
	#
	# Starting application stack
	#
	$(DOCKER_COMPOSE) up -d

stop: ##@base stop stack
	#
	# Stopping application stack
	#
	$(DOCKER_COMPOSE) stop

clean: ##@base remove all containers in stack
	#
	# Cleaning Docker environment
	#
	$(DOCKER_COMPOSE) kill
	$(DOCKER_COMPOSE) rm -fv
	$(DOCKER_COMPOSE) down --remove-orphans --rmi

reset: ##@reset containers to application defaults
	#
	# Resets stack to default condition
	#
	$(DOCKER_COMPOSE) stop
	$(DOCKER_COMPOSE) rm -v -f

frontend: ##@Build frontend scripts
	#
	# Compile frontend
	#
	$(DOCKER_COMPOSE) run --rm frontend sh -c "npm install && npm run build"

composer: ##@install composer package (enable host-volume in docker-compose config)
	#
	# Running composer installation
	#
	$(DOCKER_COMPOSE) run --no-deps --rm -e PHP_ENABLE_XDEBUG=0 php bash -c "composer install --quiet --optimize-autoloader && composer dump-autoload --classmap-authoritative"

restart:
	$(DOCKER_COMPOSE) restart
bash:	 ##@development run application bash in one-off container
	#
	# Starting application bash
	#
	$(DOCKER_COMPOSE) run --rm -e PHP_ENABLE_XDEBUG=0 php bash

bash-xdebug:	 ##@development run application bash in one-off container with xdebug
	#
	# Starting application bash
	#
	$(DOCKER_COMPOSE) run --rm php bash

exec:	 ##@development execute command (c='yii help') in running container
	#
	# Running command
	# Note: Make sure the application container is running
	#
	$(DOCKER_COMPOSE) exec php sh -c "$(c)"

exec_git:	 ##@development execute command (c='yii help') in running container
	#due to bug we need -T https://github.com/docker/compose/issues/3352
	$(DOCKER_COMPOSE) exec -T php sh -c "$(c)"

cleanup: ##@cleanup all unused docker data to free more space
	#
	# Free space
	#
	$(DOCKER) system prune -f