# piccoling-whit-docker
Piccoling es una aplicación web que proporciona un panel de control detallado sobre los platillos de un restaurante. La aplicación lee información de colecciones en MySQL que contienen datos sobre los platillos, como su nombre, precio y la frecuencia con la que se piden.

La aplicación está diseñada para ser utilizada por dos tipos de usuarios: Administrador y Consumidor.
- Administrador: Puede ver la información detallada sobre los usuarios que consumen en sus instalaciones, manipular los platillos e ingredientes disponibles, y ver las facturas generadas.
- Consumidor: Puede ver los platillos disponibles, la cantidad de estos, y realizar su orden sin necesidad de un intermediario. Piccoling permite a los consumidores obtener fácilmente una visión clara de lo que quieren pedir.

## Instalación
Para el funcionamiento de este proyecto, utilizaremos Docker, una plataforma de contenedores que permite empaquetar una aplicación junto con todas sus dependencias en un contenedor virtualizado que se puede ejecutar en cualquier sistema operativo. También emplearemos Apache Spark para aprovechar su capacidad de procesamiento distribuido y su capacidad para manejar grandes conjuntos de datos.<br>
Además, utilizaremos dos máquinas virtuales, ambas deberán tener instalados estos elementos.<br>
Para instalarlos, puedes usar los siguientes comandos:<br>
### Vagrantfile: 
Para el despligue de este proyecto necesitaremos una maquina virtual Linux Ubuntu 22.04 con una IP en especifico, la `192.168.100.4`, el motivo de esto es porque la configuración del proyecto esta mapeada sobre dicha IP, por lo que usar otra IP diferente podria generar conflictos y pasos innecesarios:<br>
Si aun no tiene Vagrant, puede descargarlo de la pagina oficial:<br> 
https://developer.hashicorp.com/vagrant/downloads?product_intent=vagrant <br>
```
# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

  config.vm.box = "base"
end
  
  Vagrant.configure("2") do |config|
  if Vagrant.has_plugin? "vagrant-vbguest"
  config.vbguest.no_install = true
  config.vbguest.auto_update = false
  config.vbguest.no_remote = true
end

config.vm.define :clientePiccoling do |clientePiccoling|
  clientePiccoling.vm.box = "bento/ubuntu-22.04"
  clientePiccoling.vm.network :private_network, ip: "192.168.100.5"
  clientePiccoling.vm.hostname = "clientePiccoling"
  clientePiccoling.vm.box_download_insecure=true
  end
  

  config.vm.define :servidorPiccoling do |servidorPiccoling|
    servidorPiccoling.vm.box = "bento/ubuntu-22.04"
    servidorPiccoling.vm.network :private_network, ip: "192.168.100.4"
    servidorPiccoling.vm.hostname = "servidorPiccoling"
    servidorPiccoling.vm.box_download_insecure=true
  servidorPiccoling.vm.provider "virtualbox" do |v|
    v.cpus = 3
    v.memory = 2048
    end
  end

end
```
### Iniciar las maquinas virtuales:
1. Prender las maquinas `vagrant up`
2. abre 2 terminales de CMD, en una colocas `vagrant ssh servidorPiccoling`  y en la otra colocas `vagrant ssh clientePiccoling`
3. Entra a las maquinas virtuales como un administrados `sudo -i`

### NodeJS:
Este lo usaremos unicamente en el servidorPiccoling

1. Instalamos algunos paquetes:<br>
`apt-get install curl gnupg2 gnupg -y`<br>
2. Importamos el sig. repositorio para instalar nodejs:<br>
`curl -fsSL https://deb.nodesource.com/setup_current.x | sudo -E bash`<br>
3. Luego se procede con la instalación: `apt-get install nodejs`

### MySQL:
Este lo usaremos unicamente en el servidorPiccoling

1. Para instalar mysql usamos el comando `apt-get install mysql-server`
2. Se inicia el servicio con el comando `systemctl start mysql.service`

### Docker:
Necesitaremos Docker en las 2 maquinas (servidorPiccoling y clientePiccoling).<br>
1. Quitar versiones de docker anteriores:<br>
```for pkg in docker.io docker-doc docker-compose docker-compose-v2 podman-docker containerd runc; do sudo apt-get remove $pkg; done```<br>

