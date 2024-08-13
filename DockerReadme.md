Docker: 

To build a docker instance

# build an instance with a tag name otm, without puppeteer
docker build -t otm .

# build an instance with a tag name otmp, with puppeteer (NB: this does not work)
docker build -t otmp . -f ./Dockerfile.puppeteer

With the Dockerfile and docker-compose.yml you start a container that provides all services to run on the port specified in docker-compose.yml for the webserver (nginx, i.e. localhost:8081, and you can run PMA on localhost:8080).  These ports must be available.

Docker Compose:

docker-compose up: Starts, stops, and rebuilds services as required based on the configuration in the docker-compose.yml file.
docker-compose down: Stops and removes containers, networks, volumes, and images created by docker-compose up.
docker-compose build: Builds the services defined in the docker-compose.yml file.
docker-compose run <service_name>: Runs a one-off command on a service.
docker-compose exec app bash: Opens a bash shell in the service named "app" to run commands within the service container.
These commands help manage and orchestrate Docker containers, images, and services in your Docker environment.
