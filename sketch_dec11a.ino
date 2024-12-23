#include <DHT.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <ESP32Servo.h>  // Library Servo

// Konfigurasi pin dan parameter LDR
const int ldrPin = 35; // Pin ADC untuk LDR
float gama = 0.7;      // Faktor gamma
float rl10 = 50;       // Resistansi referensi pada LDR

// Konfigurasi pin dan parameter DHT22
#define DHT_PIN 15     // Pin untuk sensor DHT22
#define DHT_TYPE DHT22
DHT dht(DHT_PIN, DHT_TYPE);

// Konfigurasi pin dan parameter Soil Moisture Sensor (DO)
const int soilMoisturePin = 34; // Pin Digital untuk sensor kelembaban tanah

// Inisialisasi LCD I2C dengan alamat 0x27 dan ukuran 16x2
LiquidCrystal_I2C lcd(0x27, 16, 2);

// Inisialisasi servo motor
Servo myServo;  // Membuat objek servo
const int servoPin = 13;  // Pin untuk menghubungkan servo

void setup() {
  Serial.begin(9600);

  // Inisialisasi LDR
  pinMode(ldrPin, INPUT);
  Serial.println("Membaca data dari sensor LDR, DHT22, dan Soil Moisture...");

  // Inisialisasi DHT22
  dht.begin();

  // Inisialisasi Soil Moisture Sensor (DO)
  pinMode(soilMoisturePin, INPUT);

  // Inisialisasi LCD
  lcd.begin(16, 2); // Tentukan jumlah kolom dan baris LCD
  lcd.backlight(); // Nyalakan backlight
  lcd.setCursor(0, 0); // Set posisi kursor di baris pertama
  lcd.print("Sensor Aktif...");
  delay(2000); // Tampilkan pesan selama 2 detik

  // Inisialisasi servo
  myServo.attach(servoPin);  // Hubungkan pin servo
  myServo.write(0);  // Posisi awal servo pada 0 derajat
}

void loop() {
  // Membaca nilai ADC dari LDR
  int ldrValue = analogRead(ldrPin);

  // Mengonversi nilai ADC menjadi tegangan (0-3.3V untuk ESP32)
  float ldrVoltage = ldrValue / 4095.0 * 3.3;

  // Validasi untuk menghindari pembagian dengan nol
  if (ldrVoltage >= 3.3) {
    Serial.println("Kesalahan: Tegangan LDR mendekati batas referensi!");
    delay(1000);
    return;
  }

  // Menghitung resistansi LDR berdasarkan tegangan
  float ldrResistance = 2000 * ldrVoltage / (3.3 - ldrVoltage);

  // Menghitung intensitas cahaya (Lux)
  float lux = pow(rl10 * 1e3 * pow(10, gama) / ldrResistance, (1 / gama));

  // Membaca data dari sensor DHT22
  float temperature = dht.readTemperature();
  float humidity = dht.readHumidity();

  // Membaca data dari sensor Soil Moisture (DO)
  int soilMoistureValue = digitalRead(soilMoisturePin);
  String soilMoistureStatus = (soilMoistureValue == HIGH) ? "Tanah Kering" : "Tanah Basah";

  // Validasi data DHT22
  if (isnan(temperature) || isnan(humidity)) {
    Serial.println("Kesalahan: Gagal membaca data dari sensor DHT22!");
  } else {
    // Menampilkan data di Serial Monitor
    Serial.print("Nilai ADC LDR: ");
    Serial.print(ldrValue);
    Serial.print(" | Tegangan: ");
    Serial.print(ldrVoltage, 3);
    Serial.print(" V | Intensitas Cahaya: ");
    Serial.print(lux, 3);
    Serial.println(" Lux");

    Serial.print("Suhu: ");
    Serial.print(temperature, 1);
    Serial.println(" C");

    Serial.print("Kelembapan: ");
    Serial.print(humidity, 1);
    Serial.println(" %");

    Serial.print("Status Soil Moisture: ");
    Serial.println(soilMoistureStatus);

    // Menampilkan data di LCD
    lcd.clear(); // Hapus layar LCD
    lcd.setCursor(0, 0); // Set posisi kursor ke baris pertama
    lcd.print("Suhu: ");
    lcd.print(temperature, 1);
    lcd.print(" C");

    lcd.setCursor(0, 1); // Set posisi kursor ke baris kedua
    lcd.print("Tanah: ");
    lcd.print(soilMoistureStatus);

    // Kontrol Servo berdasarkan status kelembaban tanah
    if (soilMoistureValue == HIGH) {  // Jika tanah kering
      myServo.write(90);  // Servo bergerak ke 180 derajat
    } else {  // Jika tanah basah
      myServo.write(0);  // Servo kembali ke 0 derajat
    }
  }

  // Delay untuk pembaruan data
  delay(2000);
}
