# Instalación del SIIGS

## Requerimientos

* Apache 2.2
* MySQL 5.5
* PHP 5.4


## Instalación de los requerimientos

A continuación se describen los pasos para instalar la plataforma web en un servidor Debian GNU/Linux versión 7.

Antes de continuar es necesario resaltar la importancia de los indicador "**#**" el cual significa que el comando debe ser ejecutado como usuario **root** y "**$**" que debe ser ejecutado como un **usuario normal**, en ambos casos desde una **consola de comandos**.

<blockquote>
    Actualizamos la lista de paquetes del sistema operativo.
    
    <p># apt-get update</p>
    
    Instalamos todas las librerías y aplicaciones que se utilizan en la plataforma (Apache, MySQL, PHP y Git).
    
    <p># apt-get install php5 php5-xdebug php-apc php5-cli php5-xsl php5-intl php5-mcrypt apache2 mysql-server mysql-common mysql-client git-core curl php5-ldap php5-mysql php5-json php5-curl</p>
</blockquote>

Durante el proceso de instalación se solicitará la configuración del usuario administrador (root) de MySQL se recomienda establecer una contraseña.


## Crear directorio de trabajo

El directorio de trabajo puede variar de acuerdo a la configuración o preferencias que se desee utilizar durante la instalación. Como ejemplo se usará el directorio de instalación **/var/www/siigs**.

<blockquote>
    Creamos el directorio

    <p># mkdir /var/www/siigs</p>
    
    Esta carpeta debe estar accesible y tener permisos de escritura para le usuario de Apache (www-data en Debian), por lo que asignamos como dueño del directorio al usuario www-data:

    <p># chown -R www-data /var/www/siigs</p>

    Como usuario normal del sistema tenemos que ejecutar el siguiente comando con el objetivo de tener permisos sobre la carpeta que acabamos de crear:

    <p>$ sudo chown -R `whoami` /var/www/siigs</p>
    
    Accedemos a la carpeta web del Apache

    <p>$ cd /var/www</p>
</blockquote>

```
Nota: Prestar atención en las comillas invertidas del comando whoami.
```

## Obtener el código fuente

El proyecto completo puede ser descargardo desde: [https://github.com/schiapassm2015/SIIGS](https://github.com/schiapassm2015/SIIGS) o clonar el repositorio ejecutando los el siguiente comando:

<blockquote>
    $ sudo git clone https://github.com/schiapassm2015/SIIGS.git siigs
</blockquote>

Recuerda que actualmente estamos en el directorio */var/www* y el último parámetro del git clone es la carpeta en donde se descargará el código fuente del repositorio, en nuestro caso la carpeta que se creó en el paso anterior, es decir *siigs*.

Es necesario dar permisos de lectura y escritura a determinados directorios ya que la plataforma crea archivos en tiempo de ejecución que son necesarios para su funcionamiento óptimo.

<blockquote>
    <p>$ sudo chmod 766 -R /var/www/siigs/application/cache</p>

    <p>$ sudo chmod 766 -R /var/www/siigs/application/json</p>

    <p>$ sudo chmod 766 -R /var/www/siigs/application/logs</p>
</blockquote>

## Configuración

En este caso, se considera que nuestro hostname es *localhost*. Para que la plataforma sea funcional es necesario crear un VirtualHost, para esto es necesario editar el archivo /etc/apache2/sites-available/default.

<blockquote>
# gedit /etc/apache2/sites-available/default
</blockquote>

Agregar el contenido al final del archivo:

    <VirtualHost *:80>
    	DocumentRoot "/var/www/siigs"
    	ServerName siigs.localhost
    	
    	<Directory "/var/www/siigs">
    		AllowOverride All
    		Order allow,deny
    		Allow from all
    		Require all granted
        	AuthType none
        	Options FollowSymLinks
        	Satisfy Any
    	</Directory>
    	ErrorLog ${APACHE_LOG_DIR}/error.siigs.log
    
    	# Possible values include: debug, info, notice, warn, error, crit,
    	# alert, emerg.
    	LogLevel warn
    
    	CustomLog ${APACHE_LOG_DIR}/access.siigs.log combined
    </VirtualHost>


En el archivo /etc/hosts agregamos la línea 

<blockquote>
    127.0.0.1 siigs.localhost
</blockquote>

```
Nota: Consideramos que la dirección ip del host es 127.0.0.1 que es la asignada por defecto a localhost.
```

Activar el módulo mod_rewrite, para poder utilizar las urls amigables

<blockquote>
    # a2enmod rewrite
</blockquote>

Reiniciar apache

<blockquote>
    # /etc/init.d/apache2 restart
</blockquote>


## Configuración de MySQL

Nos conectamos al servidor MySQL con el siguiente comando:

<blockquote>
    $ mysql -u root -h localhost -p
</blockquote>

En caso de haber asignado una contraseña al usuario root durante la instalacion del servidor MySQL, esta debe de ser tecleada, en caso contrario solo presionar enter para continuar.

Primeramente, crearemos la base de datos:

<blockquote>
    CREATE DATABASE siigs;
</blockquote>

Será necesario crear el usuario dueño de la base de datos, para esto se ejecuta el siguiente comando:

<blockquote>
     GRANT ALL PRIVILEGES ON siigs.* TO 'usrsiigs'@'localhost' IDENTIFIED BY 'pwdsiigs' WITH GRANT OPTION;
</blockquote>

Procedemos a cargar datos iniciales. Con este comando se insertan los datos iniciales del sistema.

<blockquote>
    PENDIENTE
</blockquote>


## Iniciar la plataforma

En este punto estamos listos para acceder a la plataforma desde la siguiente dirección:

[**http://siig.localhost**](http://siig.localhost)