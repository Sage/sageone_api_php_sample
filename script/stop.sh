#!/bin/sh

echo Stopping container ...
docker stop sageone_api_php_sample
docker rm sageone_api_php_sample
