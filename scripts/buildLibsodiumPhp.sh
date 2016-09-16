#!/usr/bin/env sh
set -ex

# ignore script if libsodium is not be installed
if [ "$LIBSODIUM" = false ]; then exit 0; fi

# install PHP extension
pecl install libsodium

# install in missing PHP version envoriments

# enable extension
# echo "extension=libsodium.so" > libsodium.ini
# phpenv config-add libsodium.ini
# or php5enmod libsodium (not tested)
# or echo "extension=<extension>.so" >> ~/.phpenv/versions/$(phpenv version-name)/etc/php.ini

# verify libsodium version
LIBSODIUMVER=$( php -r 'echo \Sodium\version_string();' )
echo "Installed libsodium version: $LIBSODIUMVER"
