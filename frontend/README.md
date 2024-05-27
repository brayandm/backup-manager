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