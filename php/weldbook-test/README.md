# README

## Требования

* docker 17.06.0+

* docker-compose

## Инициализация окружения 

* Запускаем docker контенер
```bash
docker-compose up -d
```

* Ждем пока запустится БД

* Настраиваем права у root директории

```bash
sudo chmod 0777 -R ./cli ./public
```

* Устанавливаем зависимости

```bash
docker-compose exec php sh -c 'composer install --prefer-dist'
```

* Инициализация БД

```bash
docker-compose exec php sh -c './cli migrate/up'
```

Смотрим файл `Weldbook Test.postman_collection.json`

## Тесты

 - Инициализируем приложение по вышеуказанной инструкции
 - `docker-compose exec php sh -c './vendor/bin/codecept build'`
 - `docker-compose exec php sh -c './vendor/bin/codecept run'`