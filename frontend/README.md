## How to run (Docker)

1 - Setup Node Version Manager (NVM):

```bash
nvm install && nvm use
```

2 - Install the dependencies:

```bash
 && npm install
```

3 - Copy the `.env.example` file to `.env`:

```bash
cp .env.example .env
```

4 - Create Docker Network:

```bash
docker network create backup-manager-local
```

5 - Start the container:

```bash
docker compose up -d
```

6 - Open the browser and access the URL:

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
