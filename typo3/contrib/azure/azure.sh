#!/bin/bash
cd ../../../../
zip -r --filesync typo3/typo3/contrib/azure/package.zip typo3 -x "typo3/.git*" "typo3/typo3/contrib/azure/*"
cd typo3/typo3/contrib/azure/overlay/
zip -r --grow ../package.zip *
cd ..
SHA1=$(openssl sha1 package.zip | cut -d " " -f 2)
sed -i '' "s/<sha1>.*<\/sha1>/<sha1>$SHA1<\/sha1>/g" feed.xml
echo $SHA1