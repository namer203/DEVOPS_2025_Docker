
# DEVOPS 2025 - Automating deployment of application stacks
## Intro

This repository is about automating deployment of our whole application stack - last time we did it with Vagrant + Ansible and cloud-init + Multipass. This time, it'll be with **Docker Compose**.

The web app we're using is from https://github.com/namer203/webapp. The code isn't the same, as there's a couple of needed changes - like changing the host name for database. The whole process and changes are written down below.

##  The web app

The web app consists of multiple .php files, one .sql, one .css and 4 .js files. The .php files are for loading the website pages and database, .sql for injecting and storing the data, .css for design and .js files for game logic.

## Docker Compose
We'll run **4 services/containers** for the web app to work. Below is a quick recap of what we did - a more detailed explaination is written under **docker-compose.yaml** titl:.
- For the web server and PHP, we used **Nginx** and a **custom PHP image** built using a **two-stage Docker build**.
  - The first stage is the **build stage**, where the image downloads and installs all the necessary tools required to compile and prepare the files we need.
  - The second (**final**) stage contains **only the built files** and excludes all build-time tools. This approach ensures that the final image remains **minimal, lightweight, and secure**.
  - For nginx, the image is nginx:alpine, which will be pulled from the Docker Hub. A modified **`default.conf`** file is included to configure the server behavior. We also mount `./app` to `/var/www/html` which enables us to instantly change the content while it's running.
- Our **database** uses the `mysql:5.7` image. We put the db in our users environment (username, db name, passworwd) and add a volume to store data.
- To cache and handle sessions, the app uses **redis**. The redis image is `redis:alpine`. We added a redis volume to store data and port forwarded port 6379.


**TL:DR**; Services/containers included in the **`docker-compose.yaml`** file are:
- **Nginx** - nginx:alpine image
- **Php** - custom built image with 2 stages
- **Db** - mysql:5.7 image
- **Redis** - redis:alpine image

## docker-compose.yaml
### nginx:
- Uses image `nginx:alpine`, sets the service name as "nginx".
- Port forwards ports 8080 -> 80. 
- Mounts local `./app` folder to `/var/www/html` and `default.conf` file for nginx. 
- Before the nginx service starts, it runs php because of `depends_on: - php`.

The config file for nginx is `nginx/default.conf`:
- Defines virtual server configuration.
- Listens on port 80, sets root directory to `/var/www/html`, uses `index.php` as "default".
- Handles all incoming requests to root path / (`location / {`)
- When it gets a request, it checks if the file exists - if it doesn't, it replies with `index.php`.
- Then `location ~ \-php$ {` matches all .php requests. 
- Loads FastCGI parameters which are required for php-fpm. 
- Sends php requests to php-fpm service named php via port 9000.
- Tells the exact file path of the script to execute - combines document root and requrested file.

### php:
Uses custom built image `docker/php/Dockerfile`. Sets the service name as "php". The Dockerfile has **2 stages - build and final**:

**Build stage:**
- Uses `php:8.2-fpm` image as builder.
- Installs build dependencies and libraries, tools to compile php extensions and removes package lists to reduce image size. 
- Installs php extensions, redis extension + enables redis. 
- Uses another image for composer binary (`composer:2`) and copies it into the image.
- Sets working directory to `/var/www/html`
- Copies composer files to cache dependency installations - they'll be reinstalled only if the files change.
- Installs dependencies, while excluding dev packages.
- Copies app files.
- Changes ownership so php-fpm can read/write app files on the server (`/var/www/html`)

**Final stage:**
- Starts a fresh, clean image `php:8.2-fpm` which doesn't include builds tools and the Composer
- Copies built files - php configurations, php extensions, app code and dependencies - from builder. 
- Sets working directory `/var/www/html`.
- Sets permissions again like before.
- Exposes port 9000 for php-fpm which is used by nginx via FastCGI.

### db:
- Uses image `mysql:5.7` - It's an older image as we had problems with TLS/SSL user authentication in newer versions.
- Defines the service as "db" which is used by `db.sql` as hostname.
- Sets variables that'll be used by the sql image during startup.
  - Database name: igra_app
  - Database user: igra_user
  - Database password: geslo
  - Database root password: rootpassword
- Mounts a named Docker volume `db_data` to `/var/lib/mysql`- database data persists while the container is stopped/removed.
- Mounts a local directory into mysql initialization directory. This is so the `.sql` file is executed on startup and injects the tables into the database.

### redis:
- Defines the service as "redis", uses image `redis:alpine`. 
- Maps port 6379 to 6379 which allows the app to connect to the service.
- Mounts a named volume `redis_data` to `/data` in the container - it's where it stores persistent data. With this we ensure the Redis data isn't lost when we stop the container.
