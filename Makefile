.PHONY: coverage cs infection integration it test

it: cs test

coverage: vendor
	vendor/bin/phpunit --configuration=tests/phpunit.xml --coverage-text

cs: vendor
	vendor/bin/php-cs-fixer fix --config=.php_cs --diff --verbose

infection: vendor
	vendor/bin/infection --min-covered-msi=80 --min-msi=80

test: vendor
	vendor/bin/phpunit --configuration=tests/phpunit.xml tests

#vendor/bin/phpunit --configuration=test/Unit/phpunit.xml
#vendor/bin/phpunit --configuration=test/Integration/phpunit.xml

vendor: composer.json composer.lock
	composer self-update
	composer validate
	composer install
