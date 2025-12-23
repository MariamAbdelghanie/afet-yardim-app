<?php
// Türkçe Açıklama: Sürücü paneli — sadece giriş yapan sürücü ve admin görebilir
require_once 'config/auth.php';
requireRole(['Surucu','Admin']); // sadece sürücü ve admin erişebilir
require_once 'config/db.php';
include 'templates/header.php';
// Oturumdan sürücü ID'sini alıyoruz
$driverId = $_SESSION['user_id'];
?>
<div class="container">
  <!-- Sayfa başlığı -->
  <h1 class="title"><i class="fa-solid fa-truck"></i> Sürücü Paneli</h1>
  <!-- Sevkiyatlar tablosu -->
  <div class="card">
    <h3>Sevkiyatlarım</h3>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th><th>Talep</th><th>Durum</th><th>Taşıma</th><th>Şehir</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Veritabanından sürücüye ait teslimatları çekiyoruz
        $stmt = $pdo->prepare("
          SELECT t.id, t.talep_id, t.durum, t.tasima_modu, tp.sehir
          FROM teslimatlar t
          JOIN talepler tp ON t.talep_id = tp.id
          WHERE t.surucu_id = ?
          ORDER BY t.teslimat_tarihi DESC
        ");
        $stmt->execute([$driverId]);
        $rows = $stmt->fetchAll();

        // Eğer teslimat varsa tabloya yazdırıyoruz
        if($rows){
          foreach($rows as $r){
            echo "<tr>
              <td>{$r['id']}</td>
              <td>{$r['talep_id']}</td>
              <td>{$r['durum']}</td>
              <td>{$r['tasima_modu']}</td>
              <td>{$r['sehir']}</td>
            </tr>";
          }
        } else {
          echo "<tr><td colspan='5'>Henüz atanmış sevkiyatınız yok.</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
</div>

<?php include 'templates/footer.php'; ?>





