#!/bin/bash

cd /var/www/html/include

rm tgdb.php

wget http://fm-poland.pl:888/files/tgdb.txt

cp /var/www/html/include/tgdb.txt /var/www/html/include/tgdb.php

rm tgdb.txt
