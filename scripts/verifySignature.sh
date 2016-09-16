#!/usr/bin/env bash
# Verifies taht the key is signed by
#
set -ex

# ignore pull requests as they are usually not signed
if [[ "${TRAVIS_PULL_REQUEST}" = "true" ]]; then exit 0; fi

CURRDIR=$( dirname "$0" )

# import key
gpg --import "$CURRDIR/rugksigningkey.asc.asc"
# trust key
echo "ABA9B8F6F448B07FD7EA4A1A05D40A636AFAB34D:6:"|gpg --import-ownertrust

# check latest commit
git verfiy-commit HEAD
