import json
from decimal import Decimal
from datetime import datetime
import pymysql.cursors
import requests
import csv

# Datos de conexión a la base de datos
host = '192.168.100.4'
port = 32000
user = 'root'
password = 'piccoling'
database = 'piccoling'

# Extiende la clase JSONEncoder para manejar objetos Decimal y datetime
class DecimalEncoder(json.JSONEncoder):
    def default(self, obj):
        if isinstance(obj, Decimal):
            return str(obj)
        if isinstance(obj, datetime):
            return obj.isoformat()
        return super().default(obj)

# Función para obtener los datos de la tabla "facturas" y devolverlos en formato JSON
def get_facturas_data(conn):
    sql = "SELECT * FROM facturas"
    with conn.cursor() as cursor:
        cursor.execute(sql)
        result = cursor.fetchall()
    return result

# Función para guardar los datos en un archivo CSV
def save_data_to_csv(data, output_path):
    if not data:
        print("[INFO] No se encontraron datos para guardar en el CSV")
        return

    fieldnames = data[0].keys()
    try:
        with open(output_path, mode='w', newline='') as file:
            writer = csv.DictWriter(file, fieldnames=fieldnames)
            writer.writeheader()
            for row in data:
                writer.writerow(row)
        print(f"Datos guardados correctamente en {output_path}")
    except Exception as e:
        print(f"Error al guardar datos en CSV: {e}")

def main():
    connection = None  # Inicializa la variable connection fuera del bloque try
    try:
        # Conexión a la base de datos
        connection = pymysql.connect(host=host,
                                     port=port,
                                     user=user,
                                     password=password,
                                     database=database,
                                     cursorclass=pymysql.cursors.DictCursor)

        # Obtener datos de la tabla "facturas"
        data = get_facturas_data(connection)
        if data:
            # Imprime los datos en formato JSON
            json_data = json.dumps(data, cls=DecimalEncoder, indent=4)
            print(json_data)

            # Guardar los datos en un archivo CSV
            output_path = "/root/piccoling-whit-docker/db/facturas.csv"
            save_data_to_csv(data, output_path)
        else:
            print("[INFO] No se encontraron datos en la tabla facturas")

        # Conexión a la página web
        url = "http://192.168.100.4:5080/webPiccoling/"
        headers = {"Content-Type": "application/json"}
        response = requests.get(url, headers=headers)

        if response.status_code == 200:
            # Aquí puedes procesar la respuesta de la página web
            print("Conexión exitosa a la página web")
        else:
            print(f"Error al conectar a la página web. Código de estado: {response.status_code}")

    except Exception as e:
        print(f"Error: {e}")

    finally:
        if connection:
            connection.close()

if __name__ == "__main__":
    main()

