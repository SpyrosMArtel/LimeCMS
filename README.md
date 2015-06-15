# LimeCMS
An elementary (so far) PHP content management system.

LimeCMS was made in an effort to learn the basics of PHP, and advance my HTML(5) and CSS(5) knowledge. It is still an elementary CMS which covers the basic functions, such as add, delete and edit articles and users, and some functionality for categories. Further, it features static templates for the creation of a dynamic website with MySQL backup.

Despite development has halted for the time being, here follows a list of several features that are nice/should have:
- Change to PSR-4 autoloading with composer (to enchance collaboration).
- Complete face off with help from Bootstrap or Pure CSS.
- Add gallery with images and videos.

Installation instructions:
- A working LAMP (Linux, Apache, Mysql, PHP) stack or even XAMPP for Windows systems.
- By default on Linux the web server directory is "/var/www". In any case, under your server's directory, you can create a new directory with a name of your choice and then edit the relevant files to make it active.
- Then, you only need to "git clone" this project in the directory you just created.
- Run the SQL script to create the database and edit the "DB_DSN" parameter in file *config.php* to correspond to your MySQL.
- Finally, you cross your fingers (pun intended) and navigate to your servers url with your browser.

To keep this readme short and avoid confusions, as each enviroment might be set up a bit differently, this guide might be of help (if you are new) https://www.digitalocean.com/community/tutorials/how-to-set-up-apache-virtual-hosts-on-ubuntu-14-04-lts

For XAMPP users a possible guide is https://blog.udemy.com/xampp-tutorial/

Have fun and keep coding!
