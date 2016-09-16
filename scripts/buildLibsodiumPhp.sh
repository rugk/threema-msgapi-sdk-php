#!/usr/bin/env sh
set -ex

# ignore script if libsodium is not be installed
if [ "$LIBSODIUM" == false ]; then exit 0; fi

# install PHP extension
pecl install libsodium

# verify libsodium version
LIBSODIUMVER=$( php -r 'echo \Sodium\version_string();' )
echo "Installed libsodium version: $LIBSODIUMVER"
