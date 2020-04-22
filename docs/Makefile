serve-docs:
	pipenv run mkdocs serve

build-docs:
	pipenv run mkdocs build
	mv ./site/index.html ./site/home.html
	echo "<?php header( 'Location: /home.html' ) ; ?>" > ./site/index.php
	echo "{}" > ./site/composer.json