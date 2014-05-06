#!/bin/bash
cd ../../../../
zip -r --filesync typo3/typo3/contrib/azure/package.zip typo3 -x "typo3/.git*" "typo3/typo3/contrib/azure/*"
cd typo3/typo3/contrib/azure/overlay/
zip -r --grow ../package.zip *
