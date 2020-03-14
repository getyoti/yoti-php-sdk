# Profile Example

## Requirements

This example requires [Docker](https://docs.docker.com/)

## Setup

* Create your application in the [Yoti Hub](https://hub.yoti.com) (this requires having a Yoti account)
  * Set the application domain of your app to `localhost:4002`
  * Set the scenario callback URL to `/profile`
* Do the steps below inside the [examples folder](https://github.com/getyoti/php/tree/master/examples)
* Put `your-application-pem-file.pem` file inside the [keys](keys) folder, as Docker requires the `.pem` file to reside within the same location where it's run from.
* Copy `.env.dist` to `.env`
* Open `.env` file and fill in the environment variables `YOTI_SCENARIO_ID`, `YOTI_SDK_ID`
  * Set `YOTI_KEY_FILE_PATH` to `./keys/your-application-pem-file.pem`

## Running the example

* Run the `docker-compose up -d --build` command and navigate to [https://localhost:4002](https://localhost:4002)
* Run the `docker-compose stop` command to stop the containers.
