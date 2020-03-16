# Doc Scan Example

## Requirements
- PHP >= 7.2
- [Composer](https://getcomposer.org/)

## Setup

* Create your application in the [Yoti Hub](https://hub.yoti.com) (this requires having a Yoti account)
* Do the steps below inside the [examples folder](https://github.com/getyoti/php/tree/master/examples)
* Put `your-application-pem-file.pem` file inside the [keys](keys) folder, as Docker requires the `.pem` file to reside within the same location where it's run from.
* Copy `.env.dist` to `.env`
* Open `.env` file and fill in the environment variables `YOTI_SDK_ID`, `YOTI_KEY_FILE_PATH`
  * Set `YOTI_KEY_FILE_PATH` to `./keys/your-application-pem-file.pem`
* Run the `php artisan serve --port=4003` command
* Visit [https://localhost:4003](https://localhost:4003)
