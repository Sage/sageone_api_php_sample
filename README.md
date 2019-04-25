# Sage Accounting API Sample application (PHP)

##### Note: Request signing and noncing (the X-Signature and X-Nonce headers) is no longer checked in v3. The related code will soon be removed from this repo.

Sample PHP project that integrates with Sage Accounting via the Sage Accounting API.

* Authentication and API calls are handled in [sageone_client.php](sageone_client.php)

## Setup

Clone the repo:

`git clone git@github.com:Sage/sageone_api_php_sample.git`

Update the [sageone_constants.php](sageone_constants.php) file with your application's `client_id` and `client_secret`.

Switch to the project directory to run the subsequent commands:

```
cd sageone_api_php_sample
```

## Run the app locally

Start a local PHP server:

```
php -S localhost:8080
```

## Run the app in Docker

Build the image:

```
./script/setup.sh
```

Start the container:

```
./script/start.sh
```

## Usage

You can now access the [home page](http://localhost:8080/), authorize and make an API call.
