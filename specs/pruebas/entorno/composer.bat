@echo off
rem Lanzador de Composer (PowerShell / cmd) con el PHP 8.4 de Local. Necesita ~\bin\composer.phar.
rem Copiarlo a ~\bin\composer.bat y cambiar <USUARIO>.
"C:\Users\<USUARIO>\AppData\Roaming\Local\lightning-services\php-8.4.27+0\bin\win64\php.exe" -c "C:\Users\<USUARIO>\Local Sites\angelitozzz\wp-tests\php-cli.ini" "C:\Users\<USUARIO>\bin\composer.phar" %*
