serve-docs:
	pipenv run mkdocs serve

build-docs:
	pipenv run mkdocs build
	mv ./site/index.html ./site/home.html
	echo "<?php include_once(“home.html”); ?>" > ./site/index.php
	echo "{}" > ./site/composer.json