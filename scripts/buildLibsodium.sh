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
        sudo apt-get install -qq libsodium-dev
        ;;
    # usual version number --> custom build
    [0-9]*\.[0-9]*\.[0-9]*)
        # download & verify files
        gpg --import < "$CURRDIR/libsodiumkey.asc"

        wget "https://download.libsodium.org/libsodium/releases/libsodium-$LIBSODIUM.tar.gz"
        wget "https://download.libsodium.org/libsodium/releases/libsodium-$LIBSODIUM.tar.gz.sig"

        gpg --verify "libsodium-$LIBSODIUM.tar.gz.sig" --trust-key "54A2 B889 2CC3 D6A5 97B9 2B6C 2106 27AA BA70 9FE1"

        tar -xzvf "libsodium-$LIBSODIUM.tar.gz"
        cd "libsodium-$LIBSODIUM"

        # build libsodium
        ./configure
        make
        sudo make install
        ;;
    *)
        echo "Invalid value for libsodium version: $LIBSODIUM"
        exit 1
        ;;
esac

# verify libsodium version
echo "Installed libsodium version: $( php -r 'echo \Sodium\version_string();' )"
