# Geomiq Local Environment Setup Scripts

# HELP
# This will output the help for each task
.PHONY: help

help: ## This help.
	@sh .motd
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

# TASKS
start: ## Build and start the environment.
	docker-compose up -d --build
	docker exec api composer install
	docker exec api php artisan key:generate
	docker exec api php artisan migrate
	docker exec frontend npm install
	docker exec frontend npm run build
	@echo ""
	@echo ""
	@echo "Environment Ready"
	@echo ""
	@echo "API: http://localhost:3000"
	@echo "FrontEnd: http://localhost:4000"
	@echo "Swagger API Docs: http://localhost:5000"
stop: ## Stop/pause the environment.
	docker-compose stop
refresh: ## Refresh the environment without loosing images nor volumes.
	docker-compose down -v
	docker-compose up -d --build
destroy: ## Destroy and clean the environment.
	docker-compose down -v --remove-orphans
