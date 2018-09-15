## WiFi-Visitors-Statistic-WEB
live web: http://bigbrother.hacklabos.org/

For this project you will also need graphite and carbone (https://github.com/graphite-project).

### Setup WEB server on ubutnu:

```
sudo apt-get install apache2
sudo apt-get install php
sudo apt-get install mysql-server
apt-get install php-mysqlnd
```
With `sudo nano /etc/apache2/sites-available/000-default.conf ` replace `/var/www/html` with `/var/www`

Now download this code to your www folder.

```
cd /var/www
sudo git clone https://github.com/Igor-Misic/WiFi-Visitors-Statistic-WEB.git
```

Now you need to prepare sql dabase and table.

```
sudo mysql -u root
```

```
mysql> CREATE DATABASE hacklabos;
mysql> GRANT ALL PRIVILEGES ON hacklabos.* TO 'hacklabos'@'localhost' IDENTIFIED BY 'rU8toorqFmjeVwLIgnW7';
mysql> USE hacklabos;
mysql> source /var/www/WiFi-Visitors-Statistic-WEB/hacklabos.sql;
```

Now you shall be able to see the page but without graphs.

http://127.0.0.1/WiFi-Visitors-Statistic-WEB/web/index.php

## Setup Graphite and Carbone
