@echo off
echo Downloading Local Assets for ArBif System...
echo.

REM Create directories
mkdir public\assets\jquery 2>nul
mkdir public\assets\select2 2>nul
mkdir public\assets\select2\css 2>nul
mkdir public\assets\select2\js 2>nul

REM Download jQuery
echo Downloading jQuery...
curl -L -o public\assets\jquery\jquery.min.js https://code.jquery.com/jquery-3.7.1.min.js

REM Download Select2
echo Downloading Select2...
curl -L -o select2.zip https://github.com/select2/select2/archive/refs/tags/4.1.0-rc.0.zip
tar -xf select2.zip -C public\assets\select2 --strip-components=1
del select2.zip

echo.
echo All assets downloaded successfully!
echo.
pause