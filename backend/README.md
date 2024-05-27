## How to run (Docker)

1 - Install the dependencies:

```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

2 - Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

3 - Create Docker Network:

```bash
docker network create backup-manager-local
```

4 - Start the containers:

```bash
docker compose up -d
```

5 - Generate the application key:

```bash
docker compose exec laravel.test php artisan key:generate
```

6 - Run the migrations:

```bash
docker compose exec laravel.test php artisan migrate
```

## How to build (Docker)

1 - Build the Docker image:

```bash
docker compose -f docker-compose.build.yml build --compress --force-rm --no-cache --parallel --pull
```

2 - Push the Docker image:

```bash
docker compose -f docker-compose.build.yml push
```
