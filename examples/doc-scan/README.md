# Doc Scan Example

## Requirements

This example requires [Docker](https://docs.docker.com/)

## Setup

* Create your application in the [Yoti Hub](https://hub.yoti.com) (this requires having a Yoti account)
* Do the steps below inside the [examples/doc-scan](./) folder
* Put `your-application-pem-file.pem` file inside the [keys](keys) folder, as Docker requires the `.pem` file to reside within the same location where it's run from.
* Copy `.env.example` to `.env`
* Open `.env` file and fill in the environment variables `YOTI_SDK_ID`, `YOTI_KEY_FILE_PATH`
  * Set `YOTI_KEY_FILE_PATH` to `./keys/your-application-pem-file.pem`
* Install dependencies `docker-compose up composer`
* Run the `docker-compose up --build web` command
* Visit [https://localhost:4003](https://localhost:4003)
* Run the `docker-compose stop` command to stop the containers.

* _The [default controller](./app/Http/Controllers/HomeController.php) demonstrates how to create a Doc Scan session_
* _The [success controller](./app/Http/Controllers/SuccessController.php) demonstrates how to retrieve a Doc Scan session_
