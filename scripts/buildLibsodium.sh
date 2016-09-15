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
        # would only work with Ubuntu >= 15.04 without PPA
        # so add PPA:
        sudo add-apt-repository ppa:chris-lea/libsodium
        sudo apt-get install -V libsodium-dev
        ;;
    *)
        echo "Invalid value for libsodium version: $LIBSODIUM"
        exit 1
        ;;
esac
