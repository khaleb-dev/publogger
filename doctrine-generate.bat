@ECHO OFF

 REM Khaleb Great [code_witch]
 REM ebukauche52@gmail.com
 REM @CT Workspace
 REM Usage: Laminas Projects
 REM 12:18am Thur, June 11, 2020

REM This script generate entities from database, generate annotations for the entities and proxies for the entities

REM call .\vendor\bin\doctrine-module orm:convert-mapping --force --from-database --namespace Application\Entity\ annotation ./module/Application/src/

REM call .\vendor\bin\doctrine-module orm:generate-entities ./module/Application/src/ --generate-annotations=true

REM This will generate repos
REM call .\vendor\bin\doctrine-module orm:generate-repositories ./module/Application/src/

REM This will generate proxies
 call .\vendor\bin\doctrine-module orm:generate-proxies

pause