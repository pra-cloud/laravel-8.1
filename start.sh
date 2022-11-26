#!/bin/bash

cd /var/www/html/

php artisan mq:consume
