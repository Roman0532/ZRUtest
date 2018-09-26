init-project:
	docker-compose	up  --build  -d
	docker	exec	zrutest_app_1	composer	install
	docker	exec	zrutest_app_1	php	artisan 	migrate
	docker	exec	zrutest_app_1	php	artisan 	db:seed
	docker	exec	zrutest_app_1	php	artisan 	config:cache
	docker	exec	zrutest_app_1	php	artisan 	config:clear
	docker	exec	zrutest_app_1	chmod 777 -R storage/