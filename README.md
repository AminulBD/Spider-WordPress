# Spider

Grab latest feed from search engine like Google, Bing, WordPress, etc.

## Develop with Docker
Change variables in the `.env.docker` file then run below command.
```shell
docker run --rm --env-file $(pwd)/.env.docker -p 8080:80 -v $(pwd):/var/www/html/wp-content/plugins/spider wordpress:php7.4-apache
```

## Install composer and wp-cli
```shell
# get container id
docker exec -it <container_id> bash

# update apt and install zip, unzip, curl, wget
apt update
apt install zip unzip curl wget -y

# install composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# install wp-cli
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp
```

Now you can access the site by visiting: `http://localhost`

## Todo
- [ ] create admin ui
- [ ] automatically publish into WordPress
- [ ] cli support
