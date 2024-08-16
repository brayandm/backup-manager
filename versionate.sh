#!/bin/bash

version=$(cat VERSION)

sed "s/XVERSION/$version/g" .hooks/TEMPLATE_README.md > README.md

git add README.md

git commit -m "Update README.md"

git tag -a $version -m "Release $version"

git push origin "$version"