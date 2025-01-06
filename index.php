<?php
//VARIABEL KECEPATAN PUTARAN KIPAS 
// KECEPATAN LAMBAT
function kecepatanLambat($kecepatan)
{
    if ($kecepatan <= 1000) return 1;
    if ($kecepatan >= 5000) return 0;
    return (5000 - $kecepatan) / (5000 - 1000);
}
// KECEPATAN CEPAT
function kecepatanCepat($kecepatan)
{
    if ($kecepatan <= 1000) return 0;
    if ($kecepatan >= 5000) return 1;
    return ($kecepatan - 1000) / (5000 - 1000);
}

//VARIABEL SUHU RUANGAN
//SUHU RENDAH
function suhuRendah($suhu)
{
    if ($suhu <= 100) return 1;
    if ($suhu >= 600) return 0;
    return (600 - $suhu) / (600 - 100);
}
//SUHU TINGGI
function suhuTinggi($suhu)
{
    if ($suhu <= 100) return 0;
    if ($suhu >= 600) return 1;
    return ($suhu - 100) / (600 - 100);
}

// Fungsi untuk menghitung frekuensi berdasarkan rules
function hitungFrekuensi($alpha)
{
    return 2000 + ($alpha * (7000 - 2000));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kecepatan = $_POST['kecepatan'];
    $suhu = $_POST['suhu'];

    // Hitung derajat keanggotaan
    $kecepatanLambat = kecepatanLambat($kecepatan);
    $kecepatanCepat = kecepatanCepat($kecepatan);
    $suhuRendah = suhuRendah($suhu);
    $suhuTinggi = suhuTinggi($suhu);

    // Terapkan aturan
    $alpha1 = min($kecepatanLambat, $suhuTinggi); // R1: IF kecepatan LAMBAT dan suhu TINGGI THEN frekuensi KECIL
    $alpha2 = min($kecepatanLambat, $suhuRendah);  // R2: IF kecepatan LAMBAT dan suhu RENDAH THEN frekuensi KECIL
    $alpha3 = min($kecepatanCepat, $suhuTinggi);   // R3: IF kecepatan CEPAT dan suhu TINGGI THEN frekuensi BESAR
    $alpha4 = min($kecepatanCepat, $suhuRendah);   // R4: IF kecepatan CEPAT dan suhu RENDAH THEN frekuensi BESAR

    // Hitung frekuensi untuk setiap aturan
    $z1 = hitungFrekuensi(0); // KECIL untuk R1
    $z2 = hitungFrekuensi(0); // KECIL untuk R2
    $z3 = hitungFrekuensi(1); // BESAR untuk R3
    $z4 = hitungFrekuensi(1); // BESAR untuk R4

    // Hitung output akhir menggunakan metode Tsukamoto
    $pembilang = ($alpha1 * $z1) + ($alpha2 * $z2) + ($alpha3 * $z3) + ($alpha4 * $z4);
    $penyebut = $alpha1 + $alpha2 + $alpha3 + $alpha4;

    $frekuensiAkhir = $penyebut != 0 ? $pembilang / $penyebut : 0;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Sistem Kontrol Kipas Angin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="number"] {
            width: 200px;
            padding: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        b {
            color: red;
        }

        .hasil {
            margin-top: 20px;
            padding: 15px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <h1>Aplikasi Menghitung Sistem Kontrol Kipas Angin</h1>
    <form method="post">
        <div class="form-group">
            <label>Kecepatan Putar Kipas (1000-5000 rpm):</label>
            <input type="number" name="kecepatan" min="1000" max="5000" required
                value="<?php echo isset($_POST['kecepatan']) ? $_POST['kecepatan'] : ''; ?>">
        </div>

        <div class="form-group">
            <label>Suhu Ruangan (100-600 Kelvin):</label>
            <input type="number" name="suhu" min="100" max="600" required
                value="<?php echo isset($_POST['suhu']) ? $_POST['suhu'] : ''; ?>">
        </div>

        <button type="submit">Hitung</button>
    </form>

    <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <div class="hasil">
        <b>Hasil Perhitungan:</b>
            <b>
                <p>Frekuensi yang Dihasilkan: <?php echo round($frekuensiAkhir, 2); ?> rpm</p>
            </b>
            <hr>
            <h2>Nilai:</h2>
            <p>Kecepatan Input: <?php echo $kecepatan; ?> rpm</p>
            <p>Suhu Input: <?php echo $suhu; ?> K</p>

            <br>
            <h3>Derajat Keanggotaan:</h3>
            <p>Kecepatan Lambat: <?php echo round($kecepatanLambat, 4); ?></p>
            <p>Kecepatan Cepat: <?php echo round($kecepatanCepat, 4); ?></p>
            <p>Suhu Rendah: <?php echo round($suhuRendah, 4); ?></p>
            <p>Suhu Tinggi: <?php echo round($suhuTinggi, 4); ?></p>
            <br>
            <h3>Rules:</h3>
            <p>R1 (Kecepatan Lambat & Suhu Tinggi): <?php echo round($alpha1, 4); ?></p>
            <p>R2 (Kecepatan Lambat & Suhu Rendah): <?php echo round($alpha2, 4); ?></p>
            <p>R3 (Kecepatan Cepat & Suhu Tinggi): <?php echo round($alpha3, 4); ?></p>
            <p>R4 (Kecepatan Cepat & Suhu Rendah): <?php echo round($alpha4, 4); ?></p>
        </div>
    <?php endif; ?>
</body>

</html>