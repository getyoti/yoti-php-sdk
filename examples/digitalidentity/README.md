# Profile Example

## Requirements

This example requires [Docker](https://docs.docker.com/)

## Setup

* Create your application in the [Yoti Hub](https://hub.yoti.com) (this requires having a Yoti account)
  * Set the application domain of your app to `localhost:4002`
  * Set the scenario callback URL to `/digitalidentity`
* Do the steps below inside the [examples/digitaledentity](./) folder
* Put `your-application-pem-file.pem` file inside the [keys](keys) folder, as Docker requires the `.pem` file to reside within the same location where it's run from.
* Copy `.env.example` to `.env`
* Open `.env` file and fill in the environment variables `YOTI_SCENARIO_ID`, `YOTI_SDK_ID`
  * Set `YOTI_KEY_FILE_PATH` to `./keys/your-application-pem-file.pem`
* Install dependencies `docker-compose up composer`
* Run the `docker-compose up --build` command
* Visit [https://localhost:4002](https://localhost:4002)
* Run the `docker-compose stop` command to stop the containers.

> To see how to retrieve activity details using the one time use token, refer to the [digitalidentity controller](app/Http/Controllers/IdentityController.php)

## Digital Identity Example
* Visit [/generate-share](https://localhost:4002/generate-share)

> To see how to create a dynamic scenario, refer to the [Digital Identity controller](app/Http/Controllers/IdentityController.php)
