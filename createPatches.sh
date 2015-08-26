#!/bin/bash
rm ./patches/*
cd typo3
git format-patch -o ../patches/ origin/master