2. Agregue la clave GPG oficial de docker:<br>
```
sudo apt-get update
sudo apt-get install ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc
 ```
 
3. Agregue el repositorio a Apt sources:<br>
 ```
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
sudo apt-get update
 ```
 
4. Instale la ultima version de docker:<br>
`sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin`<br>
<br>
### docker-compose:
Necesitaremos Docker-compose en las 2 maquinas (servidorPiccoling y clientePiccoling).<br>
1. Verifique que tenga DockerCompose:<br>
`docker compose version`<br>
2. Cree el archivo ~/.vimrc para trabajar con Yaml:<br>
`vim ~/.vimrc`<br>
3. Agregar la siguiente configuración para trabajar conlos archivos yaml.<br>

```
" Configuracion para trabajar con archivos yaml
au! BufNewFile,BufReadPost *.{yaml,yml} set filetype=yaml foldmethod=indent
autocmd FileType yaml setlocal ts=2 sts=2 sw=2 expandtab
```

### Apache Spark:
Necesitaremos Apache Spark en las 2 maquinas (servidorPiccoling y clientePiccoling).<br>

 1. Actualiza el indice de paquetes e instala paquetes de Java:<br>
 `apt update`
`apt install -y openjdk-18-jdk`<br>
 2. Creamos el archivo jdk18.sh para la configuración:<br>
```
cat <<EOF | sudo tee /etc/profile.d/jdk18.sh
export JAVA_HOME=/usr/lib/jvm/java-1.18.0-openjdk-amd64
export PATH=\$PATH:\$JAVA_HOME/bin
EOF
```
3. Despues de este, hacemos:<br>
`source /etc/profile.d/jdk18.sh`
4. Crearemos el dictorio en donde guardaremos los archivos de Spark:<br>
`mkdir labSpark`<br>
`cd labSpark`
5. Descargamos el archivo comprimido de Spark:<br>
`wget https://dlcdn.apache.org/spark/spark-3.5.1/spark-3.5.1-bin-hadoop3.tgz`
6. Y lo descomprimimos:<br>
`tar -xvzf spark-3.5.1-bin-hadoop3.tgz`
7. Luego entramos al directorio de configuración `cd spark-3.5.1-bin-hadoop3/conf/` y hacemos una copia del archivo de configuración de variables de entorno de Spark:<br>
`cp spark-env.sh.template spark-env.sh`<br>
`vim spark-env.sh`
Y al final del archivo introducimos estas instrucciones:<br>
En servidorPiccoling:<br>
```
SPARK_LOCAL_IP=192.168.100.4
SPARK_MASTER_HOST=192.168.100.4
```
En clientePiccoling:<br>
```
SPARK_LOCAL_IP=192.168.100.5
SPARK_MASTER_HOST=192.168.100.4
``` 

### Pip y librerias de Python:
1. Instalamos PIP y Python:<br>
`sudo apt-get install python3`<br>
`sudo apt-get install pip`
2. Instalamos la libreria de PySpark:<br>
`sudo pip install pyspark`
3. pip install pymysql



## Guia
A continuación, proporcionamos los pasos a seguir para desplegar exitosamente la aplicación de Piccoling. Es recomendable preparar dos ventanas activas de cmd: una para el funcionamiento de la máquina del servidor y otra para el funcionamiento del cliente, esto para realizar algunos pasos simultáneamente:<br>

1. Lo primero sera descargar el respositirio 'piccoling-whit-docker' en la terminal #1 servidorPiccoling:<br>
`git clone https://github.com/isabellaperezcav/piccoling-whit-docker`<br>


2. vamos a mover el dataset a la carpeta /labSpark `mv dish.csv /root/labSpark`

   
3. Despues de esto nos dirigimos al directorio donde descargamos pyspark y vamos a iniciar un master (en el servidor) y un worker (en el cliente) para tener acceso al dash `cd labSpark/spark-3.5.1-bin-hadoop3/sbin`
   En servidorPiccoling:<br>
```
./start-master.sh
```
En clientePiccoling:<br>
```
./start-worker.sh spark://192.168.100.4:7077
```
puede verificar que esto fue correcto buscando `http://192.168.100.4:8080` en el browser<br>



