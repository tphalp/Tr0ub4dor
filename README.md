Tr0ub4dor
=========


What is Tr0ub4dor?
------------------

Tr0ub4dor is a web-based password manager written in PHP, thatutilizes MySQL, and MCrypt (for encryption).

If you are updating from a previous version, please refer to the __Upgrade__ section of this document!


Requirements
============

-   PHP (>= 4.3) with MySQLi support enabled

    >http://php.net/

-   MCrypt libraries

    >You can find the mcrypt libraries for linux installations here:

    >http://mcrypt.sourceforge.net/

    >For a Windows installation, the required binaries can be downloaded here:

    >http://ftp.emini.dk/pub/php/win32/mcrypt/

-   A MySQL Database

    >http://mysql.com/

-   A Webserver (Tr0ub4dor has been tested with Apache); for secure transfer, SSL is strongly recommended.



Installation
============


Acquire Tr0ub4dor
------------------

  Clone the repo:

      git clone git@github.com:tphalp/Tr0ub4dor.git

### __OR__

  Download the latest package from:

      https://github.com/tphalp/Tr0ub4dor/downloads

  Once you have downloaded the package, unpack the distribution package:

      tar xzf tr0ub4dor-x.x.x.tgz

  Change to the directory tr0ub4dor-x.x.x

Setup the database
------------------

  Tr0ub4dor requires access to a mysql database in order to run properly. Create a database called "tr0ub4dor" (or any other name of your choosing):

      mysql -u <your_mysql_user> -p -e 'CREATE DATABASE tr0ub4dor'

  Import the database structure:

      mysql -u <your_mysql_user> -p Tr0ub4dor < ./backend/install.sql

  If you use another database name, change 'Tr0ub4dor' to your chosen database name. Also change the default password to one that you would like to use, or see the step below. Do this prior to the import shown above.

  Delete the install.sql file (for security reasons)

  Set your password for accessing Tr0ub4dor:

      mysql -u <your_mysql_user> -p tr0ub4dor -e 'UPDATE main set \
        pw=SHA1(\'secret\')'

  The standard password that comes with the installation package is "secret". Replace "tr0ub4dor" with your database name, if different.


Setup the config file
---------------------

  Tr0ub4dor comes with a config.default.php file in the frontend/lib directory. You must create a file named config.php. You may do so by making a copy of default.settings.php (or create an empty file called config.php in the same directory). For example, (from the installation directory) make a copy of the default.settings.php file with the command:

      cp frontend/lib/config.default.php frontend/lib/config.php

  Once you have done this, edit your config file in order to change the constants, DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT, TMP_PATH, and BASE_DOMAIN to match your system values.

  BASE_DOMAIN must be set to the domain where you will be hosting your installation of Tr0ub4dor.

  Windows Users: Don't use a backslash in the TMP_PATH variable. Instead use a forward slash "/".

  If you would like to set another timeout value, change the constant TIMEOUT also.

  If you would like Tr0ub4dor to generate a random password if you create a new entry, change the constant RANDOM_PW_LENGTH to the desired password length. If you wan't to disable this feature, set this constant to 0.

Setup the web frontend
----------------------

  Copy the contents of the "frontend" directory to your webserver htdocs directory. Here is an example command:

      cp -r frontend/* /usr/local/httpd/htdocs

  This will change depending on how you want to setup your Tr0ub4dor website, and which distro you are using, etc.



Upgrade
=======

__IMPORTANT__: If you want to upgrade from a version earlier than 1.40, please upgrade to that version first, then upgrade to 1.5.0.

__DOUBLY IMPORTANT__: Create a BACKUP of your current Tr0ub4dor database!

Follow all of the steps described in the section above, but be sure to create a backup of your existing Tr0ub4dor database. This will allow you to recover from a failed upgrade.

Point the database variables in your new config file to the database, as well as any other customization of the config.php file that you require.



Usage
=====

Open your favorite web browser and access this URL:

>https://example.com/tr0ub4dor/index.php

(Alternatively, use http://... if you prefer to use an unsecure connection).

The forced logoff feature will not work without javascript enabled.


Project Homepage
================

http://github.com/tphalp/Tr0ub4dor


Contributing
============

1. [Fork it][1].
2. Create a branch (`git checkout -b my_contrib`)
3. Commit your changes (`git commit -am "Meaningful Comment"`)
4. Push to the branch (`git push origin my_contrib`)
5. Create an [Issue][2] with a link to your branch
6. __Or__: Create a [Pull Request][3]

[1]: http://help.github.com/fork-a-repo/
[2]: http://github.com/tphalp/Tr0ub4dor/issues
[3]: http://help.github.com/send-pull-requests/