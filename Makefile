PROJECT_ROOT := $(shell pwd)
NETWORK_NAME := test-task-net
DB_CONTAINER_NAME := database
DB_USERNAME := $(shell grep '^DB_USERNAME=' .env | cut -d '=' -f 2)
DB_PASSWORD := $(shell grep '^DB_PASSWORD=' .env | cut -d '=' -f 2)
DB_DATABASE := $(shell grep '^DB_DATABASE=' .env | cut -d '=' -f 2)

init:
	cp .env.example .env
	docker network ls | grep -q $(NETWORK_NAME) || docker network create $(NETWORK_NAME)
	docker-compose build
	docker-compose up -d
	docker run --rm -v $(PROJECT_ROOT):/app composer install
	docker exec -it "app" php artisan key:generate

	# Retry mechanism for creating the MySQL database
	set -e; for i in {1..5}; do \
	    docker exec "$(DB_CONTAINER_NAME)" bash -c 'mysql -u$(DB_USERNAME) -p$(DB_PASSWORD) --protocol=tcp -h "$(shell docker inspect -f '{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $(DB_CONTAINER_NAME))" -e "CREATE DATABASE IF NOT EXISTS  $(DB_DATABASE);"' && break || sleep 2; \
	done;

	docker exec -it "app" php artisan migrate --seed
