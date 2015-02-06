# JIRA
Welcome to the JIRA wiki!! This is php support for JIRA project of atlassian.com, which is a project management tool. Dependences for this project are: 

1)apache2 server.

2)php5 + imap enabled.

3)mysql 

4)curl

5)Lynx

This application interacts with the cloud instance hosted by atlassian.com.The application interacts with atlassian.net using CURL. The get and post request are made using the api provided by atlassian.net.

This project extracts issues, bugs and other info from the email account given in const.php. The email parsing is done by using the imap setting of the mail server.These issues,bugs etc are stored in local DB(mysql) then by running cron jobs they are posted to the account hosted on atlassian.net.

Installing dependences: https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-14-04

Enable imap by running following commands: 

sudo apt-get install php5-imap

sudo php5enmod imap

sudo service apache2 restart

Installing Lynx:

sudo apt-install lynx-cur

To install the DB setting in your server:

lynx {server-address}/databaseInit.php -dump >>log.txt

To configure the cron jobs for every 10 mins:

sudo crontab -e

Add the following statments at the end of the file:

*/10 * * * * lynx {server-address}/mailExtractor.php -dump >> log.txt

*/10 * * * * lynx {server-address}/uploadIssues.php -dump >> log.txt

*/10 * * * * lynx {server-address}/fetchIssues.php -dump >> log.txt




