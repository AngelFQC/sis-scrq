Sistema de Valoración para el Servicio de Cirugía y Reparación de Quemados
========

Sistema para el proceso de Valoración de pacientes en  elServicio de Cirugía y Reparación de Quemados del Hospital Regional Docente Las Mercedes - Chiclayo

Equipo de desarrollo
====================
Profesor a cargo
- Ing. Juan Torres Benavides

Desarrollo
- Angel Fernando Quiroz Campos
- Rogger Rojas Quiroz

Documentación
- César Iván Nuñez Manayay
- Teresita de Jesús Hernández Coronel
- Erick Tejada Mena


Requerimientos
==============
Servidor Web Apache 2.4
	Módulo	rewrite_rule	habilitado

PHP 5.3 en adelante

MySQL 5.0 en adelante
	Motor	InnoDB		instalado

Navegador Web Mozilla Firefox, Google Chrome, Opera o Internet Explorer 9
	HTML5 y CSS3	soportado
	JavaScript		habilitado


Instalación
===========
* El código fuente de la aplicación se encuentra en el directorio "sis-scrq"

Crear base de datos en MySQL

Ejecutar el script de creación de tablas en la base de datos
	Archivo: "base de datos.sql"

Si se está ejecutando desde un servidor con dominio. Habilitar servidor
	Archivo: "scrq-config.php"
	Comentar línea 03 y descomentar la línea 05

Si se está ejecutando desde un servidor local (localhost). Habilitar servidor local
	Archivo: "scrq-config.php"
	Comentar línea 05 y descomentar la línea 03

Verificar los datos de configuración de conexión al servidor de base de datos
	Archivo: "scrq-config.php"
	Línea 7: Servidor, usuario, contraseña, nombre de base de datos


Ingreso
=======
Usuario: lmendoza
Contraseña: #lFknhqbGc=X
