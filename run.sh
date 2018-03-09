#!/bin/bash
composer dump-autoload
php bin/console cache:clear
php bin/console server:run