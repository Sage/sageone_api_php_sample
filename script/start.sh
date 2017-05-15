#!/bin/sh

echo Starting container ...
docker run -d --name sageone_api_php_sample -p 8080:80 --volume=`pwd`:/var/www/html sageone_api_php_sample
