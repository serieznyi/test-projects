# README

## Init using docker 

Run docker containers
```bash
docker-compose up -d
```

Wait until DB starting

Init database 

```bash
cat ./scheme.sql | docker-compose exec -T mysql sh -c 'mysql -u test -ptest test'
```

Prepare dirs:

```bash
chmod 0777 -R ./public/
```

Open http://127.0.0.1:8012

## Init

Init database using ./scheme.sql

Prepare dirs:

```bash
chmod 0777 -R ./public/
```

Use local php, database and web server