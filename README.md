# Sage One API Sample application (PHP)

### FOR changes required for v3 please refer to the [v3 branch](https://github.com/Sage/sageone_api_php_sample/tree/v3)

##### Note: Request signing and noncing (the X-Signature and X-Nonce headers) is no longer checked in v3. The related code will soon be removed from this repo.

Sample PHP project that integrates with Sage One Accounting via the Sage One API.

* Authentication and API calls are handled in  [sageone_client.php](sageone_client.php)
* Request signing is handled in [sageone_signer.php](sageone_signer.php).
* Callback is handled in [callback.php](callback.php), point the callback_url here.

## Run the app locally

Clone the repo:

`git clone git@github.com:Sage/sageone_api_php_sample.git`

Update the [sageone_constants.php](sageone_constants.php) file with your application's `client_id`, `client_secret`, `signing_secret` and `callback_url`.

Switch to the project directory and start a local PHP server:

```
cd sageone_api_php_sample
php -S localhost:8000
```

You can now access the [home page](http://localhost:8000/), authorize and make an API call.
