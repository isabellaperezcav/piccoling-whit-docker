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
 v.cpus = 3
 v.memory = 2048
 end
 end
end
```

### Docker:
Necesitaremos Docker en las 2 maquinas de servidorPiccoling y clientePiccoling.<br>
1. Quitar versiones de docker anteriores:<br>
`for pkg in docker.io docker-doc docker-compose docker-compose-v2 podman-docker containerd runc; do sudo apt-get remove $pkg; done`<br>
y luego  `sudo apt-get update`
2. Agregue la clave GPG oficial de docker:<br>
```
sudo apt-get update / 
sudo apt-get install ca-certificates curl/ 
sudo install -m 0755 -d /etc/apt/keyrings /
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc /
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
`sudo apt-get update`<br>
`sudo apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin`
### docker-compose:
Necesitaremos Docker-compose en las 2 maquinas de servidorPiccoling y clientePiccoling.<br>
1. Instale DockerCompose:<br>
`sudo apt-get install docker-compose-plugin`<br>
2. Cree el archivo ~/.vimrc para trabajar con Yaml:<br>
`vim ~/.vimrc`<br>
3. Agregar la siguiente configuración para trabajar conlos archivos yaml.<br>

```
" Configuracion para trabajar con archivos yaml
au! BufNewFile,BufReadPost *.{yaml,yml} set filetype=yaml foldmethod=indent
autocmd FileType yaml setlocal ts=2 sts=2 sw=2 expandtab
```

### Apache Spark:
 1. Instala paquetes de Java:<br>
`sudo apt install -y openjdk-18-jdk`<br>
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
7. Luego entramos a `/labSpark/spark-3.3.1-bin-hadoop3/conf` y hacemos una copia del archivo de configuración:<br>
`cp spark-env.sh.template spark-env.sh`<br>
Y introducimos estas instrucciones:<br>
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
### Librerias:
1. Editor de texto Vim:<br>
`sudo apt-get install vim -y`<br>
2. Zip y Unzip para descomprimir archivos:<br>
`sudo apt-get install zip unzip -y`<br>
### Pip y librerias de Python:
1. Instalamos PIP y Python:<br>
`sudo apt-get install python3`<br>
`sudo apt-get install pip`
2. Instalamos la libreria MySQL:<br>
`sudo apt install mysql-server`
3. Instalamos la libreria de PySpark:<br>
`sudo pip install pyspark`







## Configuración
Para configurar el contenedor Docker del proyecto, es necesario conocer los archivos Dockerfile que se han utilizado para crear las imágenes del contenedor. Cuando se descargue dentro de la carpeta `piccoling-whit-docker`, tendremos las siguientes subcarpetas 

`webPiccoling` es la carpeta donde se encuentran los archivos de toda la pagina como HTML y PHP, en las carpetas que inician con`micro` tenemos todo lo relacionado con los microservicios y el apigateway, en la carpeta `db` tenemos lo correspondiente a la base de datos de sql, `/haproxy` donde esta nuestro balanceador, el archivo `docker-compose.yml` tenemos toda la configuracion para hacer el despliegue, 
 `/piccodata` donde estan los archivos que usaremos para el procesamiento de spark; dentro de cada carpeta se ha creado el Dockerfile que contienen las instrucciones para construir diferentes imágenes de Docker, cada una con su propia configuración y dependencias específicas. A continuación, se presentara una breve descripción y captura de cada uno de los Dockerfiles en sus repectivas carpetas utilizados en el proyecto.

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

##### Mongodb:<br>
Es una imagen ya construida y disponible en Docker Hub de la base de datos mongodb, a la cual, se le aplico volumenes con el fin de copiar la data en archivos .json dentro de contenedor, ya que necesitaremos que la conexion de mongo con los demas servicios se expuso el puerto 27017, cabe recalcar que este servicio solo podra ser ejecuta dentro de la maquina de 'servidorUbuntu'.<br>
<br>
```
version: '3'

services:
    mongodb:
        image: mongo:4.0
        restart: always
        container_name: mongodb
        volumes:
            - ./mongo/data:/data/db
            - ./flights.json:/json/flights.json
            - ./users.json:/json/users.json
            - ./flight_stats.json:/json/flight_stats.json
        ports:
            - 27017:27017
```
Aqui creamos el contenedor de mongo sacado de dockerhub, al cual le aplicaremos volumenes con los archivos .json, los cuales deben de estar dentro del contenedor.<br>

