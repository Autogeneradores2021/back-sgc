up:
	docker-compose up -d
build:
	docker-compose build --no-cache --force-rm
rebuild:
	@make destroy
	@make build
reset:
	@make clean-volumes
	@make build
stop:
	docker-compose stop && docker system prune
down:
	docker-compose down --remove-orphans
restart:
	@make down
	@make up
destroy:
	docker-compose down --rmi all --volumes --remove-orphans
clean-volumes:
	docker-compose down --volumes --remove-orphans
ps:
	docker-compose ps
logs:
	docker-compose logs -f
bash:
	docker exec -it back-sgc bash
php:
	docker exec -it back-sgc php
composer:
	docker exec -it back-sgc composer install
artisan:
	docker exec -it back-sgc php artisan
migrate:
	docker exec -it back-sgc php artisan migrate
fresh:
	docker exec -it back-sgc php artisan migrate:fresh --seed
seed:
	docker exec -it back-sgc php artisan db:seed
dacapo:
	docker exec -it back-sgc php artisan dacapo
rollback-test:
	docker exec -it back-sgc php artisan migrate:fresh
	docker exec -it back-sgc php artisan migrate:refresh
test:
	docker exec -it back-sgc php artisan test
optimize:
	docker exec -it back-sgc php artisan optimize
optimize-clear:
	docker exec -it back-sgc php artisan optimize:clear
cache:
	docker exec -it back-sgc composer dump-autoload -o
	@make optimize
	docker exec -it back-sgc php artisan event:cache
	docker exec -it back-sgc php artisan view:cache
cache-clear:
	docker exec -it back-sgc composer clear-cache
	@make optimize-clear
	docker exec -it back-sgc php artisan event:clear
db:
	docker exec -it db bash