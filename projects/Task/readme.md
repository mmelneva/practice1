# Разворачивание проекта через docker-compose

```
docker-compose build
docker-compose up -d
docker-compose exec php-fpm composer install
docker-compose exec php-fpm php artisan migrate
```