#### 3. /backend:
Aqui se encuentran los 3 microservicios y el apigetway, para la creacion de la imagen de cada uno ellos se uso el mismo dockerfile, con una ligera diferencia como algunos parametros y lo mas importante el puerto por donde salen, pero en si tienen la misma estructura:<br>
##### Dockerfile de los microservicios y apigateway
```
FROM node:16
WORKDIR /home/node/app
COPY package*.json ./
RUN npm install
COPY .env ./

RUN mkdir -p ./src
COPY ./src ./src
EXPOSE 3000
CMD ["node", "./src/index.js"]
```
<br>
Para ejecutar los microservicios de Blackbird, es necesario contar con Nodejs y descargar la librería de NPM. En el WORKDIR se especificará el directorio de trabajo y se copiarán los archivos package.json, que contienen las dependencias que se utilizarán, como Axios, el cual se encargará de monitorear los puertos no expuestos de los otros microservicios.

##### Apigateway:<br>
Es el servicio encargado de tomar los puertos de cada uno de los microservicios (microuser, microairlines y microairports) ya que los microservicios no se comunican entre ellos, y con el fin de no exponer multiples puertos y su vez simplificar la obtencion de los datos, constrimos este apigateway para concentrar las multiples salidas de los 3 puertos en uno solo, que en este caso es el puerto 3000.

##### Microuser:<br>
Este es el microservicio encargado de controlar y autenticar a los usuarios que esten disponibles en la base de datos, estara conectado a la base de datos, y transmitiendo por el puerto 3001.

##### MicroAirlines:<br>
MicroAirlines es el microservicio encargado de gestionar la información relacionada con las aerolíneas y sus tablas de informacion, estara conectado a la base de datos y dependera del broker de mensajeria MQTT, y transmitiendo por el puerto 3002.

##### MicroAirports:<br>
MicroAirports cumple un papel similar a MicroAirlines, solo que su funcion esta dedicada unicamente a los aeropuertos, estara conectado a la base de datos y dependera del broker de mensajeria MQTT, y transmitiendo por el puerto 3003.<br>

#### 4. /app:
App-1 es el servicio encargado de cargar la aplicacion web construida en Vuejs en su version de producción, y con el fin de usar haproxy y realizar el balanceo de carga, hemos realizado una copia de este servicio llamado App-2.<br>
##### Dockerfile de app
```
FROM ubuntu
RUN apt update
RUN apt install -y apache2
RUN apt install -y apache2-utils
RUN apt clean

COPY ./000-default.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p /var/www/html/blackbird/dist

COPY ./dist /var/www/html/blackbird/dist/
EXPOSE 80
CMD ["apache2ctl", "-D", "FOREGROUND"]
```
En este punto se establecerán los parámetros necesarios para el funcionamiento de nuestra aplicación web. Para ello, se instalará el servidor de Apache (Apache2) y se copiará la configuración de nuestro sitio web dentro del contenedor de la imagen. Finalmente, se creará una carpeta dentro del contenedor que contendrá todos los archivos de nuestro sitio web creado con Vuejs.<br>

#### 5. /haproxy:
Haproxy sera el servicio encargado de balancear entre dos imagenes de nuestra app, permitiendonos tambien ver un informe detallado de el estado de cada una de ellas y el numero de peticiones ejecutadas.<br>
##### Dockerfile de haproxy
```
FROM haproxy:2.3
RUN mkdir -p /run/haproxy/
COPY haproxy.cfg /usr/local/etc/haproxy/haproxy.cfg
COPY errors/503.http /usr/local/etc/haproxy/errors/503.http
```
##### haproxy.cfg
```
backend web-backend
   balance roundrobin
   stats enable
   stats auth admin:admin
   stats uri /haproxy?stats

   server app-1 app-1:80 check
   server app-2 app-2:80 check

frontend http
  bind *:80
  default_backend web-backend
```
Dentro de dockerfile de Haproxy le damos las intrucciones de usar haproxy:2.3, para despues crear el directorio `/run/haproxy` dentro del contenedor. Por ultimo realizamos la copia de dos archivos, uno para la configuracion del haproxy y el otro para una pagina personalizada del error 503.<br>

#### 6. /mqtt:
MQTT es el broker de mensajeria escogio para ser de intermediario entre nuestra app y el framework de computación distribuida y procesamiento de datos, Apache Spark, encargado de escuchar los topics por donde se transmitiran los datos que luego se convertiran el consultas de PySpark.<br>

