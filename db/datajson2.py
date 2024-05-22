import json
from decimal import Decimal
from datetime import datetime
import pymysql.cursors
import requests

# Datos de conexión a la base de datos
host = '192.168.100.4'
port = 32000
user = 'piccoling'
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
