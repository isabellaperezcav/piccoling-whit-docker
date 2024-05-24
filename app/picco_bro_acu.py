from pyspark.sql import SparkSession
from pyspark import SparkContext

# Inicializar la sesión de Spark
spark = SparkSession.builder.getOrCreate()
sc = SparkContext.getOrCreate()

# Crear un diccionario de productos y transmitirlo
productos = {"Fecha": "", "Nombre": "", "Precio": 0, "Cantidad": 0, "Frecuencia_pedido": 0, "Tiempo_minutos": 0, "Valoracion": 0}
broadcast_productos = sc.broadcast(productos)

# Crear un acumulador para cada campo
acumuladores = {campo: sc.accumulator(0) for campo in productos}

# Crear un RDD con los datos de los productos
rdd = spark.sparkContext.parallelize([productos])

def procesar_producto(producto):
    # Acceder al diccionario de productos de la variable de transmisión
    productos_broadcast = broadcast_productos.value

    # Si el campo está en el diccionario, incrementar el acumulador correspondiente
    for campo in producto:
        if campo in productos_broadcast:
            acumuladores[campo].add(1)

# Aplicar la función a cada producto del RDD
rdd.foreach(procesar_producto)

# Imprimir el valor de cada acumulador
for campo, acumulador in acumuladores.items():
    print(f"El campo '{campo}' ha aparecido {acumulador.value} veces")

# Detener la sesión de Spark
spark.stop()
