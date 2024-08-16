#!/bin/bash

set -e

version=$(cat VERSION)

sed "s/XVERSION/$version/g" templates/README.md > README.md