4. Despues de esto, salimos de /sbin `cd ..` y entramos a bin `cd bin`


   
5. Ejecutamos `` para corrrer la aplicacion que se encargara del analisis de nuestro dataset


   
7. .
8. 






3. Despues de esto vamos a crear un cluster de docker Swarm con un nodo corriendo en el servidor y otro en el cliente.<br>
    En servidorPiccoling `docker swarm init --advertise-addr 192.168.100.4`  , `docker swarm join-token worker`
    En clientePiccoling vamos a copiar el comando que salio al hacer `docker swarm join-token worker` en el servidor

4. Ejecutamos el stack (en el servidor) dentro de la carpeta del proyecto (`cd piccoling-whit-docker/`) con el sig comando`docker stack deploy -c docker-compose.yml stack_piccoling`
   si quiere verificarlo coloque `docker service ps stack_piccoling`
5. Escalaremos los servicios de la pag web `docker service scale stack_piccoling_web1=8` y `docker service scale stack_piccoling_web2=8`
   si quiere verificarlo coloque `docker service ls`<br>






8. Y luego nos dirigimos a `labSpark/spark-3.4.0-bin-hadoop3/bin` y una vez dentro ejecutamos el siguiente comando:<br>
`./spark-submit --master spark://192.168.100.2:7077 /home/vagrant/bbs71_git/bbs71_docker/spark_app/bbs71_etl.py "/home/vagrant/bbs71_git/bbs71_docker/spark_app/Combined_Flights_2021.csv" "/home/vagrant/bbs71_git/bbs71_docker/spark_app/flights"`<br>
(este proceso puede tardar un rato)<br>
Cuando termine nos debe generar una carpeta `flights` en el directorio `bbs71_git/bbs71_docker/spark_app/` con todos los csv resultado `bbs71_etl.py`, como por ejemplo:<br>
```
part-00000-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  part-00009-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv
part-00001-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  part-00010-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv
part-00002-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  part-00011-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv
part-00003-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  part-00012-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv
part-00004-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  part-00013-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv
part-00005-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  part-00014-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv
part-00006-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  part-00015-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv
part-00007-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  part-00016-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv
part-00008-4a73310c-a9aa-4590-9e8f-c260dbf2a0ee-c000.csv  _SUCCESS
```

9. Ahora para correr la app realizaremos la conexion del Docker Swarm entre servidorUbuntu y clienteUbuntu, y lo haremos de la siguiente forma:
Escribimos este comando `sudo docker swarm init --advertise-addr 192.168.100.2` para iniciarlo y nos dara el siguiente comando con el token para realizar el enlace (si se te olvide puedes usar este `sudo docker swarm join-token worker`) y en la terminal de clienteUbuntu lo escribimos:<br> 
En nuestro caso fue: <br>
`sudo docker swarm join --token SWMTKN-1-4qt4bp8o1jeakj6xtgfsa62esrgb8mq6fyip25444653jv1c2b-cqdk5hl7yf17xi1a943ntw3zo 192.168.100.2:2377`

10. Para agilizar la descarga de las imagenes, realizaremos un pull para asi descargarlas de dockerhub.
```
sudo docker pull bbs71/api-gateway
sudo docker pull bbs71/micro-user
sudo docker pull bbs71/micro-airlines
sudo docker pull bbs71/micro-airports
sudo docker pull bbs71/app
sudo docker pull bbs71/haproxy
sudo docker pull eclipse-mosquitto
sudo docker pull mongo:4.0
```

11. Ya casi para finalizar una vez hecho los pasos anteriores ahora si ya podemos desplegar la aplicación entera usando Docker Swarm, para ello nos devolvemos a  `bbs71_git/bbs71_docker` donde se encuentra el archivo docker-compose.yml y lo ejecutamos usando Swarm:<br>
`sudo docker stack deploy -c docker-compose.yml bbs71`<br>
este comando creará y ejecutará los contenedores de Docker necesarios para cada servicio especificado en el archivo docker-compose.yml y usara los recursos de ambas maquinas.<br>

