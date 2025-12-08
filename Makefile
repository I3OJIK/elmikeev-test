env:
	cp .env.example .env
build:
	docker compose build
install:
	docker compose exec php composer install -o
up:
	docker compose up -d
down:
	docker compose down --remove-orphans
migrate:
	docker compose exec php php artisan migrate:fresh
# запуск Supervisor
supervisor-start:
	docker compose exec -u root php service supervisor start 
	docker compose exec -u root php supervisorctl reread
	docker compose exec -u root php supervisorctl update
	docker compose exec -u root php supervisorctl start laravel-sync-worker:*

# Перезагрузка воркера
restart-supervisor-worker:
	docker compose exec php supervisorctl restart laravel-sync-worker:*

setup:
	$(MAKE) env;
	$(MAKE) build;
	$(MAKE) up;
	$(MAKE) install;
	$(MAKE) migrate;
	$(MAKE) supervisor-start;
	@echo "Setup complete"