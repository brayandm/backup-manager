#!/bin/bash

bash genreadme.sh

version=$(cat VERSION)

if ! grep -Fxq "$version" versions.txt; then
    echo "$version" >> versions.txt
    echo "Version $version added to versions.txt"
else
    echo "Version $version already exists in versions.txt"
fi

git add .

git commit -m "Create release $version" || true

git push origin main

git tag -d "$version" || true
git push origin --delete "$version" || true

git tag -a "$version" -m "Release $version"

git push origin "$version"