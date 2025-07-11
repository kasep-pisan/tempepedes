<?php
require_once 'includes/config.php';

$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $conn->query("DELETE FROM menus");
    $successMessage = "Semua menu berhasil dihapus!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO menus (name, description, price, active, position) VALUES (?, ?, ?, 1, 0)");
    $stmt->bind_param("ssd", $name, $description, $price);
    $stmt->execute();

    $successMessage = "Menu berhasil ditambahkan!";
    header("Location: menu.php?success=1");
    exit();
}

// Ambil semua data menu
$menus = $conn->query("SELECT * FROM menus ORDER BY id DESC");
?>
<?php if (isset($_GET['success'])): ?>
  <div class="alert alert-success">Menu berhasil ditambahkan!</div>
<?php endif; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Menu</title>
    <style>
        body {
            font-family: Arial;
            background-color: #f2f2f2;
        }
        .container {
            width: 400px;
            margin: 40px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        input, textarea, button {
            width: 100%;
            margin: 8px 0;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            font-weight: bold;
        }
       
        .success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #bbb;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background: #f5f5f5;
        }

         .btn-hapus {
            background-color: red;
            color: white;
            padding: 4px 10px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Menu</h2>

        <?php if ($successMessage): ?>
            <div class="success"> <?= $successMessage ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Nama Menu:</label>
            <input type="text" name="name" required>

            <label>Deskripsi:</label>
            <textarea name="description" required></textarea>

            <label>Harga (Rp):</label>
            <input type="number" name="price" required placeholder="contoh:5000">

            <button type="submit">Simpan Menu</button>
            <a href="../index.php" class="nav-link">kembali</a>



        </form>
        <h3 style="margin-top:30px;">Daftar Menu:</h3>
<table class="table table-striped table-bordered mt-3">
  <thead class="table-dark">
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Deskripsi</th>
      <th>Harga (Rp)</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php if ($menus->num_rows > 0): ?>
      <?php $no = 1; ?>
      <?php while ($menu = $menus->fetch_assoc()): ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= htmlspecialchars($menu['name']) ?></td>
          <td><?= htmlspecialchars($menu['description']) ?></td>
          <td><?= number_format($menu['price'], 0, ',', '.') ?></td>
          <td>
            <form method="POST" action="hapus-menu.php" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
              <input type="hidden" name="id" value="<?= $menu['id'] ?>">
              <button type="submit" class="btn-hapus">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    <?php else: ?>
      <tr><td colspan="5" class="text-center">Belum ada menu.</td></tr>
    <?php endif; ?>
  </tbody>
</table>
    </div>
</body>
</html>
