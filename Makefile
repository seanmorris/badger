#!make
.PHONY: init

SHELL    = /bin/bash
PROJECT  ?=badger
REPO     ?=sean

-include .env
-include .env.${TARGET}

-include vendor/seanmorris/ids/Makefile

init: ${TARGET_COMPOSE}
	@ docker run --rm \
		-v $$PWD:/app \
		-v $${COMPOSER_HOME:-$$HOME/.composer}:/tmp \
		composer -vvv require seanmorris/ids:dev-master
	@ make -s
	@ make -s start-fg
