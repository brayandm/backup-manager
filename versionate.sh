#!/bin/bash

bash genreadme.sh

version=$(cat VERSION)

git add .

git commit -m "Create release $version" || true

git push origin main

git tag -d $version || true

git push origin --delete $version || true

git tag -a $version -m "Release $version"

git push origin "$version"