12. Por ultimo en la terminal #2 de servidorUbuntu nos dirigimos a `labSpark/spark-3.4.0-bin-hadoop3/bin` y una vez dentro ejecutamos el siguiente comando:<br>
`./spark-submit --master spark://192.168.100.2:7077 /home/vagrant/bbs71_git/bbs71_docker/spark_app/bbs71_stream.py "/home/vagrant/bbs71_git/bbs71_docker/spark_app/flights/*csv"`<br>
Nos debe de salir: <br>
```
Comenzando a leer los archivos CSV...
Archivos CSV leídos correctamente.
Conectado a la base de datos
```

13. Ya con todo corriendo nos dirigimos a nuestro navegador de preferencia y colocamos en la barra de busqueda la ip `192.168.100.2` con el puerto `1080` de Haproxy.

14. Tambien podemos ver las estadisticas de haproxy accediendo por `192.168.100.2:1080/haproxy?stats`.<br>
Usuario:<br>
`admin`<br>
Contraseña:<br>
`admin`<br>


 ## Configuración
Para configurar el contenedor Docker del proyecto, es necesario conocer los archivos Dockerfile que se han utilizado para crear las imágenes del contenedor. Cuando se descargue dentro de la carpeta `piccoling-whit-docker`, tendremos las siguientes subcarpetas 

`webPiccoling` es la carpeta donde se encuentran los archivos de toda la pagina como HTML y PHP, en las carpetas que inician con`micro` tenemos todo lo relacionado con los microservicios y el apigateway, en la carpeta `db` tenemos lo correspondiente a la base de datos de sql, `/haproxy` donde esta nuestro balanceador, el archivo `docker-compose.yml` tenemos toda la configuracion para hacer el despliegue, 
 `/piccodata` donde estan los archivos que usaremos para el procesamiento de spark; dentro de cada carpeta se ha creado el Dockerfile que contienen las instrucciones para construir diferentes imágenes de Docker, cada una con su propia configuración y dependencias específicas.
 A continuación, se presentara una breve descripción y captura de cada uno de los Dockerfiles en sus repectivas carpetas utilizados en el proyecto.
### piccoling-whit-docker:<br>

#### 1. Docker-compose.yml<br>
Este es el docker-compose.yml principal, encargado de desplegar todos los servicios que necesitamos:<br>
```
version: '3'
networks:
  cluster_piccoling_default:
services:
  haproxy:
    image: isabellaperezc/haproxyprueba
    deploy:
      placement:
        constraints:
          - node.hostname == servidorPiccoling
    init: true
    depends_on:
      - web1
      - web2
    ports:
      - "5080:80"

  db:
    image: mysql:5.7
    ports:
      - "32000:3306"
    environment:
      MYSQL_ROOT_PASSWORD: piccoling
    volumes:
      - ./db:/docker-entrypoint-initdb.d/:ro
    deploy:
      placement:
        constraints:
          - node.hostname == servidorPiccoling

  usuarios:
    image: isabellaperezc/usuariopiccoling
    depends_on: 
      - db
    ports:
      - "3001:3001"
    deploy:
      placement:
        constraints:
          - node.hostname == servidorPiccoling

  inventario:
    image: isabellaperezc/inventariopiccoling
    depends_on: 
      - db
    ports:
      - "3002:3002"
    deploy:
      placement:
        constraints:
          - node.hostname == servidorPiccoling

  facturas:
    image: isabellaperezc/facturaspiccoling
    depends_on:
      - db
    ports:
      - "3003:3003"
    deploy:
      placement:
        constraints:
          - node.hostname == servidorPiccoling

  web1:
    image: isabellaperezc/webpiccoling
    depends_on:
      - usuarios
      - inventario
      - facturas
    deploy:
      placement:
        constraints:
          - node.hostname == clientePiccoling
    init: true
  
  web2:
    image: isabellaperezc/webpiccoling
    depends_on:
      - usuarios
      - inventario
      - facturas
    deploy:
      placement:
        constraints:
          - node.hostname == clientePiccoling
    init: true
```
En el docker-compose se definen las imagenes de cada uno de los servicios y los parametros que se van a usar; para este proyecto utilizamos los siguientes servicios:

#### 2. /db:

