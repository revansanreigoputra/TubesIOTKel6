<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sensor dan Galon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        * {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-100">

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "smart_farming";  // Sesuaikan dengan nama database Anda
    
    // Membuat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Memeriksa koneksi
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    ?>

    <!-- Header -->
    <header class="bg-[#ff7f00] text-white py-4 px-6 flex items-center justify-between">
        <div class="flex items-center">
            <img src="polindra.png" alt="Logo" class="w-8 h-8 mr-4">
            <h1 class="text-xl font-semibold">Smart Farm Penyiraman Tanaman</h1>
        </div>  
        <p class="bg-orange-300 text-white py-2 px-4 rounded-full text-sm">Admin</p>
    </header>

    <!-- Dashboard -->
    <div class="max-w-[90%] mx-auto p-6 mt-6 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>

        <?php
        // Query untuk mendapatkan suhu, kelembaban, dan kelembaban tanah terbaru
        $sql = "SELECT temperature, humidity, soil_moisture, timestamp FROM sensor_data ORDER BY timestamp DESC LIMIT 1";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $sensorData = $result->fetch_assoc();
        }
        ?>

        <!-- Data Sensor Terkini -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
            <div class="bg-orange-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-500">Suhu Terkini</p>
                <p class="text-xl font-semibold"><?= isset($sensorData['temperature']) ? $sensorData['temperature'] . '°C' : 'Data tidak tersedia'; ?></p>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-500">Kelembaban Terkini</p>
                <p class="text-xl font-semibold"><?= isset($sensorData['humidity']) ? $sensorData['humidity'] . '%' : 'Data tidak tersedia'; ?></p>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg shadow-sm">
                <p class="text-sm text-gray-500">Kelembaban Tanah Terkini</p>
                <p class="text-xl font-semibold"><?= isset($sensorData['soil_moisture']) ? $sensorData['soil_moisture'] . '%' : 'Data tidak tersedia'; ?></p>
            </div>
        </div>

        <h3 class="text-xl font-semibold mb-4">Tabel Data Sensor</h3>

        <!-- Tabel Data Sensor -->
        <div class="overflow-x-auto bg-white rounded-lg shadow-sm">
            <table class="min-w-full table-auto">
                <thead class="bg-[#ff7f00] text-white">
                    <tr>
                        <th class="py-2 px-4 text-left">ID</th>
                        <th class="py-2 px-4 text-left">Suhu (°C)</th>
                        <th class="py-2 px-4 text-left">Kelembaban (%)</th>
                        <th class="py-2 px-4 text-left">Kelembaban Tanah (%)</th>
                        <th class="py-2 px-4 text-left">Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Query untuk mengambil data semua sensor
                    $sql = "SELECT id, temperature, humidity, soil_moisture, timestamp FROM sensor_data ORDER BY timestamp DESC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr class='bg-gray-50'>
                                    <td class='py-2 px-4'>{$row['id']}</td>
                                    <td class='py-2 px-4'>{$row['temperature']}</td>
                                    <td class='py-2 px-4'>{$row['humidity']}</td>
                                    <td class='py-2 px-4'>{$row['soil_moisture']}</td>
                                    <td class='py-2 px-4'>{$row['timestamp']}</td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='py-2 px-4 text-center'>No data found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php
    // Menutup koneksi database
    $conn->close();
    ?>

</body>
</html>
