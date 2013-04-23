test:
	./vendor/bin/phpunit -c ./

code-coverage:
	./vendor/bin/phpunit -c ./ --coverage-html=./docs/generated/code-coverage
	open ./docs/generated/code-coverage/index.html
