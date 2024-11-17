name := magisterka-api

build:
	docker-compose build

composer:
	docker-compose exec $(name) composer install -o

up:
	docker-compose up

install: build up composer preparedb

down:
	docker-compose down

stop:
	docker-compose stop

bash:
	docker-compose exec $(name) bash

test:
	docker-compose exec $(name) vendor/bin/phpunit tests/Unit/

restart: down install

phpcsfixer:
	docker-compose exec $(name) php -dmemory_limit=-1 vendor/bin/php-cs-fixer --no-interaction --allow-risky=yes --dry-run --diff fix

phpcsfixer_fix:
	docker-compose exec $(name) php -dmemory_limit=-1 vendor/bin/php-cs-fixer --no-interaction --allow-risky=yes --ansi fix

phpstan:
	docker-compose exec $(name) vendor/bin/phpstan --level=max analyse src

psalm:
	docker-compose exec $(name) vendor/bin/psalm

preparedb:
	docker-compose exec $(name) bin/console doctrine:database:create ; docker-compose exec $(name) bin/console doctrine:migrations:migrate --no-interaction

preparedbtest:
	docker-compose exec $(name) bin/console doctrine:database:create --env=test ; docker-compose exec $(name) bin/console doctrine:migrations:migrate --env=test

fixtures:
	docker-compose exec $(name) php bin/console --env=test doctrine:fixtures:load