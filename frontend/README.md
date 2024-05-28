## How to run (Docker)

1 - Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

2 - Create Docker Network:

```bash
docker network create backup-manager-local
```

3 - Install the dependencies:

```bash
docker compose up -d
```

4 - Open the browser and access the URL:

```bash
open http://localhost:3000
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
