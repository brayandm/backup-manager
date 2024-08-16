#!/bin/bash

set -e

version=$(cat VERSION)

sed "s/XVERSION/$version/g" templates/README.md > README.md

git add .

git commit -m "Create release $version" || true

git tag -d $version || true

git push origin --delete $version  || true

git tag -a $version -m "Release $version"

git push origin main

git push origin "$version"