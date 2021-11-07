#!/bin/bash
_file=$1

if [ -z $_file ]; then
	echo "Please provide filename of .sql file"
	exit 0
fi

# Import .sql file
IMPORT_COMMAND='exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"'
docker-compose exec -T db sh -c "$IMPORT_COMMAND" < $_file