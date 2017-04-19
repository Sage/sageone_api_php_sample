#!/usr/bin/env bash

export NGINX_WEB_ROOT=${NGINX_WEB_ROOT:-'/var/www/html'}
export NGINX_PHP_FALLBACK=${NGINX_PHP_FALLBACK:-'/index.php'}
export NGINX_PHP_LOCATION=${NGINX_PHP_LOCATION:-'^/(index|callback|sageone_data|sageone_response)\.php(/|$)'}
export NGINX_USER=${NGINX_USER:-'www-data'}
export NGINX_CONF=${NGINX_CONF:-'/etc/nginx/nginx.conf'}

export PHP_SOCK_FILE=${PHP_SOCK_FILE:-'/run/php.sock'}
export PHP_USER=${PHP_USER:-'www-data'}
export PHP_GROUP=${PHP_GROUP:-'www-data'}
export PHP_MODE=${PHP_MODE:-'0660'}
export PHP_FPM_CONF=${PHP_FPM_CONF:-'/etc/php/7.0/fpm/php-fpm.conf'}

envsubst '${NGINX_WEB_ROOT} ${NGINX_PHP_FALLBACK} ${NGINX_PHP_LOCATION} ${NGINX_USER} ${NGINX_CONF} ${PHP_SOCK_FILE} ${PHP_USER} ${PHP_GROUP} ${PHP_MODE} ${PHP_FPM_CONF}' < /tmp/nginx.conf.tpl > $NGINX_CONF
envsubst '${NGINX_WEB_ROOT} ${NGINX_PHP_FALLBACK} ${NGINX_PHP_LOCATION} ${NGINX_USER} ${NGINX_CONF} ${PHP_SOCK_FILE} ${PHP_USER} ${PHP_GROUP} ${PHP_MODE} ${PHP_FPM_CONF}' < /tmp/php-fpm.conf.tpl > $PHP_FPM_CONF

TRAPPED_SIGNAL=false

echo 'Starting NGINX';
nginx -c $NGINX_CONF  -g 'daemon off;' 2>&1 &
NGINX_PID=$!

echo 'Starting PHP-FPM';
php-fpm7.0 -R -F -c $PHP_FPM_CONF 2>&1 &
PHP_FPM_PID=$!

trap "TRAPPED_SIGNAL=true; kill -15 $NGINX_PID; kill -15 $PHP_FPM_PID;" SIGTERM  SIGINT

while :
do
    kill -0 $NGINX_PID 2> /dev/null
    NGINX_STATUS=$?

    kill -0 $PHP_FPM_PID 2> /dev/null
    PHP_FPM_STATUS=$?

    if [ "$TRAPPED_SIGNAL" = "false" ]; then
        if [ $NGINX_STATUS -ne 0 ] || [ $PHP_FPM_STATUS -ne 0 ]; then
            if [ $NGINX_STATUS -eq 0 ]; then
                kill -15 $NGINX_PID;
                wait $NGINX_PID;
            fi
            if [ $PHP_FPM_STATUS -eq 0 ]; then
                kill -15 $PHP_FPM_PID;
                wait $PHP_FPM_PID;
            fi

            exit 1;
        fi
    else
       if [ $NGINX_STATUS -ne 0 ] && [ $PHP_FPM_STATUS -ne 0 ]; then
            exit 0;
       fi
    fi

	sleep 1
done
