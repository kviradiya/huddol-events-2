if [ -z "$WP_DUMP_DIRECTORY" ]; then
    echo "Need to set WP_DUMP_DIRECTORY"
    exit 1
fi

if [ "$1" ];
then
    INPUT_FILE=$1
else
    INPUT_FILE=latest.sql
fi

if [ ! -f $WP_DUMP_DIRECTORY/$INPUT_FILE ];
then
   echo "File $WP_DUMP_DIRECTORY/$INPUT_FILE does not exist."
   exit;
fi

if [ -z "$WP_USERNAME" ]; then
    echo "Need to set WP_USERNAME"
    exit 1
fi  

if [ -z "$WP_PASSWORD" ]; then
    echo "Need to set WP_PASSWORD"
    exit 1
fi  

if [ -z "$WP_DB_NAME" ]; then
    echo "Need to set WP_DB_NAME"
    exit 1
fi  

if [ -z "$WP_ENV_NAME" ]; then
    echo "Need to set WP_ENV_NAME"
    exit 1
fi

BACKUP_FILE=$(date +"%Y-%m-%d-%T")
mysqldump --opt -u $WP_USERNAME -p$WP_PASSWORD $WP_DB_NAME > $WP_DUMP_DIRECTORY/bk/$BACKUP_FILE.$WP_ENV_NAME.sql.bk
echo "Backup written to $WP_DUMP_DIRECTORY/bk/$BACKUP_FILE.$WP_ENV_NAME.sql.bk"

mysql -u $WP_USERNAME -p$WP_PASSWORD $WP_DB_NAME < $WP_DUMP_DIRECTORY/$INPUT_FILE
echo "DB restored from $WP_DUMP_DIRECTORY/$INPUT_FILE"

mysql -u $WP_USERNAME -p$WP_PASSWORD $WP_DB_NAME < $WP_DUMP_DIRECTORY/post-restore/$WP_ENV_NAME.sql
echo "Ran post-restore script $WP_DUMP_DIRECTORY/post-restore/$WP_ENV_NAME.sql"