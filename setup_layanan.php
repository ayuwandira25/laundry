<?php
require_once __DIR__ . '/config.php';

$message = '';
$error = '';

if ($_GET['reset'] === '1') {
    // Drop dan recreate tabel layanan
    $queries = [
        "DROP TABLE IF EXISTS pesanan",
        "DROP TABLE IF EXISTS layanan",
        "CREATE TABLE IF NOT EXISTS layanan (
            id INT AUTO_INCREMENT PRIMARY KEY,
            kategori ENUM('kiloan', 'satuan') NOT NULL,
            jenis_layanan VARCHAR(255) NOT NULL COMMENT 'Cuci Kering, Cuci Setrika, Setrika Saja, dll',
            harga_reguler DECIMAL(10,2) NOT NULL,
            harga_express DECIMAL(10,2) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        "CREATE TABLE IF NOT EXISTS pesanan (
            id INT AUTO_INCREMENT PRIMARY KEY,
            pelanggan_id INT,
            layanan_id INT,
            jumlah DECIMAL(10,2),
            jenis_item VARCHAR(255),
            total_harga DECIMAL(10,2),
            tanggal_masuk TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            tanggal_selesai DATE,
            status VARCHAR(50) DEFAULT 'pending',
            FOREIGN KEY (pelanggan_id) REFERENCES pelanggan(id) ON DELETE SET NULL,
            FOREIGN KEY (layanan_id) REFERENCES layanan(id) ON DELETE SET NULL
        )"
    ];

    $success = true;
    foreach ($queries as $query) {
        if (!mysqli_query($db, $query)) {
            $error = "Error: " . mysqli_error($db);
            $success = false;
            break;
        }
    }

    if ($success) {
        $message = "✅ Tabel berhasil direset! Struktur database sudah diperbarui.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Layanan - Kyka Laundry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .message {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px 5px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        .button-primary {
            background-color: #dc3545;
            color: white;
        }
        .button-primary:hover {
            background-color: #c82333;
        }
        .button-secondary {
            background-color: #6c757d;
            color: white;
        }
        .button-secondary:hover {
            background-color: #545b62;
        }
        .info {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #0c5460;
        }
        .button-group {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ Setup Database Layanan</h1>

        <?php if ($message): ?>
            <div class="message success"><?php echo $message; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="info">
            <strong>ℹ️ Informasi:</strong><br>
            Jika Anda mendapatkan error saat menambah layanan, gunakan tombol di bawah untuk mereset tabel database dengan struktur yang benar.
        </div>

        <div class="button-group">
            <a href="?reset=1" class="button button-primary" onclick="return confirm('Perhatian: Ini akan menghapus semua data layanan dan pesanan yang ada. Lanjutkan?')">
                🔄 Reset Tabel Database
            </a>
            <a href="dashboard.php" class="button button-secondary">
                ← Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>
