#!/bin/sh

#CONFIGURE VARIABLES
WORK_FOLDER=/tmp

DOWNLOAD_LINK="http://ipgeobase.ru/files/db/Main/geo_files.tar.gz"

#FILES VARIABLES
ARCHIVE_FILE=$WORK_FOLDER/geo.tar.gz
CITY_FILE=$WORK_FOLDER/cities.txt
GEO_FILE=$WORK_FOLDER/cidr_optim.txt

#DELETE OLD FILES
if [ -f $ARCHIVE_FILE ]; then
	rm -f $ARCHIVE_FILE
fi
if [ -f CITY_FILE ]; then
	rm -f $CITY_FILE
fi
if [ -f GEO_FILE ]; then
	rm -f $GEO_FILE
fi
#DOWNLOAD NEW ARCHIVE

WGET_PARAMS="--output-document=$ARCHIVE_FILE --tries=3 --continue --wait=10"

wget $WGET_PARAMS  $DOWNLOAD_LINK

if [ -f $ARCHIVE_FILE ]; then
	tar --overwrite --directory=$WORK_FOLDER -xzvf $ARCHIVE_FILE
	rm -f $ARCHIVE_FILE
fi


