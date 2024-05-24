from pyspark.sql import SparkSession
from pyspark.sql.functions import col

# Crear una sesión de Spark
spark = SparkSession.builder.appName("DataApp").getOrCreate()

# Cargar el conjunto de datos
data = spark.read.format("csv").option("header", "true").load("/root/piccolabSpark/dish.csv")

# Filtrar las filas donde 'Precio' y 'Frecuencia_pedido' no sean nulos
filtered_data = data.filter(col("Precio").isNotNull() & col("Frecuencia_pedido").isNotNull())

# Seleccionar solo las filas con los campos requeridos
selected_data = filtered_data.select("Fecha", "Nombre", "Precio", "Cantidad", "Frecuencia_pedido", "Tiempo_minutos", "Valoracion")

# Mostrar los datos obtenidos
selected_data.show()

# Guardar los datos filtrados en un archivo
output_path = "/root/piccolabSpark/resultados"
selected_data.write.format("csv").option("header", "true").save(output_path)

# Detener la sesión de Spark
spark.stop()
