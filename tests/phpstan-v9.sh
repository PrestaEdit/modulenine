#!/bin/bash
PS_VERSION=$1

set -e

# Docker images prestashop/prestashop may be used, even if the shop remains uninstalled
echo "Pull PrestaShop files (Tag ${PS_VERSION})"

docker rm -f ps9-php8 || true
docker volume rm -f ps9-php8 || true

docker run -tid --rm -v ps9-php8:/var/www/html --name ps9-php8 prestaedit/prestashop:$PS_VERSION

docker exec -i ps9-php8 php -v

# Clear previous instance of the module in the PrestaShop volume
echo "Clear previous module"

docker exec -tid ps9-php8 rm -rf /var/www/html/modules/modulenine

# Run a container for PHPStan, having access to the module content and PrestaShop sources.
# This tool is outside the composer.json because of the compatibility with PHP 5.6
echo "Run PHPStan using phpstan-${PS_VERSION}.neon file"

docker run --rm --volumes-from ps9-php8 \
       -v $PWD:/var/www/html/modules/modulenine \
       -e _PS_ROOT_DIR_=/var/www/html \
       --workdir=/var/www/html/modules/modulenine ghcr.io/phpstan/phpstan:nightly-php8.1 \
       analyse \
       --configuration=/var/www/html/modules/modulenine/tests/phpstan/phpstan-$PS_VERSION.neon
