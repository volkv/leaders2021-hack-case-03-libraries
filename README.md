## Локальный запуск (Linux / macOS):

##### Local SSL (https://github.com/FiloSottile/mkcert) 
`mkcert -key-file key.pem -cert-file cert.pem libraries.local && mv -t docker/nginx/local/ssl/ key.pem cert.pem`
##### /etc/hosts
`127.0.0.1  libraries.local`
##### .env
`cp .env.example .env`

##### Зависимости

* git (`apt install git`)
* make (`apt install make`)
* docker / docker-compose (`apt install docker-compose`)

###

`git clone git@github.com:volkv/leaders2021-hack-case-03-libraries.git`
### Сборка

* `make docker-build`
* `make setup-local`

### Очистка данных и перенос в PostgreSQL

*Необходимые файлы*
`storage/datasets_biblioteki/datasets_2/circulaton_{1-16}.csv`
`storage/datasets_biblioteki/books.jsn`

*Либо использовать уже очищенные данные*
./docker/sql/
`make restore-sql`
`make seed` - создать справочники и перенести датасет в БД

### Использование

`make npm-watch` - сборка фронта в режиме watch
`make cache` - очистка кэша приложения
