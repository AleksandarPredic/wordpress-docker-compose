# WPDC - WordPress Docker Compose

Easy WordPress development with Docker and Docker Compose.

With this project you can quickly run the following:

- [WordPress and WP CLI](https://hub.docker.com/_/wordpress/)
- [phpMyAdmin](https://hub.docker.com/r/phpmyadmin/phpmyadmin/)
- [MySQL](https://hub.docker.com/_/mysql/)
- [Mailhog](https://hub.docker.com/r/mailhog/mailhog/)
- [composer2](https://hub.docker.com/_/composer)
- [node15](https://hub.docker.com/_/node)

Contents:

- [Requirements](#requirements)
- [Configuration](#configuration)
- [Installation](#installation)
- [Usage](#usage)

## Requirements

Make sure you have the latest versions of **Docker** and **Docker Compose** installed on your machine.

Clone this repository or copy the files from this repository into a new folder. In the **docker-compose.yml** file you may change the IP address (in case you run multiple containers) or the database from MySQL to MariaDB.

Make sure to [add your user to the `docker` group](https://docs.docker.com/install/linux/linux-postinstall/#manage-docker-as-a-non-root-user) when using Linux.

## Configuration

Copy the example environment into `.env`

```
cp env.example .env
```

Edit the `.env` file to change the default IP address, MySQL root password and WordPress database name.

## Installation

Open a terminal and `cd` to the folder in which `docker-compose.yml` is saved and run:

```
docker-compose up
```

This creates two new folders next to your `docker-compose.yml` file.

* `wp-data` – used to store and restore database dumps
* `wp-app` – the location of your WordPress application

The containers are now built and running. You should be able to access the WordPress installation with the configured IP in the browser address. By default it is `http://127.0.0.1`.

For convenience you may add a new entry into your hosts file.

## Usage

### Using scripts to start, stop or interact with containers

You can easily use scripts in the `script` folder which contain a easier way to execute some of the 
commands mentioned below.

### Starting containers

You can start the containers with the `up` command in daemon mode (by adding `-d` as an argument) or by using the `start` command:

```
docker-compose start
```

### Stopping containers

```
docker-compose stop
```

### Removing containers

To stop and remove all the containers use the`down` command:

```
docker-compose down
```

Use `-v` if you need to remove the database volume which is used to persist the database:

```
docker-compose down -v
```

### Remove containers and volumes
```
docker-compose rm -sv
```


### Project from existing source

Copy the `docker-compose.yml` file into a new directory. In the directory you create two folders:

* `wp-data` – here you add the database dump
* `wp-app` – here you copy your existing WordPress code
* `wp-source` – here you can keep your git repository and link it with wp-app using volumes

You can now use the `up` command:

```
docker-compose up
```

This will create the containers and populate the database with the given dump. You may set your host entry and change it in the database, or you simply overwrite it in `wp-config.php` by adding:

```
define('WP_HOME','http://wp-app.local');
define('WP_SITEURL','http://wp-app.local');
```

### Creating database dumps

```
./scripts/export.sh
```

### Import DB script
```
./scripts/import.sh {wp-data/filename.sql}
```

### Accessing db container bash
```
docker-compose exec db bash
```

### Developing a Theme

Configure the volume to load the theme in the container in the `docker-compose.yml`:

```
volumes:
  - ./theme-name/trunk/:/var/www/html/wp-content/themes/theme-name
```

### Developing a Plugin

Configure the volume to load the plugin in the container in the `docker-compose.yml`:

```
volumes:
  - ./plugin-name/trunk/:/var/www/html/wp-content/plugins/plugin-name
```

### WP CLI

The docker compose configuration also provides a service for using the [WordPress CLI](https://developer.wordpress.org/cli/commands/).

Sample command to install WordPress:

```
docker-compose run --rm wpcli core install --url=http://localhost --title=test --admin_user=admin --admin_email=test@example.com
```

Or to list installed plugins:

```
docker-compose run --rm wpcli plugin list
```

For an easier usage you may consider adding an alias for the CLI:

```
alias wp="docker-compose run --rm wpcli"
```

This way you can use the CLI command above as follows:

```
wp plugin list
```

### phpMyAdmin

You can also visit `http://127.0.0.1:8080` to access phpMyAdmin after starting the containers.

The default username is `root`, and the password is the same as supplied in the `.env` file.

### Mailhog

You can also visit `http://127.0.0.1:8025` to access phpMyAdmin after starting the containers.

The important part is the WP configuration for the Mailhog, added in MU plugins via wp container volume.

### Follow logs
```
docker-compose logs -f wp
```

Additionally you should add this to the `wp-config.php` file:
```PHP
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
define('SCRIPT_DEBUG', true);
define('SAVEQUERIES', true);
define('WP_DISABLE_FATAL_ERROR_HANDLER', true);
define( 'WP_AUTO_UPDATE_CORE', false );
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

#### Follow only WordPress PHP logs
```
docker-compose logs -f wp | grep --line-buffered -i -E --color "php7:"
```

### Composer

The docker compose configuration also provides a service for using the composer

Example command:
```
docker-compose run --rm composer2 composer install --ignore-platform-reqs -d "/var/www/html/wp-content/plugins/plugin-name/"
```

### Node

The docker compose configuration also provides a service for using the nodejs

Example command:
```
docker-compose run --rm node15 bash -c "cd /var/www/html/wp-content/themes/theme-name/ && npm install"
```

### Sage 9 deployment pipeline docker image

This docker compose configuration also include `sage9` service allow you for various usage scenarios 
if you have sage 9 theme.

Fire up the bash with the command and test what you need to test
```
docker-compose run --rm yarn bash
```