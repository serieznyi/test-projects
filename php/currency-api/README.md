# Currency API README

## Требования к проекту

 - Docker version >= 18.09.5
 - docker-compose >= 1.22.0
 - python2.7
  
## Утсрановка

1) Инициализация окружения

```bash
chmod +x ./etc/scripts/*

./etc/scripts/init
```

2) Запускаем контейнеры

```bash
docker-compose up -d
``` 

3) Устанавливаем зависимости

```bash
docker-compose exec php composer install
```

4) Выполняем миграции

```bash
docker-compose exec php ./yii migrate
```

5) Заполняем БД данными о курсах валют

```bash
docker-compose exec php ./yii app/sync-currency-exchange-rate
```

6) Делаем запрос

```bash
curl -X POST \
    -H "Content-Type: application/json" \
    --data '{"currency": "<CURRENCY_CODE>", "value": "<VALUE>"}' \
    http://127.0.0.1:1280/currency/convert
```

CURRENCY_CODE - один из кодов валют из ISO 4217

VALUE - сумма для преобразования

Например: 

```bash
curl -X POST \
    -H "Content-Type: application/json" \
    --data '{"currency": "RUB", "value": "2"}' \
    http://127.0.0.1:1280/currency/convert
```