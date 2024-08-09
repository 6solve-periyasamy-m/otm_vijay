Docker Compose:

docker-compose up: Starts, stops, and rebuilds services as required based on the configuration in the docker-compose.yml file.
docker-compose down: Stops and removes containers, networks, volumes, and images created by docker-compose up.
docker-compose build: Builds the services defined in the docker-compose.yml file.
docker-compose run <service_name>: Runs a one-off command on a service.
docker-compose exec app bash: Opens a bash shell in the service named "app" to run commands within the service container.
These commands help manage and orchestrate Docker containers, images, and services in your Docker environment.
