#!/bin/bash

./vendor/bin/sail artisan migrate:fresh

./vendor/bin/sail artisan db:seed --class=ShowcaseSeeder
