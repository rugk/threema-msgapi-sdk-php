#!/usr/bin/env bash
set -ex

# ignore script if libsodium should not be installed
if [[ "$LIBSODIUM" = false ]]; then exit 0; fi

if [[ -z "$LIBSODIUM" ]]; then
    echo "No libsodium version given."
    exit 1
fi

# thanks to https://stackoverflow.com/questions/16989598/bash-comparing-version-numbers#answer-24067243
function version_gt() { test "$(echo "$@" | tr " " "\n" | sort -V | head -n 1)" != "$1"; }
function importgpgkey() {
    gpg --import "$CURRDIR/libsodiumkey.asc"
    echo "54A2B8892CC3D6A597B92B6C210627AABA709FE1:6:"|gpg --import-ownertrust
}

firstWebsiteOnlyRelease="1.0.4"

CURRDIR=$( dirname "$0" )

case "$LIBSODIUM" in
    nightly)
        importgpgkey
        git clone -b master "https://github.com/jedisct1/libsodium.git"

        cd libsodium
        # ATTENTION: Currently head commits are not signed
        # tracked in issue https://github.com/jedisct1/libsodium/issues/428
        # Therefore the following command for verification is disabled
        # git verify-commit HEAD
        ./autogen.sh

        echo "Build nightly libsodium version"
        ./configure
        make
        sudo make install
        ;;
    stable)
        # would only work with Ubuntu >= 15.04 without PPA
        sudo add-apt-repository -y ppa:chris-lea/libsodium

        echo "Installing stable libsodium version"
        sudo apt-get update -qq
        sudo apt-get install -qq -V libsodium-dev
        ;;
    # usual version number --> custom build
    [0-9]*\.[0-9]*\.[0-9]*)
        importgpgkey

        if version_gt "$LIBSODIUM" "$firstWebsiteOnlyRelease"; then
            # download & verify files from website

            wget "https://download.libsodium.org/libsodium/releases/libsodium-$LIBSODIUM.tar.gz"
            wget "https://download.libsodium.org/libsodium/releases/libsodium-$LIBSODIUM.tar.gz.sig"

            gpg --verify "libsodium-$LIBSODIUM.tar.gz.sig"

            tar -xzf "libsodium-$LIBSODIUM.tar.gz"
            cd "libsodium-$LIBSODIUM"
        else
            git clone -b "$LIBSODIUM" "https://github.com/jedisct1/libsodium.git"
            cd libsodium
            git verify-tag "$LIBSODIUM"

            ./autogen.sh
        fi

        echo "Build libsodium version $LIBSODIUM"
        ./configure
        make
        sudo make install
        ;;
    *)
        echo "Invalid value for libsodium version: $LIBSODIUM"
        exit 1
        ;;
esac
