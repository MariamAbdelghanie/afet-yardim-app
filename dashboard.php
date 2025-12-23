<?php
// Türkçe Açıklama: Dashboard sayfası — tüm kullanıcılar giriş yaptıktan sonra görebilir
require_once 'config/auth.php'; // session burada başlatılır

// Eğer kullanıcı giriş yapmamışsa login sayfasına yönlendir
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Veritabanı bağlantıları ve yardımcı sınıflar
require_once 'config/db.php';
require_once 'lib/Helpers.php';
require_once 'lib/Priority.php';
require_once 'lib/Geo.php';

include 'templates/header.php';

// Türkçe Açıklama: Kullanıcı şehir seçimini GET parametresinden alıyoruz
$sehir = $_GET['sehir'] ?? '';
?>

<div class="container">
  <h1 class="title"><i class="fa-solid fa-gauge-high"></i> Yönetici Paneli</h1>

  <!-- Şehir seçimi -->
  <form method="GET" class="card">
    <label>Şehir Seçiniz</label>
    <select name="sehir" onchange="this.form.submit()">
      <option value="">-- Seçiniz --</option>
      <?php
      // Türkçe Açıklama: Veritabanından şehirleri çekiyoruz
      $cities = $pdo->query("SELECT DISTINCT sehir FROM tedarikciler ORDER BY sehir")->fetchAll();
      foreach($cities as $c){
        $sel = ($c['sehir']===$sehir) ? 'selected' : '';
        echo "<option value='{$c['sehir']}' $sel>{$c['sehir']}</option>";
      }
      ?>
    </select>
  </form>

  <?php if($sehir): ?>
  <!-- Talepler -->
  <div class="card">
    <h3><i class="fa-solid fa-list"></i> Talepler (<?php echo htmlspecialchars($sehir); ?>)</h3>
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Mahalle</th><th>Sokak</th><th>Kişi</th><th>Aciliyet</th><th>Durum</th><th>Öncelik</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmt = $pdo->prepare("SELECT * FROM talepler WHERE sehir=? ORDER BY created_at DESC");
        $stmt->execute([$sehir]);
        $rows = $stmt->fetchAll();
        $heatmapData = [];
        $talepScores = [];
        foreach($rows as $r){
          $lat = $r['latitude'];
          $lng = $r['longitude'];
          $score = Priority::calculate(
            intval($r['kisi_sayisi']),
            $r['aciliyet_seviyesi'],
            [],
            $lat,
            $lng,
            $pdo
          );
          echo "<tr>
            <td>{$r['id']}</td><td>{$r['mahalle']}</td><td>{$r['sokak']}</td>
            <td>{$r['kisi_sayisi']}</td><td>{$r['aciliyet_seviyesi']}</td>
            <td>{$r['durum']}</td><td><strong>{$score}</strong></td>
          </tr>";
          if($lat && $lng && Geo::isValid($lat,$lng)){
            if(Geo::isInsideTurkey($lat,$lng)){
              $heatmapData[] = [$lat,$lng,$score];
              $talepScores[$r['id']] = $score;
            } else {
              list($lat,$lng) = Geo::clampTurkey($lat,$lng);
              $heatmapData[] = [$lat,$lng,$score];
              $talepScores[$r['id']] = $score;
            }
          }
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Depolar ve Stoklar -->
  <div class="card">
    <h3><i class="fa-solid fa-warehouse"></i> Depolar ve Stoklar (<?php echo htmlspecialchars($sehir); ?>)</h3>
    <table>
      <thead><tr><th>Depo</th><th>Malzeme</th><th>Miktar</th></tr></thead>
      <tbody>
        <?php
        $stmt = $pdo->prepare("SELECT d.id, d.ad AS depo, d.latitude, d.longitude, m.ad AS malzeme, s.miktar 
                               FROM stoklar s 
                               JOIN tedarikciler d ON s.tedarikci_id=d.id 
                               JOIN malzemeler m ON s.malzeme_id=m.id 
                               WHERE d.sehir=?");
        $stmt->execute([$sehir]);
        $depots = [];
        foreach($stmt as $row){
          echo "<tr><td>{$row['depo']}</td><td>{$row['malzeme']}</td><td>{$row['miktar']}</td></tr>";
          if(!isset($depots[$row['id']])){
            $depots[$row['id']] = [
              'ad'=>$row['depo'],
              'lat'=>$row['latitude'],
              'lng'=>$row['longitude'],
              'stoklar'=>[]
            ];
          }
          $depots[$row['id']]['stoklar'][] = $row['malzeme'].": ".$row['miktar'];
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Teslimatlar -->
  <div class="card">
    <h3><i class="fa-solid fa-truck"></i> Teslimatlar (<?php echo htmlspecialchars($sehir); ?>)</h3>
    <table>
      <thead><tr><th>ID</th><th>Talep</th><th>Durum</th><th>Taşıma</th></tr></thead>
      <tbody>
        <?php
        $stmt = $pdo->prepare("
          SELECT t.id, t.talep_id, t.durum, t.tasima_modu AS tasima_turu, t.tedarikci_id,
                 tp.latitude AS talep_lat, tp.longitude AS talep_lng,
                 d.latitude AS depo_lat, d.longitude AS depo_lng, d.ad AS depo_ad
          FROM teslimatlar t
          JOIN talepler tp ON t.talep_id = tp.id
          JOIN tedarikciler d ON t.tedarikci_id = d.id
          WHERE tp.sehir = ?
          ORDER BY t.teslimat_tarihi DESC
        ");
        $stmt->execute([$sehir]);
        $routes = [];
        foreach($stmt as $row){
          echo "<tr>
            <td>{$row['id']}</td><td>{$row['talep_id']}</td>
            <td>{$row['durum']}</td><td>{$row['tasima_turu']}</td>
          </tr>";
          if($row['talep_lat'] && $row['talep_lng']){
            list($row['talep_lat'],$row['talep_lng']) = Geo::clampTurkey($row['talep_lat'],$row['talep_lng']);
          }
          if($row['depo_lat'] && $row['depo_lng']){
            list($row['depo_lat'],$row['depo_lng']) = Geo::clampTurkey($row['depo_lat'],$row['depo_lng']);
          }
          $routes[] = [
            'talep_id'=>$row['talep_id'],
            'durum'=>$row['durum'],
            'tasima'=>$row['tasima_turu'],
            'depo_ad'=>$row['depo_ad'],
            'talep_lat'=>$row['talep_lat'],
            'talep_lng'=>$row['talep_lng'],
            'depo_lat'=>$row['depo_lat'],
            'depo_lng'=>$row['depo_lng'],
            'score'=>$talepScores[$row['talep_id']] ?? 0
          ];
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Harita -->
  <div class="card">
    <h3><i class="fa-solid fa-map"></i> Harita Görselleştirme</h3>
    <div id="map" style="height:500px;"></div>
  </div>

  <script>
    var map = L.map('map').setView([41.63, 32.34], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Talepler heatmap
    var heatData = <?php echo json_encode($heatmapData); ?>;
    L.heatLayer(heatData, {
      radius: 25,
      blur: 15,
      maxZoom: 17,
      gradient: {0.2:'blue',0.4:'lime',0.6:'orange',0.8:'red'}
    }).addTo(map);

    // Depo ikonları
        // Depo ikonları
    var depots = <?php echo json_encode(array_values($depots)); ?>;
    depots.forEach(function(d){
      if(d.lat && d.lng){
        var stokHtml = "<ul>";
        d.stoklar.forEach(function(s){ stokHtml += "<li>"+s+"</li>"; });
        stokHtml += "</ul>";
        L.marker([d.lat,d.lng], {
          icon: L.icon({
            iconUrl: 'assets/icons/warehouse.png',
            iconSize: [32,32],
            iconAnchor: [16,32],
            popupAnchor: [0,-32]
          })
        }).addTo(map).bindPopup("<b>Depo:</b> "+d.ad+"<br><b>Stoklar:</b>"+stokHtml);
      }
    });

    // Rotalar (teslimatlar)
    var routes = <?php echo json_encode($routes); ?>;
    routes.forEach(function(r){
      if(r.talep_lat && r.talep_lng && r.depo_lat && r.depo_lng){
        var color = 'gray';
        if(r.durum === 'Planlandı') color = 'blue';
        else if(r.durum === 'Başlatıldı') color = 'orange';
        else if(r.durum === 'Teslim Edildi') color = 'green';
        else if(r.durum === 'Planlanamadı') color = 'gray';

        // Depo → Talep çizgisi
        L.polyline([
          [r.depo_lat, r.depo_lng],
          [r.talep_lat, r.talep_lng]
        ], {
          color: color,
          weight: 4,
          opacity: 0.8,
          dashArray: '6,4'
        }).addTo(map);

        // Talep noktası
        L.circleMarker([r.talep_lat, r.talep_lng], {
          radius: 8,
          color: color,
          fillColor: color,
          fillOpacity: 0.7
        }).addTo(map).bindPopup(
          "<b>Talep #" + r.talep_id + "</b><br>" +
          "<b>Durum:</b> " + r.durum + "<br>" +
          "<b>Taşıma:</b> " + r.tasima + "<br>" +
          "<b>Depo:</b> " + r.depo_ad + "<br>" +
          "<b>Öncelik:</b> " + r.score
        );
      }
    });
  </script>

  <?php endif; ?>
</div>

<?php include 'templates/footer.php'; ?>


















