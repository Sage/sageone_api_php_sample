# Sage Business Cloud Accounting API Sample application (PHP)

Sample PHP project that integrates with Sage Accounting via the Sage Accounting API.

* Authentication and API calls are handled in [lib/api_client.php](lib/api_client.php)

## Setup

Clone the repo:

`git clone git@github.com:Sage/sageone_api_php_sample.git`

Switch to the project directory to run the subsequent commands:

```
cd sageone_api_php_sample
```

## Run the app locally

Start a local PHP server:

```
php -S localhost:8080
```

Then follow the instructions in the browser.

## Run the app in Docker

Build the image:

```
./script/setup.sh
```

Start the container:

```
./script/start.sh
```

If you need, stop.sh will stop the container:

```
./script/stop.sh
```

## Usage

You can now access [http://localhost:8080/](http://localhost:8080/), authorize and make an API call. Depending on your setup, it could also be [http://192.168.99.100:8080/](http://192.168.99.100:8080/) or similar.

## License

This sample application is available as open source under the terms of the
[MIT licence](LICENSE).

Copyright (c) 2019 Sage Group Plc. All rights reserved.
