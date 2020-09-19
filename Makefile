.PHONY: migrations

local-deploy:
	docker-compose up -d
	docker exec -it immo-php composer install
	docker exec -it immo-php yii migrate --interactive=0
	docker exec -it immo-php tests/bin/yii migrate --interactive=0

migrations:
	docker exec -it immo-php yii migrate --interactive=0
	docker exec -it immo-php tests/bin/yii migrate --interactive=0

unit-tests:
	docker exec -it immo-php vendor/bin/codecept run unit

api-tests: currency-update
	docker exec -it immo-php vendor/bin/codecept run api

currency-update:
	docker exec -it immo-php yii currency/update