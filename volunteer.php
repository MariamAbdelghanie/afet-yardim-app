// Gönüllü 
<?php
require_once 'config/auth.php';
requireRole(['Gonullu','Admin']);
require_once 'config/db.php';
include 'templates/header.php';

// Gönüllü'nün görevli olduğu şehirleri çekiyoruz
$sehirler = $pdo->query("SELECT DISTINCT sehir FROM talepler ORDER BY sehir")->fetchAll();
?>

<div class="container">
  <h1 class="title"><i class="fa-solid fa-people-carry-box"></i> Gönüllü Paneli</h1>

  <!-- Şehir seçimi -->
  <form method="GET" class="card">
    <label>Şehir Seçiniz</label>
    <select name="sehir" onchange="this.form.submit()">
      <option value="">-- Seçiniz --</option>
      <?php
      foreach($sehirler as $s){
        $sel = ($_GET['sehir'] ?? '') === $s['sehir'] ? 'selected' : '';
        echo "<option value='{$s['sehir']}' $sel>{$s['sehir']}</option>";
      }
      ?>
    </select>
  </form>
//Gönüllü devam
  <?php if(!empty($_GET['sehir'])): ?>
  <!-- Görevler tablosu -->
  <div class="card">
    <h3>Görevlerim (<?php echo htmlspecialchars($_GET['sehir']); ?>)</h3>
    <table class="table">
      <thead><tr><th>ID</th><th>Mahalle</th><th>Sokak</th><th>Kişi</th><th>Durum</th></tr></thead>
      <tbody>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM talepler WHERE sehir=? ORDER BY created_at DESC");
        $stmt->execute([$_GET['sehir']]);
        foreach($stmt as $r){
          echo "<tr>
            <td>{$r['id']}</td><td>{$r['mahalle']}</td><td>{$r['sokak']}</td>
            <td>{$r['kisi_sayisi']}</td><td>{$r['durum']}</td>
          </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>



