# Spider

Grab latest feed from search engine like Google, Bing, WordPress, etc.

## Develop with Docker
Change variables in the `.env.docker` file then follow instruction below.


### 1. Build docker image

```shell
docker build -t wordpress:swp .
```

### 2. Run docker container

```shell
docker run --rm --env-file $(pwd)/.env.docker -p 8080:80 -v $(pwd):/var/www/html/wp-content/plugins/spider wordpress:swp
```

Now you can access the site by visiting: `http://localhost`

## Todo
- [ ] create admin ui
- [ ] automatically publish into WordPress
- [ ] cli support
