# ==========================================================
# Task Manager Laravel - Makefile
# ==========================================================

DOCKER_ENV_FILE=docker/.env
DOCKER_DEV_COMPOSE=docker/dev/docker-compose.yml
DOCKER_PROD_COMPOSE=docker/prod/docker-compose.yml
DOCKER_IMAGE_NAME=task-manager-laravel
POWERSHELL=powershell -NoProfile -ExecutionPolicy Bypass -Command

.DEFAULT_GOAL := help

help:
	@echo ""
	@echo "Task Manager Laravel - Makefile"
	@echo ""
	@echo "Development"
	@echo "  make dev-up           Start DEV environment"
	@echo "  make dev-down         Stop DEV environment"
	@echo "  make dev-down-vol     Stop DEV environment and remove volumes"
	@echo "  make dev-restart      Restart DEV environment"
	@echo "  make dev-logs         Show DEV application logs"
	@echo "  make dev-build        Build DEV Docker image"
	@echo ""
	@echo "Production"
	@echo "  make prod-up          Start PROD environment"
	@echo "  make prod-down        Stop PROD environment"
	@echo "  make prod-down-vol    Stop PROD environment and remove volumes"
	@echo "  make prod-logs        Show PROD application logs"
	@echo "  make prod-build       Build PROD Docker image"
	@echo ""

ensure-docker-env:
	@$(POWERSHELL) "if (-not (Test-Path '$(DOCKER_ENV_FILE)')) { Copy-Item 'docker/.env.example' '$(DOCKER_ENV_FILE)' }"

dev-up: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_DEV_COMPOSE) up -d --build

dev-down: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_DEV_COMPOSE) down

dev-down-vol: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_DEV_COMPOSE) down -v

dev-restart: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_DEV_COMPOSE) restart

dev-logs: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_DEV_COMPOSE) logs -f task-manager-app

dev-build:
	docker build --target dev -t $(DOCKER_IMAGE_NAME):dev .

prod-up: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_PROD_COMPOSE) up -d --build

prod-down: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_PROD_COMPOSE) down

prod-down-vol: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_PROD_COMPOSE) down -v

prod-logs: ensure-docker-env
	docker compose --env-file $(DOCKER_ENV_FILE) -f $(DOCKER_PROD_COMPOSE) logs -f task-manager-app

prod-build:
	docker build --target prod -t $(DOCKER_IMAGE_NAME):prod .
