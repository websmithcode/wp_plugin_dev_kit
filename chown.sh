dc exec -t wp bash -c "
chown www-data:www-data  -R * # Let Apache be owner
echo Chown is Done!
find . -type d -exec chmod 755 {} \;  # Change directory permissions rwxr-xr-x
echo Chmod directories is Done!
find . -type f -exec chmod 644 {} \;  # Change file permissions rw-r--r--
echo Chmod files is Done!
echo ALL DONE!
"
