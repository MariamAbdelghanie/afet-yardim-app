<?php
// Tedarikçipaneli — giriş gerekli
require_once 'config/auth.php';
requireRole(['Tedarikci','Admin']); // sadece tedarikçi ve admin görebilir
include 'templates/header.php';
?>

<div class="container">
  <h1 class="title"><i class="fa-solid fa-warehouse"></i> Tedarikçi Paneli</h1>
  
  <div class="card">
    <h3>Stok Güncelle</h3>
    <form id="supplierForm">
      <div class="grid-3">
        <div><label>Malzeme</label><select name="malzeme_id" id="supMaterial"></select></div>
        <div><label>Miktar</label><input type="number" name="miktar" min="0" value="0"></div>
        <div><label>Hız (dk)</label><input type="number" name="lead_time" min="0" value="0"></div>
      </div>
      <button class="btn primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Kaydet</button>
    </form>
  </div>

  <div class="card" style="margin-top:12px;">
    <h3>Talep Atamaları</h3>
    <table class="table" id="supplierTasks"></table>
  </div>
</div>

<?php include 'templates/footer.php'; ?>



