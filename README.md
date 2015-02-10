Passencrypt is encryption and decryption software for web applications. It is intended to allow browsers and web servers to transmit data privately between each other through HTTP, over insecure internet connections.

It allows sensitive data to be encrypted before transmission by the originator (which can be either the client or the server), with one-time symmetric keys that can only be recreated at the destination with knowledge of a password. Passencrypt does not provide a method for securely transmitting the password, so other channels have to be used to establish a common user password for new user account setup.

The purpose of passencrypt is to provide web applications hosted on shared web hosting services that don't offer HTTPS with 'better than nothing' protection against MITM eavesdropping on data transmission between web servers and their web users.

Demo web application

I've created a demonstration web application that uses passencrypt here:

http://kianoo.com/passencrypt/default.php

The login credentials are:

username: Administrator

password: kianootestpass

The application is a credit score estimator and extremely basic CRM. Please note, the purpose of the credit score estimator component of this application is to generate dummy data for demonstrating the encryption functionality of the app, and not to generate accurate credit scores, so please don't rely on the credit score outputs.

I've used Google forms for creating new contacts and inputting their data. The form for the demo can be found here:

https://docs.google.com/forms/d/1ImfZgzjiamALIIH1Bx6qiRnEbKrHoNTIVqp0s8lpgUk/viewform

Installation

To recreate this web demo on your own server, follow these steps:







