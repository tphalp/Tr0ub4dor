**WORKING DRAFT**
****************************************************************
**           w3pw - a web based password console.             **
**                       Version 1.50                         **
****************************************************************

If you are updating from a previous version, please refer to the
"UPGRADE" section of this document!

REQUIREMENTS:
=============
- A Webserver (w3pw was tested with Apache); for secure transfer, SSL
  is strongly recommended.

- PHP (http://php.net) with MySQLi support enabled, and the MCrypt libraries.
  You can find the mcrypt libraries for linux installations here:

    http://mcrypt.sourceforge.net/

  For a Windows installation, the required binaries can be downloaded
  here:

    http://ftp.emini.dk/pub/php/win32/mcrypt/.

- A MySQL (http://myql.com) Database.

INSTALLATION
============
1. DOWNLOAD w3pw:

  Download the latest package from:

    http://sourceforge.com/projects/w3pw/files/

  Once you have downloaded the package, unpack the distribution
  package:

    tar xzf w3pw-x.x.x.tgz

  Change to the directory w3pw-x.x.x

2. CREATE THE DATABASE

  w3pw requires access to a mysql database in order to run
  properly. Create a database called "w3pw" (or any other name
  of your choosing):

    mysql -u <your_mysql_user> -p -e "CREATE DATABASE w3pw"

  Import the database structure:

    mysql -u <your_mysql_user> -p w3pw < ./backend/install.sql.

  If you use another database name, change 'w3pw' to your chosen
  database name. Also change the default password to one that you
  would like to use, or see the step below. Do this prior to the
  import shown above.

  Delete the install.sql file (for security reasons)

  Set your password for accessing w3pw:

    mysql -u <your_mysql_user> -p w3pw -e "UPDATE main set \
      pw=SHA1(\"<yourpassword>\")"

  The standard password that comes with the installation package
	is "secret". Replace "w3pw" with your database name, if different.

3. SETUP THE CONFIG FILE

  w3pw comes with a config.default.php file in the frontend/lib
  directory. You must create a file named config.php. You may do so
  by making a copy of default.settings.php (or create an empty file called
  config.php in the same directory). For example, (from the installation
  directory) make a copy of the default.settings.php file with the command:

    cp frontend/lib/config.default.php frontend/lib/config.php

  Once you have done this, edit your config file in order to change the
  constants, DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT, TMP_PATH, and
  BASE_DOMAIN to match your system values.

  BASE_DOMAIN must be set to the domain where you will be hosting your
  installation of w3pw.

	Windows Users: Don't use a backslash in the TMP_PATH variable.
  Instead use a forward slash "/".

	If you would like to set another timeout value, change the constant
	TIMEOUT also.

  If you would like w3pw to generate a random password if you create
  a new entry, change the constant RANDOM_PW_LENGTH to the desired
  password length. If you wan't to disable this feature, set this
  constant to 0.

4. SETUP WEB FRONTEND

  Copy the contents of the "frontend" directory to your webserver
  htdocs directory. Here is an example command:

    cp -r frontend/* /usr/local/httpd/htdocs

  This will change depending on how you want to setup your w3pw website,
  and which distro you are using, etc.

UPGRADE
=======
IMPORTANT: If you want to upgrade from a version earlier than 1.40,
please upgrade to that version first, then upgrade to 1.5.0.

DOUBLY IMPORTANT: Create a BACKUP of your current w3pw database!

Follow all of the steps described in the section above, but be sure to create
a backup (see warning above) of your existing w3pw database. This will allow
you to recover from a failed upgrade.

Point the database variables in your new config file to the database, as
well as any other customization of the config.php file that you require.

Point your browser to:

  http(s)://<your server>/_upgrade.php

and follow the update instructions. After a succcessful upgrade, delete the
file "_update.php" from your w3pw directory.


USAGE
=====
Open your favorite webbrowser and access this URL:

  https://<your server>/w3pw/index.php

(Alternatively, use "http://..." if you prefer to use an unsecure connection).

The forced logoff feature will not work without javascript enabled.

Project Homepage: http://w3pw.sourceforge.net/
