# zrutest
## Приложение для создания отложенных транзакций

Для  начала работы необходимо:
 - Php 7.1
 - Mysql 5.7
 - docker
 - composer

# Разворачивание проекта с помощью докера: 
```
 1. Выполнить команду git clone https://github.com/Roman0532/zrutest.git
 2. cd zrutest
 3. Открыть файл docker-compose.yml и отредактировать переменные
 - "MYSQL_DATABASE="
 - "MYSQL_USER="
 - "MYSQL_PASSWORD="
 - "MYSQL_ROOT_PASSWORD="
 4. docker-compose up --build -d
 5. docker exec zrutest_app_1 composer install
 6. docker exec database mysql -u(ЛОГИН БД) -p(ПАРОЛЬ БД) -e "CREATE DATABASE IF NOT EXISTS (ИМЯ БД);"
 7. cp .env.example .env 
 8. Открыть файл env и отредактировать переменые
 - DB_DATABASE=
 - DB_USERNAME=
 - DB_PASSWORD=
 ```
 ## Выполнить следущие команды
 ```
 9.  docker exec zrutest_app_1 php artisan migrate
 10. docker exec zrutest_app_1 php artisan db:seed
 11. docker exec zrutest_app_1 php artisan config:cache
 12. docker exec zrutest_app_1 php artisan config:clear
 13. docker exec zrutest_app_1 php artisan key:generate
 14. docker exec zrutest_app_1 chmod 777 -R storage/
 15. Открыть проект по адресу localhost:8080
 ```
 # Разворачивание проекта без докера 
 1. Выполнить команду git clone https://github.com/Roman0532/zrutest.git
 2. cd zrutest
 3.	composer	install
 4. cp .env.example .env 
 5. Открыть файл env и отредактировать переменые
 - DB_DATABASE=
 - DB_USERNAME=
 - DB_PASSWORD=
 - DB_PORT:3306
 
 ## Для запуска тестов введите команду 
 
 ``` vendor/bin/phpunut ```
