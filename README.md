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

## Rebuilding docker wp image after changes

If you need to update the PHP version of the docker image used for wp, edit the Dockerfile in `config/php/Dockerfile` and
then run `docker-compose up -d --build wp`

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

### Accessing WP container bash
```
docker-compose exec wp bash
```

### Accessing DB container bash
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

### Composer

Access the `wp` container bash while you have your project started. `docker-compose exec wp bash`.
Execute composer commands.

### WP CLI

Access the `wp` container bash while you have your project started. `docker-compose exec wp bash`.
Execute wp cli commands.

* https://developer.wordpress.org/cli/commands/

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

## xdebug configuration

In the `docker-compose.yml`, under `wp` container volumes, we have disabled `./config/php/conf.d/xdebug-off.ini:/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini`
xdebug by the default.

To enable it, uncomment the line above which adds `xdebug-on.ini` config and reboot.

You will not have the `xdebug` enabled.

### Configuring PHPStorm for xdebug

1. Go to the preferences -> PHP -> debug and check Break at first line PHP script.
2. Start listening for the incoming connections in PHPStorm (the phone icon on the top need to be enabled).
3. The PHPStorm will auto-initiate server settings first time it detect the xdebug request.
4. Go to the preferences -> PHP -> servers, find the automatically added server, and map the paths to the docker server.
   1. Look for trhe 127.0.0.1 server
      * Name is: 127.0.0.1
      * Host is: 127.0.0.1
      * Port: 80
      * Debugger: Xdebug
      * Use path mappings: checked
      * Below you need to map the paths to the docker server. Hint: `wp-app` folder will be mapped to `/var/www/html`
5. Start debugging by adding breakpoint into your application. If this doesn't work, check the mappings.

## Useful links
* https://matthewsetter.com/setup-step-debugging-php-xdebug3-docker/