##### init.sql:<br>
Es una imagen ya construida y disponible en Docker Hub de la base de datos MySQL. A esta imagen se le aplicaron volúmenes para copiar los datos en archivos .json dentro del contenedor. La conexión con los microservicios se expone a través del puerto 32000. Cabe recalcar que este servicio solo puede ser ejecutado en la máquina 'servidorPiccoling'.<br>
<br>
```
version: '3'
networks:
  cluster_piccoling_default:
services:
  db:
      image: mysql:5.7
      ports:
        - "32000:3306"
      environment:
        MYSQL_ROOT_PASSWORD: piccoling
      volumes:
        - ./db:/docker-entrypoint-initdb.d/:ro
      deploy:
        placement:
          constraints:
            - node.hostname == servidorPiccoling

```

#### 3. /microUsuarios , /microFacturas , microInventario:
Estos son los tres microservicios disponibles. Para la creación de la imagen de cada uno de ellos, se utilizó el mismo Dockerfile con ligeras diferencias en algunos parámetros y, lo más importante, en el puerto que utilizan. No obstante, todos tienen una estructura muy similar:<br>
##### Dockerfile de los microservicios (se coloca de ejemplo el usado en el microservicio de inventario)
```
FROM node:20

WORKDIR /microInventario

COPY src/controllers /microInventario/src/controllers
COPY src/models /microInventario/src/models
COPY src/index.js /microInventario/src

RUN npm init --yes
RUN npm install express morgan mysql mysql2 axios

EXPOSE 3002

CMD node src/index.js
```
<br>
Para ejecutar estos microservicios, es necesario contar con Node.js y descargar las librerías de NPM. En el WORKDIR se especificará el directorio y se copiarán los archivos package.json, que contienen las dependencias necesarias, como Axios, el cual se encargará de monitorear los puertos no expuestos de los otros microservicios.

##### Microusuarios:<br>
Este es el microservicio encargado de controlar y autenticar a los usuarios que esten disponibles en la base de datos, estara conectado a la base de datos, y transmitiendo por el puerto 3001.

##### MicroInventario:<br>
Este microservicio es el encargado de gestionar la información relacionada con los platillos e ingredientes disponibles y sus tablas de información. Estará conectado a la base de datos y transmitirá a través del puerto 3002.

##### MicroFacturas:<br>
Su función está dedicada únicamente a crear las facturas según lo solicitado por los usuarios. Estará conectado a la base de datos y a los demás microservicios, transmitiendo a través del puerto 3003.<br>

#### 4. /webPiccoling - app1:
Este servicio se encarga de cargar la aplicación web construida en PHP en su versión de producción. Para utilizar HAProxy y realizar el balanceo de carga, hemos creado una copia de este servicio, llamada app2.<br>
##### Dockerfile de webPicooling
```
FROM php:7.1-apache
COPY . /var/www/html/webPiccoling
EXPOSE 80
```

#### 5. /haproxy:
HAProxy será el servicio encargado de balancear las cargas de la aplicación entre dos imágenes, permitiéndonos también ver un informe detallado del estado de cada una de ellas y del número de peticiones ejecutadas.<br>
##### Dockerfile de haproxy
```
FROM haproxy:2.3
RUN mkdir -p /run/haproxy/
COPY haproxy.cfg /usr/local/etc/haproxy/haproxy.cfg
```

Dentro de dockerfile de Haproxy le damos las intrucciones de usar haproxy:2.3, para despues crear el directorio `/run/haproxy` dentro del contenedor. Por ultimo realizamos la copia de dos archivos para la configuracion del haproxy.<br>







CAMBIAR ESTO POR EL BROKER QUE VAYAMOS A USAR

#### 6. /mqtt:
MQTT es el broker de mensajeria escogio para ser de intermediario entre nuestra app y el framework de computación distribuida y procesamiento de datos, Apache Spark, encargado de escuchar los topics por donde se transmitiran los datos que luego se convertiran el consultas de PySpark.<br>

 AYUDAAAAAAAAAAAAA

#### 7. /PiccoData




Aqui es donde se encuentran los scripts de pyspark para realizar el procesamiento distribuido, uno de ellos es `bbs71_etl.py` encargado de realizar la extración, limpieza y carga del dataset de kaggle y `bbs71_stream.py` encargado de hacer el procesamiento en streaming de Apache Spark, tambien esta sera la carpeta en donde se almacenara el dataset de kaggle `Combined_Flights_2021.csv` y en donde se guardaran posteriormente los .csv con los datos ya transformados.
