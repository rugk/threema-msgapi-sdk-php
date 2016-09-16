#!/usr/bin/env bash
set -e

# ignore script if libsodium is not installed
if [[ "$LIBSODIUM" = false ]]; then exit 0; fi

# verify libsodium version
LIBSODIUMVER=$( php -r 'echo \Sodium\version_string();' )
echo "Installed libsodium version: $LIBSODIUMVER"
