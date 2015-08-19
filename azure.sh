#!/bin/bash
if [ $OSTYPE == "msys" ]
then
echo "On Windows"
export PATH="/c/Program Files (x86)/IIS Express/PHP/v5.5/":$PATH
export PATH="/c/Program Files (x86)/GnuWin32/bin":$PATH
else
echo "No Windows"
fi
git submodule update
cd typo3/
git reset --hard HEAD
git am --abort
git clean -x -f -d --exclude="vendor"
composer.phar install
git am ../patches/*
cd ..
zip -r --filesync package.zip typo3 install.sql manifest.xml parameters.xml web.config
SHA1=$(openssl sha1 package.zip | cut -d " " -f 2)
sed -i'' "s|<sha1>.*</sha1>|<sha1>$SHA1</sha1>|g" feed.xml
echo $SHA1
if [ $OSTYPE == "msys" ]
then
sed -i'' "s|<installerURL>.*</installerURL>|<installerURL>C:\\\\Users\\\\dkd-azure\\\\typo3\\\\package.zip</installerURL>|g" feed.xml
else
sed -i'' "s|<installerURL>.*</installerURL>|<installerURL>file://\\\\\\\\VBOXSVR\\\\azure\\\\package.zip</installerURL>|g" feed.xml
fi
