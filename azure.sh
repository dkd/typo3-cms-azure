#!/bin/bash
cd typo3/
git reset --hard HEAD
git clean -x -f -d
git apply -v ../patches/*
cd ..
zip -r --filesync package.zip typo3 install.sql manifest.xml parameters.xml web.config
SHA1=$(openssl sha1 package.zip | cut -d " " -f 2)
sed -i '' "s/<sha1>.*<\/sha1>/<sha1>$SHA1<\/sha1>/g" feed.xml
echo $SHA1
