# Passencrypt

## Introduction

This is a repository for a demonstration web application to showcase Passencrypt, which is a set of open source encryption and decryption software tools for web applications. Passencrypt is intended to enable browsers and web servers to transmit data privately between each other through HTTP, over insecure network connections.

Passencyrpt does not rely on certificate authorities or a public key infrastructure to provide security. Instead, it enables sensitive data to be encrypted before transmission by the originator (which can be either the client or the server), with one-time symmetric keys that can only be recreated at the destination with knowledge of a password. Passencrypt does not provide a method for securely transmitting the password, so other channels have to be used to establish a common user password for new user account setup.

The purpose of Passencrypt is to provide web applications hosted on shared web hosting services that don't offer HTTPS with 'better than nothing' protection against MITM eavesdropping on data transmission between web servers and authenticated web users. 

## Live version 

A live verrsion of the demo application can be found here:

http://kianoo.com/passencrypt/default.php

The login credentials are:

username: administrator

password: kianootestpass

The application is a credit score estimator and extremely basic CRM. To load a contact, type their userid in the userid field and click outside the field or click on the row of the client table associated with the contact.

Please note, the purpose of the credit score estimator component of this application is to generate dummy data for demonstrating the encryption functionality of the app, and not to generate accurate credit scores, so please don't rely on the credit score outputs.

I've used Google forms for creating new contacts and inputting their data. The form for the demo can be found here:

https://docs.google.com/forms/d/1ImfZgzjiamALIIH1Bx6qiRnEbKrHoNTIVqp0s8lpgUk/viewform

## Features

* 2,000 rounds of PBKDF2 to stretch the user password

* one-time symmetric encryption keys

* Advanced Encryption Standard (AES) used for encryption and decryption

## Installation

Passencrypt is designed for applications that run on LAMP (Linux, Apache, MySQL, PHP) stacks. The demo app likewise requires that you have the LAMP stack installed.

Follow these steps to install the demo app:

1. Clone the repository: `git clone https://github.com/Aminocd/passencrypt.git`

2. Create a MySQL database for your app, and make note of the host name, username, database name and password of the new database.

3. Open 'server.php' with an editor and enter the host name, username, password and database name into the $server, $user, $db, and $pass variables, respectively.

4. 	a) If you have local access or secure (encrypted) remote access to a dedicated machine where your demo app is hosted, and are able to access the web server locally from the machine using a browser, follow these steps to install the demo app:

		i. From the machine where the web server is running, run install.php from a browser. It will prompt you to enter the user's username and password. Once you click 'submit', the script will create the MySQL tables used by the demo app, and will create a new user with the username/password you entered that can access app.

	b) If you do not have local or secure remote access to a browser running on the machine where the web server is running, follow these steps to install the demo app:

		i. Run [your-domain]/install-tables.php from a browser on any machine. This will create the MySQL tables. 

		ii. On your local machine, open with a browser:

		hashpass-util.html

		This is a password stretching utility that uses 2000 rounds of PBKDF2 to generate a password-derived key and outputs it on the webpage. Input the user's username and password in the labeled fields and press submit to generate a stretched password for your new user. 

		iii. Securely connect (e.g. using HTTPS) to your web server's database and execute this SQL statement with the username you inputted above in place of [username] and the password-derived key outputted above in place of [password]:

		`INSERT INTO users (username, password, active) VALUES('[username]', '[password]', 1)`

5. Create a Google Form modeled on the one found here: 

	https://docs.google.com/forms/d/1ImfZgzjiamALIIH1Bx6qiRnEbKrHoNTIVqp0s8lpgUk/viewform

	When finished creating the form, click on the 'View Responses' tab. In the responses spreadsheet that opens, click on row 2, right-click, and click 'Insert 1 above'. In cell A2, type 0. This is the increment field. Next, click Tools->Script editor at the top to open the script generator window. 

6. Create five script files: trunctLog_PE, sendHttpPostAll_PE, truncateTable_PE, sendHttpPost_PE, increment_PE, and in each copy the content found in the script file of the same name found in the Google_Scripts directory in this repository. Change the references to kianoo.com/passencrypt/ in the source script files to your own domain name.

7. From the same script generator window, click Resources->'All your triggers' to open up the trigger setting window. 

Create the following triggers:

Run                   |	Events                |                |              |
----------------------|-----------------------|----------------|--------------| 
trunctLog_PE	      |	Time-driven	      |	Hour timer     | Every 2 hours|
sendHttpPostAll_PE    |	Time-driven	      |	Hour timer     | Every 2 hours|
truncateTable_PE      |	Time-driven	      |	Hour timer     | Every 2 hours|
sendHttpPost_PE	      |	From spreadsheet      |	On form submit |              |
increment_PE	      |	From spreadsheet      |	On form submit |              |	

## Contribute

If you would like to contribute, please email me at aminkbtc [nospam_at] gmail dt com, or simply make a pull request. Work needed to be done:

* **modularize the Passencrypt features of the demo application into a library that can easily be ported into other projects**

* cryptoanalyze Passencrypt to verify whether it enables secure communication as intended

* add log in attempt limiting to prevent brute force attacks

* enable Passencrypt to use multiple one-time token tables for encryption of asynchronous data channels




