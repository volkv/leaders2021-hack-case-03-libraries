# Готовое решение

# https://mos-knigi.volkv.com

## Stack

* PHP `8.0.12`
* Laravel `8.54`
* PostgreSQL `14`
* Nginx `1.21.3`
* Redis `6.2.6`
* Meilisearch `0.23.0`
* React `16.14`

## Технологии

* Docker / Compose
* Очереди Redis
* Коллаборативная фильтрация
* Кэш Redis
* Планировщик Laravel
* Meilisearch - современный, эффективный поиск на языке Rust


## Полная документация к API:

> https://mos-knigi.volkv.com/api

## Методы API

#### Список рекомендаций для пользователя

> GET /api/v1/recs_for_user_id/{userId}
>

#### Список ближайших соседей для пользователя

> GET /api/v1/neighbours_for_user_id/{userId}

#### История книг пользователя

> GET /api/v1/history_for_user_id/{userId}

#### Поиск по книгам

> GET /api/v1/search?q={query}

## Функционал веб-интерфейса:

* Выдача рекомендаций по заданному `userID`
* Поиск ближайших соседей `userID` и отображением их книг (общих и различных)
* Отображении истории книг пользователя
* Поиск по всем книгам на сайте

## Локальный запуск (Linux / macOS):

##### Local SSL (https://github.com/FiloSottile/mkcert) (необязательно)

> `mkcert -key-file key.pem -cert-file cert.pem libraries.local && mv -t docker/nginx/local/ssl/ key.pem cert.pem` - для того, чтобы сайт открывался по https с действительным сертификатом

##### /etc/hosts

> `127.0.0.1  libraries.local` - добавляем наш тестовый локальный домен

##### Зависимости

* git (`apt install git`)
* make (`apt install make`)
* docker / docker-compose (`apt install docker-compose`)

### Git clone

> `git clone git@github.com:volkv/leaders2021-hack-case-03-libraries.git`

> `cd leaders2021-hack-case-03-libraries`

# Сборка

> `cp .env.example .env` - скопировать файл с переменным окружения

> `make docker-build` - сборка Docker контейнеров

> `make setup-local` - установка зависимостей, сборка фронта

### Восстановление БД PostgreSQL с очищенными данными

> `https://mos-knigi.volkv.com/storage/backup` - скачать дамп PostgreSQL в папку ./docker/sql/

> `make restore-sql` - команда восстановления дампа БД

### Успех. Сервер запущен и доступен по адресу: https://libraries.local:8080

### Использование

> `make npm-watch` - сборка фронта в режиме watch

> `make cache` - очистка кэша приложения

> `make search` - принудительно обновить поисковый индекс


# Пояснительная документация

Логика обработки файлов датасета и создания БД находится в файле
`./database/seeders/DatabaseSeeder.php`

Логика работы рекомендательной системы находится в файле
`./app/Helpers/BookHelperService.php`

Настройка работы рекомендательной системы производится с помощью констант `NEIGHBOUR_MIN_BOOK_COUNT` и `MAX_NEIGHBOURS_COUNT` класса `BookHelperService`


