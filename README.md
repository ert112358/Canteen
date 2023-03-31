# Canteen

Canteen is a software written in PHP, JS and HTML which I made for my school, that allows clients to order items from the schools' canteen.

## Installation

 1. First of all, you must install Apache2 and PHP. In order to do that, run the following commands:
 
		sudo su
		apt-get update
		apt-get install apache2
		apt-get install libapache2-mod-php

 2. Next, we remove the default **html** folder created by **Apache2** and put the new files in:

		rm -r /var/www/html/
		git clone https://github.com/ert112358/Canteen
		cd Canteen
		mv html /var/www/
		mv keys /var/www/

 3. (Optional) In order for the site to work correctly, you must give some files R/W permissions:

		chmod 666 /var/www/html/activity.log
		chmod 666 /var/www/html/orders/orders.txt
		chmod 666 /var/www/keys/keys.csv

## User management

The default credentials are:

 - **Username**: ExampleUser
 - **Password**: weakpassword101
 
 or
 
- **Username**: ElevatedUser
 - **Password**: weakpassword101

ElevatedUser is an **elevated** user, while ExampleUser is not. The difference between elevated users and regular users is that elevated users can order up to 200 items whose price doesn't exceed 80€, while regular users can order up to 20 items whose price doesn't exceed 30€.

In order to add new users, you must edit the `keys/keys.csv` file. A user consists of 3 fields:

	user,password,elevation
	
The `password` field is encrypted, and `elevation` (`true` or `false`) represents the elevation status of the user.

The password is encrypted with a key stored in the `$key` variable inside `html/main.php`. The function used to encrypt the password is:

	openssl_encrypt($pass,"aes-256-ecb",$key);
		
You can encrypt a password by visiting `http://localhost/encrypt.php`
You can also decrypt a password by visiting `http://localhost/decrypt.php`.

For example, to add an elevated user named `Peter` with password `Griffin`, you must write the following:

	Peter,4MLnFpPoUEbOCyDp/YQhSw==,true

## Menu management

The menu is contained in the file `html/orders/menu.csv`.
Each item consists of 3 fields:

	id,name,price,type

For example, in order to add a "Small Pizza" item with the price of 1.50€, you must write:

	pizza,Small Pizza,1.50,food

Remember to put drink items before food ones, like this:

	a,AAA,1.00,drink
	b,BBB,1.00,drink
	...
	y,YYY,2.00,food
	z,ZZZ,2.00,food

## Miscellaneous

The file `activity.log` contains information of basically everything that clients do in the server. The platform logs:

 - Logins
 - Password changes
 - Orders
 - Hacking attempts

Each entry contains the IP address and date/time when the event took place.

The file `html/orders/orders.txt` contains all the orders that clients placed. There's not much to say here, but it's recommended to create a `cron` schedule that clears this file whenever it needs to be cleaned. I've made this for my school.

