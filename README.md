## Локальный запуск (Linux / macOS):

### Зависимости

`cp .env.example .env`
##### Local SSL (https://github.com/FiloSottile/mkcert) 
`mkcert -key-file key.pem -cert-file cert.pem libraries.local && mv -t docker/nginx/local/ssl/ key.pem cert.pem`
##### /etc/hosts
`127.0.0.1  libraries.local`
##### .env
`cp .env.example .env`
* git (`apt install git`)
* make (`apt install make`)
* docker / docker-compose (`apt install docker-compose`)

### Сборка

* `make docker-build`
* `make setup-local`

### Использование

`make npm-watch` - сборка фронта в режиме watch
`make cache` - очистка кэша приложения
