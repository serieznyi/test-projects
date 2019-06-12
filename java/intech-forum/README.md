# README

# Зависимости

 - Java >= 8
 - Docker >= 18.09 (только если у вас нет своего instance базы данных)
 - docker-compose 1.21.2 (только если у вас нет своего instance базы данных)
 
## Run app

1) Запускаем БД в docker контейнере или используем свой instance.

```bash
docker run \
    --name intech-postgres \
    -v $(pwd)/etc/docker/postgres.conf:/etc/postgres/postgresql.conf \
    -p "5477:5432" \
    -e POSTGRES_USER=intech_user \
    -e POSTGRES_PASSWORD=intech_pass \
    -e POSTGRES_DB=intech \
    -d \
    postgres:9.6.13-alpine \
    postgres -c config_file=/etc/postgres/postgresql.conf
```

или используем свой экземпляр БД. Для этого необходимо изменить реквизиты 
БД в файле `application.properties`

2) Применяем миграции

```
./gradlew liquibaseUpdate
```

3) Запускаем приложение 

```
./gradlew bootRun
```

4) Сайт доступен по адресу http://127.0.0.1:8080/