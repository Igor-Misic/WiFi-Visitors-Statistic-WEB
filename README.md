## WiFi-Visitors-Statistic-WEB
live web: http://bigbrother.hacklabos.org/

For this project you will also need Graphite and Carbon (https://github.com/graphite-project).

### Architecture
![](https://lh3.googleusercontent.com/s0ulMG73Ch20P3DT7rSqzv7T0wEPM8GPvyGnSG1pb4aFblsfW_OAFyhGUcFMNZt-KeeAsbQWqiS5tNRcE-un80APgCO_4keVbyUDm9Al4vU41nEBMtRqD7Xpy34yOOZCFlXAXhN1Kmao1MZB6PIkCZ2sCAJc8bAoptaccDtfcqpFN6Fp1MyDmfnI1nO8ApuHkPOmvVX7cctR0IYEOj7zpWdu4hYJWp9fiZFW0nGL4MvNwm4Rjn1lpYaSJFs22zs0Hen3mAYQ4Z52RwNfS-mTEoVAuRgyPXj8x-W_x5Cr0lI4FGJDWAixqSAlS4FDmpnhq2uU_3Lnp67t9myuZeqmzvvIl4uqYfMy4zxw_bBVmjizc8J_cSOAYn-5mskt-JSLTLVMjJjTd0qVq9eVlcAN-X_0wySs6SgntgBxEFoakCIIOu4ntIWR_Mjrc5XuBXjn82DxEtQF9T8iK4g8fgryruc6IdIQUiqz6YHdG-Q2kPk_D8Rj2V0N5lFMLDWd40PE3YErhIXlVvnqaeuogEvwZc0_KYjHrWNY-e7jGpP2Mu_RBJXAQfTuCzb_bN0As9ubqyApM06D1VYpE8GMEGLYtEHpiB7zF9n-mQNwbT77-wbOpbV4JrMbkNYiM-Esb-wnjcawpK-YVT1y8s-EW9L8XFbwMkaojvt8zMl5Dfc0gt8sA-U8IIvIwp0=w700-h501-no)

### Setup WEB server on ubutnu:

```
sudo apt-get install apache2
sudo apt-get install php
sudo apt-get install mysql-server
sudo apt-get install php-mysqlnd php-curl
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

## Setup Graphite and Carbon TODO: This need to fix, it is broken.
I can't install graphite to Ubuntu 18.04 I have some problems. So this need to be done.



With Graphite, you don't need to take care of saving data into the database and to take care of data. You just need to send it to Carbon and read it with Graphite.

It should go something like this:

Do instructions:


```
cd /opt/
sudo mkdir tmp
cd tmp
sudo git clone https://github.com/graphite-project/graphite-web.git
sudo git clone https://github.com/graphite-project/carbon.git
sudo git clone https://github.com/graphite-project/whisper.git
sudo git clone https://github.com/graphite-project/ceres.git

cd graphite-web/
sudo python setup.py install
cd ..
cd carbon/
sudo python setup.py install
cd ..
cd ceres/
sudo python setup.py install
cd whisper/
cd ../..
sudo rm -rf tmp
```


```
sudo cp /opt/graphite/examples/init.d/* /etc/init.d/
sudo chmod 775 /etc/init.d/carbon-*
sudo cp /opt/graphite/examples/example-graphite-vhost.conf /etc/apache2/sites-available/apache2-graphite.conf
```

Inside  `/etc/apache2/sites-available/apache2-graphite.conf` change `<VirtualHost *:80>` to `/<VirtualHost *:81>` and also `WSGISocketPrefix run/wsgi` to `WSGISocketPrefix /var/run/apache2/wsgi`.

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

Set carbon to run after system reboot. Replace ` CARBON_CACHE_ENABLED=false` with ` CARBON_CACHE_ENABLED=true`

#### Setup config files

```
sudo cp /opt/graphite/conf/graphite.wsgi.example /opt/graphite/conf/graphite.wsgi
sudo cp /opt/graphite/conf/relay-rules.conf.example /opt/graphite/conf/relay-rules.conf
sudo cp /opt/graphite/conf/storage-aggregation.conf.example /opt/graphite/conf/storage-aggregation.conf
sudo cp /opt/graphite/conf/storage-schemas.conf.example /opt/graphite/conf/storage-schemas.conf
sudo cp /opt/graphite/conf/carbon.conf.example /opt/graphite/conf/carbon.conf
```

```
sudo nano /etc/default/graphite-carbon
```




