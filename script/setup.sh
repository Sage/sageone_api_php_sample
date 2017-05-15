#!/bin/sh

echo Building docker image ...
docker build --rm -t sageone_api_php_sample -f Dockerfile .

echo Setup complete.
