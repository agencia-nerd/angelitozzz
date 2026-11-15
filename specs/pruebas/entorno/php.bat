@echo off
rem Lanzador de PHP (PowerShell / cmd): el PHP 8.4 de Local con el ini de línea de comandos.
rem Copiarlo a ~\bin\php.bat y cambiar <USUARIO> y la versión de PHP.
"C:\Users\<USUARIO>\AppData\Roaming\Local\lightning-services\php-8.4.27+0\bin\win64\php.exe" -c "C:\Users\<USUARIO>\Local Sites\angelitozzz\wp-tests\php-cli.ini" %*
