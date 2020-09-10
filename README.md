# Geomiq Quote System 

### Running the environment

* Navigate to the folder named docker and run the command `make` this will give you a list of commands you can run i.e. `make start` which is the command you will most likely only need to use to run the environment
* If not running on a unix based machine then you can open the file called `Makefile` inside the docker container and run the commands one by one that are listed under the start

### Viewing the application
* The frontend will be visible at https://localhost:4000
* The Api docs will be available at http://localhost:5000
* The APi will be available at http://localhost:3000 but will not be accessed nor has any fronend part to it

### Project structure
* The Frontent and the backend have been decoupled. The backend acts as an api and the frontend consumes this api to achieve sending leads and storing the quotes
* This application has been containerized and split into the following containers:
1. Php container for the api
2. Node container for the frontend
3. Production database for store data
4. Testing database to test in isolation with a clean database
5. An nginx container for serving requests
6. A swagger container for displaying the Api documentation

### Notes

* For the frontend I was unable to load a thumbnail of the uploaded file but as you can see in the vuejs file I attempted it and it seems like the file gets loaded with threejs but does not render for some reason
* For the backend I followed a TDD approach for building the logic of the application and have also included 2 more endpoints which are not used currently by the frontend for fetching all quotes as well as quotes by the id
* I created Api documentation for all the endpoints that are available together with examples
* Http Resources have been used for formatting data from the database to be returned by the APi

### Important
**Environment variables should never be pushed to production over git, this has only been done here for simplicity and should always be avoided**