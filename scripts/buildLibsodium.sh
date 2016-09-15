 #!/usr/bin/env sh
set -ex

# ignore script if libsodium should not be installed
if [[ $LIBSODIUM == false ]]; then exit 0; fi

if [[ -z $LIBSODIUM ]]; then
    echo "No libsodium version given."
    exit 1
fi

case "$LIBSODIUM" in
    stable)
        sudo apt-get update -qq
        sudo apt-get install -V libsodium
        ;;
    *)
        echo "Invalid value for libsodium version: $LIBSODIUM"
        exit 1
        ;;
esac
