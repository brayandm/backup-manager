#!/bin/bash

version=$(cat VERSION)

sed "s/XVERSION/$version/g" templates/TEMPLATE_README.md > README.md

git add .

git commit -m "Create release $version"

git tag -d $version

git push origin --delete $version

git tag -a $version -m "Release $version"

git push origin main

git push origin "$version"