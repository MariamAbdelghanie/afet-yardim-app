<?php
// Afetzede talep oluşturma sayfası
include 'templates/header.php'; ?>
<div class="container">
  <h1 class="title"><i class="fa-solid fa-hand-holding-medical"></i> Afet Yardım Talebi</h1>

  <form id="talepForm" method="POST" action="api/request_create.php" class="card">
    <!-- Şehir / Mahalle / Adres -->
    <div class="grid-3">
      <div><label>Şehir</label><select name="sehir" id="sehirSelect" required></select></div>
      <div><label>Mahalle</label><select name="mahalle" id="mahalleSelect" required></select></div>
      <div><label>Sokak / Adres</label><input type="text" name="sokak" id="adresInput" placeholder="Adres yazınız" required></div>
    </div>

    <!-- Konum seçimi -->
    <div class="card" style="margin-top:8px;">
      <h3 style="margin-bottom:8px;"><i class="fa-solid fa-map-location-dot"></i> Konum</h3>
      <div id="map"></div>
      <div class="grid-3" style="margin-top:8px;">
        <div><label>Latitude</label><input type="text" name="latitude" id="latField" /></div>
        <div><label>Longitude</label><input type="text" name="longitude" id="lngField" /></div>
        <div><label>GPS Metin</label><input type="text" id="coordText" readonly></div>
      </div>
      <button type="button" class="btn outline" id="gpsBtn"><i class="fa-solid fa-location-crosshairs"></i> GPS ile Konum</button>
    </div>

    <!-- Kişi sayısı / Aciliyet / Açıklama -->
    <div class="grid-3">
      <div><label>Kişi Sayısı</label><input type="number" name="kisi_sayisi" min="1" value="1" required></div>
      <div><label>Aciliyet</label>
        <select name="aciliyet_seviyesi">
          <option value="Normal">Normal</option>
          <option value="Acil">Acil</option>
          <option value="CokAcil">Çok Acil</option>
        </select>
      </div>
      <div><label>Açıklama</label><textarea name="aciklama" rows="3"></textarea></div>
    </div>

    <!-- İhtiyaçlar + miktar -->
    <div class="card">
      <h3><i class="fa-solid fa-boxes-packing"></i> İhtiyaçlar</h3>
      <div class="grid-3">
        <div>
          <label>Gıda</label>
          <select name="needs_list[gida]">
            <option value="">Seçiniz</option>
            <option value="Ekmek">Ekmek</option>
            <option value="Konserve">Konserve</option>
            <option value="Bakliyat">Bakliyat</option>
          </select>
          <input type="number" name="needs_qty[gida]" min="1" value="1" placeholder="Miktar">
        </div>
        <div>
          <label>Su</label>
          <select name="needs_list[su]">
            <option value="">Seçiniz</option>
            <option value="Şişe Su">Şişe Su</option>
            <option value="Damacana">Damacana</option>
          </select>
          <input type="number" name="needs_qty[su]" min="1" value="1" placeholder="Miktar">
        </div>
        <div>
          <label>İlaç</label>
          <select name="needs_list[ilac]">
            <option value="">Seçiniz</option>
            <option value="Ağrı Kesici">Ağrı Kesici</option>
            <option value="Antiseptik">Antiseptik</option>
          </select>
          <input type="number" name="needs_qty[ilac]" min="1" value="1" placeholder="Miktar">
        </div>
      </div>
      <div class="grid-2">
        <div>
          <label>Hijyen</label>
          <select name="needs_list[hijyen]">
            <option value="">Seçiniz</option>
            <option value="Sabun">Sabun</option>
            <option value="Dezenfektan">Dezenfektan</option>
          </select>
          <input type="number" name="needs_qty[hijyen]" min="1" value="1" placeholder="Miktar">
        </div>
        <div>
          <label>Barınma</label>
          <select name="needs_list[barinma]">
            <option value="">Seçiniz</option>
            <option value="Çadır">Çadır</option>
            <option value="Uyku Tulumu">Uyku Tulumu</option>
          </select>
          <input type="number" name="needs_qty[barinma]" min="1" value="1" placeholder="Miktar">
        </div>
      </div>
      <label>Diğer İhtiyaçlar</label>
      <input type="text" name="extra_need" placeholder="Başka bir şey yazınız...">
    </div>

    <!-- İletişim -->
    <div class="card">
      <h3><i class="fa-solid fa-id-card"></i> İletişim</h3>
      <div class="grid-2">
        <div><label>TC Kimlik</label><input type="text" name="ikamet" required></div>
        <div><label>Telefon</label><input type="tel" name="telefon" required></div>
      </div>
    </div>

    <button type="submit" class="btn primary" style="margin-top:12px;"><i class="fa-solid fa-paper-plane"></i> Talep Gönder</button>
  </form>

  <div id="result" class="toast" style="display:none;"></div>
</div>
<?php include 'templates/footer.php'; ?>





