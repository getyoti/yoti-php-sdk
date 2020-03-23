# Doc Scan Example

## Requirements

This example requires [Docker](https://docs.docker.com/)

## Setup

* Create your application in the [Yoti Hub](https://hub.yoti.com) (this requires having a Yoti account)
* Do the steps below inside the [examples/doc-scan](./) folder
* Put `your-application-pem-file.pem` file inside the [keys](keys) folder, as Docker requires the `.pem` file to reside within the same location where it's run from.
* Copy `.env.dist` to `.env`
* Open `.env` file and fill in the environment variables `YOTI_SDK_ID`, `YOTI_KEY_FILE_PATH`
  * Set `YOTI_KEY_FILE_PATH` to `./keys/your-application-pem-file.pem`
* Run the `docker-compose up -d --build` command
* Visit [https://localhost:4003](https://localhost:4003)
* Run the `docker-compose stop` command to stop the containers.
