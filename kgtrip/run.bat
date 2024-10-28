@echo off
set folderpath=%~dp0
for %%f in ("%folderpath%.") do set foldername=%%~nxf
start http://localhost/%foldername%

