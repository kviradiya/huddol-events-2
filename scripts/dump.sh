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

if [ -z "$WP_DUMP_DIRECTORY" ]; then
    echo "Need to set WP_DUMP_DIRECTORY"
    exit 1
fi

if [ -z "$1" ]; then
    OUTPUT_FILE=$(date +"%Y-%m-%d-%T")
else
    OUTPUT_FILE=$1
fi

mysqldump --opt -u $WP_USERNAME -p$WP_PASSWORD $WP_DB_NAME > $WP_DUMP_DIRECTORY/$OUTPUT_FILE.$WP_ENV_NAME.sql
echo "Dumped to $WP_DUMP_DIRECTORY/$OUTPUT_FILE.$WP_ENV_NAME.sql"

mysqldump --opt -u $WP_USERNAME -p$WP_PASSWORD $WP_DB_NAME > $WP_DUMP_DIRECTORY/latest.sql
echo "Dumped to $WP_DUMP_DIRECTORY/latest.sql"