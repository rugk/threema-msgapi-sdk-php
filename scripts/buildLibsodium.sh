#!/usr/bin/env sh
set -ex

# ignore script if libsodium should not be installed
if [[ "$LIBSODIUM" == false ]]; then exit 0; fi

if [[ -z "$LIBSODIUM" ]]; then
    echo "No libsodium version given."
    exit 1
fi

CURRDIR=$( dirname "$0" )

case "$LIBSODIUM" in
    stable)
        # would only work with Ubuntu >= 15.04 without PPA
        sudo add-apt-repository -y ppa:chris-lea/libsodium

        echo "Installing stable libsodium version"
        sudo apt-get update -qq
        sudo apt-get install -qq -V libsodium-dev
        ;;
    # usual version number --> custom build
    [0-9]*\.[0-9]*\.[0-9]*)
        # download & verify files
        gpg --import "$CURRDIR/libsodiumkey.asc"
        echo "54A2B8892CC3D6A597B92B6C210627AABA709FE1:5:"|gpg --import-ownertrust

        wget "https://download.libsodium.org/libsodium/releases/libsodium-$LIBSODIUM.tar.gz"
        wget "https://download.libsodium.org/libsodium/releases/libsodium-$LIBSODIUM.tar.gz.sig"

        gpg --verify "libsodium-$LIBSODIUM.tar.gz.sig"

        tar -xzvf "libsodium-$LIBSODIUM.tar.gz"
        cd "libsodium-$LIBSODIUM"

        # build libsodium
        ./configure
        make
        sudo make install

        # enable extension
        echo "extension=libsodium.so" > libsodium.ini
        phpenv config-add libsodium.ini
        # or php5enmod libsodium (not tested)
        ;;
    *)
        echo "Invalid value for libsodium version: $LIBSODIUM"
        exit 1
        ;;
esac
