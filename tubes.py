import paho.mqtt.client as mqtt
import mysql.connector
import re  # Import untuk regex (regular expressions) yang akan membantu ekstraksi data

# Koneksi ke database MySQL
mydb = mysql.connector.connect(
    host="localhost",  # Ganti dengan alamat server MySQL Anda
    user="root",       # Ganti dengan username MySQL Anda
    password="",       # Ganti dengan password MySQL Anda
    database="smart_farming"  # Nama database yang sesuai
)

print("Koneksi ke database:", mydb)

mycursor = mydb.cursor()

# Fungsi untuk menangani koneksi ke broker MQTT
def on_connect(client, userdata, flags, rc):
    print("Connected with result code " + str(rc))
    client.subscribe("wokwi/tubes/iot/klp6")  # Topik dari kode Wokwi

# Fungsi untuk menangani pesan yang diterima dari MQTT
def on_message(client, userdata, msg):
    try:
        # Mengambil data dari payload MQTT
        payload = str(msg.payload, 'utf-8')
        print(f"Pesan diterima: {payload}")

        # Menggunakan regular expression untuk mengekstrak data yang diperlukan
        # Ekstraksi suhu, kelembaban, dan status kelembaban tanah
        temp_match = re.search(r"Temp:\s*([-\d.]+)C", payload)
        hum_match = re.search(r"Humidity:\s*([-\d.]+)%", payload)
        soil_match = re.search(r"Soil:\s*(Tanah\s*Basah|Tanah\s*Kering)", payload)

        if temp_match and hum_match and soil_match:
            temp = temp_match.group(1)  # Ambil suhu
            hum = hum_match.group(1)    # Ambil kelembaban
            soil_moisture = "1" if "Tanah Kering" in soil_match.group(1) else "0"  # 1 = Kering, 0 = Basah

            print(f'Suhu: {temp}C, Kelembaban: {hum}%, Kelembaban Tanah: {soil_moisture}')

            # Menyimpan data ke dalam tabel sensor_data
            sql = "INSERT INTO sensor_data (temperature, humidity, soil_moisture, timestamp) VALUES (%s, %s, %s, NOW())"
            val = (temp, hum, soil_moisture)
            
            mycursor.execute(sql, val)
            mydb.commit()
            print("Data berhasil disimpan ke database!")
        else:
            print("Format data salah! Tidak dapat mengekstrak suhu, kelembaban, atau status kelembaban tanah.")
    
    except Exception as e:
        print(f"Terjadi kesalahan saat memproses pesan: {e}")

# Membuat objek client MQTT
client = mqtt.Client()
client.on_connect = on_connect
client.on_message = on_message

# Koneksi ke broker MQTT
client.connect("mqtt.my.id", 1883, 60)

# Loop untuk terus mendengarkan pesan dari broker
client.loop_forever()
