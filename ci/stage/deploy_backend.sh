#!/bin/bash

cd ../../
mkdir backend
TARGETDIR=backend;for file in *;do test "$file" != "$TARGETDIR" && cp -r "$file" "$TARGETDIR/";done

# Remove files that we don't want to bundle
cd backend
rm -rf ci
rm -rf .git
rm -rf api/maps/

# Put the current commit hash into cachehash.txt
rm api/Config/cachehash.txt
echo $CI_COMMIT_SHORT_SHA > api/Config/cachehash.txt

zip -r ../backend.zip . > ../zip_output.txt
cd ../
rm -rf backend
mkdir backend
mv backend.zip backend
mv zip_output.txt backend

lftp -d -e "set ssl:verify-certificate no; mirror -P $NUMBER_OF_CONCURRENT_UPLOADS -Rv backend/ $REMOTE_DIR; quit;" ftp://$STAGE_USERNAME:$PASSWORD@$HOST

curl https://test.hitmaps.com/unzip-build.php?zip=backend\&access-key=$ACCESS_KEY
curl https://test.hitmaps.com/api/admin/migrate?access-key=$ACCESS_KEY
