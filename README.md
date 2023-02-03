Структура репозитория:
.docker - папка с окружением
projects/Task - папка с файлами проекта
docker-compose.yml - конфигурация Docker
dump.sql - дамп БД MySQL проекта



Запуск проекта. Всё выполняем в корне проекта (папки):
Для разворачивания окружения выполните
docker-compose build
docker-compose up -d

Далее в папке с проектом выполните composer install
docker exec -it php-5.4 bash
cd /srv/projects/Task/
composer install

Далее импортируем дамп БД (MYSQL_ROOT_PASSWORD указан в файле docker-compose.yml)
docker exec -it php-5.4 bash (не требуется, если вы не вышли из контейнера)
mysql -uroot -hdb -pMYSQL_ROOT_PASSWORD
create database task_test;
exit
mysql -uroot -hdb -pMYSQL_ROOT_PASSWORD task_test < /var/www/dump.sql

В файле /etc/hosts добавьте строку
127.0.0.1   task.localhost

При успешном разворачивании проекта вы получите по адресу http://task.localhost страницу Hello World!

Адрес для перехода в CMS http://task.localhost/cc логин/пароль test/test