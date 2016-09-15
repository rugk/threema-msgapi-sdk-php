#!/usr/bin/env sh
set -ex

# ignore script if libsodium is not installed
if [[ "$LIBSODIUM" == false ]]; then exit 0; fi

# install PHP extension
pecl install libsodium
