<?php
// Türkçe Açıklama: Talep takibi — talep ID ile konumu ve teslimatları gösterir
include 'templates/header.php'; ?>
<div class="container">
  <h1 class="title"><i class="fa-solid fa-location-dot"></i> Talep Takibi</h1>
  <div class="card" style="max-width:640px;">
    <form id="trackForm">
      <label>Talep Numarası</label><input type="number" id="trackId" required>
      <button class="btn primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Sorgula</button>
    </form>
    <div id="trackResult" style="margin-top:12px;"></div>
  </div>

  <div class="card" style="margin-top:12px;">
    <h3><i class="fa-solid fa-map-location-dot"></i> Rota ve Depolar</h3>
    <div id="trackMap"></div>
  </div>
</div>
<?php include 'templates/footer.php'; ?>


