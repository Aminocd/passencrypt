'passencrypt' is a set of web-based encryption and decryption tools intended to allow a client and server to communicate with each other over insecure channels through HTTP.

It allows sensitive data to be encrypted before transmission by the originator (which can be the client or the server), with one-time keys that can only be recreated at the destination with a shared password that is known only to the server and client.

The purpose of this set of tools is to provide those using shared hosting services that don't offer HTTPS with 'better than nothing' protection against MITM eavesdropping.

I've created a sample webpage to demonstrate how passencrypt works client side, at:

http://kianoo.com/passencrypt/default.php

The login credentials are:

username: Administrator

password: kianootestpass


