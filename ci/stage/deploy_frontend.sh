#!/bin/bash

cd ../../dist

mkdir frontend
TARGETDIR=frontend;for file in *;do test "$file" != "$TARGETDIR" && cp -r "$file" "$TARGETDIR/";done

zip -r ../frontend.zip . > ../zip_output.txt
cd ../
rm -rf frontend
mkdir frontend
mv frontend.zip frontend
mv zip_output.txt frontend

lftp -d -e "set ssl:verify-certificate no; mirror -P $NUMBER_OF_CONCURRENT_UPLOADS -Rv frontend/ $REMOTE_DIR; quit;" ftp://$STAGE_USERNAME:$PASSWORD@$HOST

curl https://test.hitmaps.com/unzip-build.php?zip=frontend\&access-key=$ACCESS_KEY
