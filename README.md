# Spider

Grab latest feed from search engine like Google, Bing, WordPress, etc.

## Develop with Docker
Change variables in the `.env.docker` file then run below command.

```shell
# 1. build docker image
docker build -t wordpress:swp .

# 2. run docker container and use the image we just built
docker run --rm --env-file $(pwd)/.env.docker -p 8080:80 -v $(pwd):/var/www/html/wp-content/plugins/spider wordpress:swp
```

Now you can access the site by visiting: `http://localhost`

## Todo
- [ ] create admin ui
- [ ] automatically publish into WordPress
- [ ] cli support
