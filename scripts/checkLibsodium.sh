#!/usr/bin/env bash
set -ex

# ignore script if libsodium is not installed
if [[ "$LIBSODIUM" = false ]]; then exit 0; fi

# thanks to https://stackoverflow.com/questions/16989598/bash-comparing-version-numbers#answer-24067243
function version_gt() { test "$(echo "$@" | tr " " "\n" | sort -V | head -n 1)" != "$1"; }

firstNamespaceVersion="0.2.0"

# verify PHP libsodium binding (pecl extension)
LIBSODIUMPHPVER=$( php -r 'echo phpversion(''libsodium'');' )
echo "Installed libsodium-php version: $LIBSODIUMPHPVER"

# verify libsodium version
if version_gt "$LIBSODIUMPHPVER" "$firstNamespaceVersion"; then
    # a fairly recent version working with namespaces
    LIBSODIUMVER=$( php -r 'echo \Sodium\version_string();' )
else
    # an old, depreciated version
    LIBSODIUMVER=$( php -r 'echo Sodium::sodium_version_string();' )
    echo "Warning: This version is depreciated!"
fi
echo "Installed libsodium version: $LIBSODIUMVER"
