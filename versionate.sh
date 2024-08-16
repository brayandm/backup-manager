#!/bin/bash

version=$(cat VERSION)

sed "s/XVERSION/$version/g" .hooks/TEMPLATE_README.md > README.md