##### docker-compose de mqtt
```
version: '3'

services:
  mqtt:
    image: eclipse-mosquitto
    restart: always
    volumes:
      - ./mosquitto/config:/mosquitto/config
      - ./mosquitto/data:/mosquitto/data
      - ./mosquitto/log:/mosquitto/log
    ports:
      - 1883:1883
      - 9001:9001
```
En este docker-compose al igual que con el de mongodb, hacemos uso de los volumenes para copiar los archivos de configuración de mqtt.<br>
#### 7. /spark_app
Aqui es donde se encuentran los scripts de pyspark para realizar el procesamiento distribuido, uno de ellos es `bbs71_etl.py` encargado de realizar la extración, limpieza y carga del dataset de kaggle y `bbs71_stream.py` encargado de hacer el procesamiento en streaming de Apache Spark, tambien esta sera la carpeta en donde se almacenara el dataset de kaggle `Combined_Flights_2021.csv` y en donde se guardaran posteriormente los .csv con los datos ya transformados.

## Guia
A continuacion daremos el paso a seguir para desplegar de forma exitosa la app de Blackbird (Es recomendable ir preparando otras 2 ventana de cmd, una en la misma maquina de servidor y otra en cliente para realizar algunos pasos a la vez):<br>

1. Lo primero sera descargar el respositirio de bbs71 en la terminal #1 servidorUbuntu:<br>
`git clone https://github.com/SPinzon12/bbs71_git`<br>

2. Despues de esto nos dirigimos al directorio `bbs71_git/bbs71_docker`, y lo que haremos sera descargar el archivo flights.json y el dataset combined_flights_2021.csv que son demasiado pesados para git, lo haremos con el siguiente comando:<br>
`wget https://www.dropbox.com/s/npd87j2k5yxul2r/bbs71_data.zip`

3. Lo siguiente sera descomprimir el archivo .zip con `unzip bbs71_data.zip`, al hacerlo nos dara 2 archivos `Combined_Flights_2021.csv` y `flights.json` los cuales tendremos que mover a directorios diferentes de la siguiente forma:<br>
`mv Combined_Flights_2021.csv ./spark_app/` y `mv flights.json ./db/`<br>

4. Luego en `bbs71_docker/db` iniciamos el docker-compose de la base de datos de con el fin de subir los json:<br>
`sudo docker compose up -d`<br>

5. Una vez hecho esto, entraremos al contenedor de mongo con el fin de subir los archivos .json al cluster de mongo y para ello usaremos los comandos:<br> 
Para visualizar el ID del contenedor usamos:<br>
`sudo docker ps`<br>
Deberia mostrarnos algo asi, y copiamos el `CONTAINER ID`:<br>
```
root@vagrant:~/bbs71_git/bbs71_docker/db# docker ps
CONTAINER ID   IMAGE       COMMAND                  CREATED         STATUS         PORTS                                           NAMES
8867c3571a3b   mongo:4.0   "docker-entrypoint.s…"   5 seconds ago   Up 4 seconds   0.0.0.0:27017->27017/tcp, :::27017->27017/tcp   mongodb
```
Ahora entramos en el contenedor:<br>
`sudo docker exec -it <id del contenedor> /bin/bash`<br>
Y navegamos al directorio `/json` y ejecutaremos los siguientes comandos para subirlos al cluster:<br>
Estos comandos importan los archivos .json especificando el nombre de la base de datos, el nombre de la colección, el archivo y el tipo de archivo.<br>
`mongoimport --db bbs71_db --collection flights --type json --file /json/flights.json --jsonArray`<br>
`mongoimport --db bbs71_db --collection users --type json --file /json/users.json --jsonArray`<br>
`mongoimport --db bbs71_db --collection flight_stats --type json --file /json/flight_stats.json --jsonArray`<br>

6. Una vez hecho esto ya podemos salir del contenedor con `exit` y ahora podemos detener el contenedor de mongo  con `sudo docker ps` para verlo y `sudo docker stop <id del contenedor>` para detenerlo.<br>

7. Una vez cerremos el contenedor de mongo, nos dirigimos a `cd ../../labSpark/spark-3.4.0-bin-hadoop3/sbin` y iniciamos el master y el worker en la maquina de servidorUbuntu:<br>
Master:<br>
`./start-master.sh`<br>
Worker:<br>
`./start-worker.sh spark://192.168.100.2:7077`<br>

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
15. Para loguearse en nuestra app hemos colocado 4 ejemplos de usuarios, 2 de aeropuerto y otros 2 de aerolinea:

##### Aeropuertos:
1. Usuario:<br>
`BNA`<br>
Contraseña:<br>
`10693`<br>

2. Usuario:<br>
`ROA`<br>
Contraseña:<br>
`14574`<br>
#### Aerolineas:
1. Usuario:<br>
`Horizon_Air`<br>
Constraseña:<br>
`QX`<br>
2. Usuario:<br>
`Delta_Air`<br>
Constraseña:<br>
`DL`<br>
