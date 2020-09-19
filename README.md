Для локального развертывания на ПК должны быть установлены докер и утилита make.

Запускаем развертывание командой make local-deploy
Поднимается сервер, доступный по адресу localhost:8080

Запускать тесты можно командами make unit-tests и make api-tests

Обновить валюту можно командой make currency-update или docker exec -it immo-php yii currency/update

Миграции применяются автоматически при исполнении команды make local-deploy.
Но также есть команда позволяющая запустить миграции отдельно make migrations.

Основная логика лежит в папке components.

Примеры запросов:
localhost:8080/currency/1
localhost:8080/currencies?page=2
