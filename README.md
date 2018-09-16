## WiFi-Visitors-Statistic-WEB
live web: http://bigbrother.hacklabos.org/

For this project you will also need Graphite and Carbon (https://github.com/graphite-project).

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

## Setup Graphite and Carbon
With Graphite, you don't need to take care of saving data into the database and to take care of data. You just need to send it to Carbon and read it with Graphite.

Install it with:


```
sudo apt-get install graphite-web graphite-carbon

```
Setup will ask you to delete graphite-carbon. You shall select "No". 

```
sudo cp /usr/share/graphite-web/apache2-graphite.conf /etc/apache2/sites-available
```

Inside  `/etc/apache2/sites-available/apache2-graphite.conf` change `<VirtualHost *:80>` to `/<VirtualHost *:81>`.

And add port `Listen 81` to together with `Listen 80` with 

`sudo nano  /etc/apache2/ports.conf `

```
Listen 80
Listen 81

```
Run following commands to install proper lib for apache to run graphite. And then reload all configs.

```
sudo apt-get install libapache2-mod-wsgi
sudo a2ensite apache2-graphite
systemctl reload apache2
```



