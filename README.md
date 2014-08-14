php-ddns53
==========

A simple PHP script to update Amazon's Route 53 with a new IP

This is a simple hack to use Route 53 as a dynamic DNS host. I use the script in a crontab during boot and on a regular basis afterwards on my RaspberryPi to check for a new IP address.

Usage
=====

It requires Dan Myers's [Amazon Route 53 PHP Class](http://sourceforge.net/projects/php-r53/) to work.

IncR53.php takes your AWS ID and secret key.

The main file ddns53.php needs the Hosted Zone ID and FQDN that you find in your AWS Management Console.

Just put all three files into the same directory and run "php ddns53.php"
