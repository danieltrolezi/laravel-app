shell:
	docker exec -it app.laravel-app bash

migration:
	docker exec -it app.laravel-app php artisan migrate:refresh --seed

phpcs:
	docker exec -it app.laravel-app composer phpcs

phpcbf:
	docker exec -it app.laravel-app composer phpcbf

test:
	docker exec -it app.laravel-app composer test

test-coverage:
	docker exec -it app.laravel-app composer test:coverage