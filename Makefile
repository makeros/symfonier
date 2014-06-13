# make all tests
test:
	php vendor/phpunit/phpunit/phpunit -c app/

install-dev:
	cd web/ && ln -fs .htaccess_dev .htaccess

install-prod:
	cd web/ && ln -fs .htaccess_prod .htaccess

install-config:
	cd app/config/ && cp parameters.yml.dist parameters.yml

install:
	# TODO: need here to check where composer is installed - arek
	composer.phar install
	
build: install install-prod test