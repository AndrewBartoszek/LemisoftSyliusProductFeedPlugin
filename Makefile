DOCKER_COMPOSE = docker compose
EXEC_PHP       = $(DOCKER_COMPOSE) exec -T app
RUN_PHP        = $(DOCKER_COMPOSE) run --rm --no-deps app
COMPOSER       = composer
CONSOLE        = ./tests/Application/bin/console
PHPSTAN        = ./vendor/bin/phpstan
PHPCS          = ./vendor/bin/phpcs
PHPCBF         = ./vendor/bin/phpcbf
PHPMND         = ./vendor/bin/phpmnd
PHPUNIT        = ./vendor/bin/phpunit
ECS            = ./vendor/bin/ecs
PSALM          = ./vendor/bin/psalm
BEHAT          = ./vendor/bin/behat
PHPSPEC        = ./vendor/bin/phpspec

rn:
	$(eval EXEC_PHP := $(RUN_PHP))

eslint:
	$(EXEC_NODE) npm run lint

phpcs:
	$(EXEC_PHP) $(PHPCS) -p

ecs:
	$(EXEC_PHP) $(ECS) check

phpcbf:
	$(EXEC_PHP) $(PHPCBF)

phpstan:
	$(EXEC_PHP) $(PHPSTAN) --memory-limit=-1

phpmnd:
	$(EXEC_PHP) $(PHPMND) src --ignore-funcs=sleep --progress --extensions=all

psalm:
	$(EXEC_PHP) $(PSALM)

behat:
	APP_ENV=test $(EXEC_PHP) $(BEHAT) --colors --strict --no-interaction -f progress

phpunit:
	$(EXEC_PHP) $(PHPUNIT) --testdox --colors=never

phpspec:
	$(EXEC_PHP) $(PHPSPEC) run --ansi --no-interaction -f dot

install:
	$(EXEC_PHP) $(COMPOSER) install --no-interaction --no-scripts

backend:
	$(EXEC_PHP) $(CONSOLE) sylius:install --no-interaction
	$(EXEC_PHP) $(CONSOLE) sylius:fixtures:load default --no-interaction

frontend:
	$(EXEC_PHP) yarn install --cwd tests/Application --pure-lockfile
	GULP_ENV=prod  $(EXEC_PHP) yarn --cwd tests/Application build


init: install backend frontend

tests: phpunit behat phpspec

quality: phpcs phpstan phpmnd ecs
