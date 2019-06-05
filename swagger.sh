#!/bin/bash
vendor/zircote/swagger-php/bin/openapi app/Http/Controllers/Api/*Controller.php -o swagger.yaml
if [ ! -e ./node_modules/.bin/redoc-cli ]; then
    yarn add redoc-cli
fi
./node_modules/.bin/redoc-cli bundle swagger.yaml -o apidocs.html