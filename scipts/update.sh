#!/bin/sh

#CONFIGURE VARIABLES
FILE_PATH=`readlink -e $0`
FILE_FOLDER=`dirname $FILE_PATH`
if [ -f $FILE_FOLDER/config.sh ]; then
	source "$FILE_FOLDER/config.sh"
else
	echo "Can't find config file: config.sh"
	exit 1
fi

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

if [ -f CITY_FILE ]; then
	$PHP_PATH $FILE_FOLDER/city.php < CITY_FILE |$MYSQL_PATH --host=$MYSQL_PATH --user=$MYSQL_USER --password=$MYSQL_PASSWORD $MYSQL_DATABASE
else
	echo "Not found file for cities list"
fi

