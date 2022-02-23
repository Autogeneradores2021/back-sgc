<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>
<p align="center"><a href="https://www.oracle.com/database/" target="_blank"><img src="https://logodix.com/logo/88244.png" width="200"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# BACK-SGC 🚀

## Comenzando

_Esta aplicacion fue diseñada para recolectar informacion para el Sistema de Gestion de Calidad de Electrohuila SA ESP_


### Pre-requisitos 📋

_Para que este proyecto funcione correctamente necesitas los siguientes paquetes_

* php (8.0.13)
* laravel (8.x)
* Docker (20.10.10, build b485636)
* Docker Compose (1.26.0, build d4451659)

### Instalación 🔧

_Sigue los pasos para que se ejecute correctamenete_


_Parado en la caperta principal del proyecto ejecuta los siguientes comandos en el CMD o POWERSHELL de windows_

### Construimos la aplicacion
#### Asi:
```
docker-compose build
```
#### Levantamos los servicios
```
docker-compose up -d
```
### Configuramos la base de datos
```
docker exec -it db bash

sqlplus

SQL*Plus: Release 11.2.0.2.0 Production on Tue Nov 16 20:56:01 2021

Copyright (c) 1982, 2011, Oracle.  All rights reserved.

Enter user-name: system
Enter password: oracle

Connected to:
Oracle Database 11g Express Edition Release 11.2.0.2.0 - 64bit Production

SQL> 
```
#### Creamos el usuario de desarrollo
```
SQL> CREATE USER dev IDENTIFIED BY "dev";

SQL> GRANT ALL PRIVILEGES TO dev;

SQL> exit

:/# exit
```
#### OPCIONAL migre la base de datos
```
docker exec -it back-sgc php artisan migrate
```

### Pruebalo en http://127.0.0.1:8000/chat/

### Soporte adicional con make

#### comandos comunes
```
make up
make build
make migrate
make php
make db
make bash
make rebuild
make reset
make destroy
```



## Construido con 🛠️


* [php](https://www.php.net/download-docs.php)
* [laravel](https://laravel.com/docs/8.x)
* [docker](https://www.docker.com/)
* [docker-compose](https://docs.docker.com/compose/)



## Autores ✒️


* **Santiago Yunda** - *Implementacion* - [Swanky](https://github.com/YUND4)


## Licencia 📄

MIT
