// Türkçe Açıklama: Durum etiketleri için CSS sınıfları
function statusClass(d){
  switch((d||'').toLowerCase()){
    case 'planlandi':return'plan';
    case 'rota':return'route';
    case 'en route':return'run';
    case 'delivered':return'done';
    case 'failed': case 'planlanamadi':return'fail';
    default:return'plan';
  }
}

document.addEventListener('DOMContentLoaded',()=>{
  initAfetzede(); initDashboard(); initTrack(); initVolunteer(); initSupplier(); initDriver();
});

function initAfetzede(){
  const sehirSelect=document.getElementById('sehirSelect'); const mapEl=document.getElementById('map'); if(!sehirSelect||!mapEl) return;

  // Türkçe Açıklama: Türkiye genel örnek şehir listesi (API ile de doldurulabilir)
  const cities=['Bartın','Karabük','Kastamonu','Zonguldak','Ankara','İstanbul','İzmir','Trabzon','Diyarbakır','Konya','Bursa','Adana','Gaziantep','Antalya','Samsun'];
  cities.forEach(c=>{ const o=document.createElement('option'); o.value=c; o.textContent=c; sehirSelect.appendChild(o); });
  sehirSelect.value='Bartın';

  // Türkçe Açıklama: Mahalle örnekleri — basit statik veri (DB veya API ile genişletilebilir)
  const mahalleSelect=document.getElementById('mahalleSelect');
  const mahalleData={
    'Bartın':['Orduyeri','Kırtepe','Kemerköprü','Gölbucağı','Yıldız'],
    'Karabük':['Cumhuriyet','Soğuksu','Fevzi Çakmak'],
    'Ankara':['Çankaya','Keçiören','Mamak'],
    'İstanbul':['Kadıköy','Üsküdar','Beşiktaş'],
    'İzmir':['Konak','Karşıyaka','Bornova']
  };
  const loadMahalle=s=>{ mahalleSelect.innerHTML=''; (mahalleData[s]||['Merkez']).forEach(m=>{ const o=document.createElement('option'); o.value=m; o.textContent=m; mahalleSelect.appendChild(o); }); };
  loadMahalle(sehirSelect.value); sehirSelect.addEventListener('change',e=>loadMahalle(e.target.value));

  const adresInput=document.getElementById('adresInput');
  const map=L.map('map').setView([39.0,35.0],6); // Türkiye merkezi
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);
  let marker=null;
  const latField=document.getElementById('latField'); const lngField=document.getElementById('lngField');

  // Türkçe Açıklama: Koordinatları Türkiye sınırlarına kısıtla
  function clampTurkey(lat,lng){
    lat=Math.max(36.0,Math.min(42.2,lat)); lng=Math.max(26.0,Math.min(45.0,lng)); return [lat,lng];
  }

  map.on('click',(e)=>{
    let {lat,lng}=e.latlng; [lat,lng]=clampTurkey(lat,lng);
    if(marker){ marker.setLatLng([lat,lng]); } else { marker=L.marker([lat,lng]).addTo(map); }
    latField.value=lat.toFixed(6); lngField.value=lng.toFixed(6);
    // Türkçe Açıklama: GPS ile seçilen koordinatı adres alanına yaz
    adresInput.value=`Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)}`;
    // Türkçe Açıklama: Ek metin alanı (salt okunur)
    const coordText=document.getElementById('coordText');
    if(coordText) coordText.value=`${lat.toFixed(6)}, ${lng.toFixed(6)}`;
  });

  document.getElementById('gpsBtn')?.addEventListener('click',()=>{
    if(navigator.geolocation){
      navigator.geolocation.getCurrentPosition(pos=>{
        let {latitude,longitude}=pos.coords; [latitude,longitude]=clampTurkey(latitude,longitude);
        map.setView([latitude,longitude],14);
        if(marker){ marker.setLatLng([latitude,longitude]); } else { marker=L.marker([latitude,longitude]).addTo(map); }
        latField.value=latitude.toFixed(6); lngField.value=longitude.toFixed(6);
        adresInput.value=`Koordinat: ${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
        const coordText=document.getElementById('coordText');
        if(coordText) coordText.value=`${latitude.toFixed(6)}, ${longitude.toFixed(6)}`;
      });
    }
  });

  const form=document.getElementById('talepForm'); const result=document.getElementById('result');
  form.addEventListener('submit',async(e)=>{
    e.preventDefault();
    const btn=form.querySelector('button[type="submit"]'); btn.disabled=true; // Türkçe Açıklama: Çift gönderimi engelle
    try{
      const fd=new FormData(form);
      const res=await fetch(form.action,{method:'POST',body:fd}); const data=await res.json();
      result.style.display='block';
      result.innerHTML=data.ok?`Talep #${data.talep_id} kaydedildi. <a class="btn outline" href="track.php?talep_id=${data.talep_id}">Talebimi Takip Et</a>`:(data.msg||'Hata oluştu');
      if(data.ok){
        // Türkçe Açıklama: Tedarik ve taşıma planını bir kez tetikle
        await fetch('api/sourcing_allocate.php',{method:'POST',body:new URLSearchParams({talep_id:data.talep_id})});
        await fetch('api/transport_plan.php',{method:'POST',body:new URLSearchParams({talep_id:data.talep_id})});
      }
    } finally { btn.disabled=false; }
  });
}

async function initDashboard(){
  const el=document.getElementById('dashMap'); if(!el) return;

  // Türkçe Açıklama: Şehirleri DB'den çek
  const citySel=document.getElementById('cityFilter');
  const cRes=await fetch('api/cities.php'); const cData=await cRes.json();
  (cData.cities||[]).forEach(c=>{ const o=document.createElement('option'); o.value=c; o.textContent=c; citySel.appendChild(o); });
  citySel.value=(cData.cities||[])[0]||'Bartın';

  async function load(city){
    const res=await fetch(`api/city_overview.php?sehir=${encodeURIComponent(city)}`); const data=await res.json();

    // Türkçe Açıklama: Yoğunluk tablosu
    document.getElementById('clusterSummary').innerHTML=`<tr><th>Şehir</th><th>Mahalle</th><th>Talep</th><th>Kişi</th></tr>`+(data.agg||[]).map(a=>`<tr><td>${a.sehir}</td><td>${a.mahalle}</td><td>${a.talep_sayisi}</td><td>${a.toplam_kisi}</td></tr>`).join('');

    // Türkçe Açıklama: Depo ve stok tablosu
    document.getElementById('stockSummary').innerHTML=`<tr><th>Depo</th><th>Şehir</th><th>Yerel/Bölgesel</th><th>Kapasite</th><th>Stok</th></tr>`+(data.depolar||[]).map(d=>`<tr><td>${d.ad}</td><td>${d.sehir}</td><td>${d.yerel_mi?'Yerel':'Bölgesel'}</td><td>${d.kapasite||'-'}</td><td>${(d.stok||[]).map(s=>`${s.malzeme}: ${s.toplam_miktar}`).join(', ')}</td></tr>`).join('');

    // Türkçe Açıklama: Teslimatlar
    document.getElementById('deliveryList').innerHTML=`<tr><th>ID</th><th>Talep</th><th>Şehir/Mahalle</th><th>Taşıma</th><th>Durum</th></tr>`+(data.teslimatlar||[]).map(t=>`<tr><td>${t.id}</td><td>#${t.talep_id}</td><td>${t.sehir} / ${t.mahalle}</td><td>${t.tasima_modu}</td><td><span class="status ${statusClass(t.durum)}">${t.durum}</span></td></tr>`).join('');

    // Türkçe Açıklama: Harita — Türkiye görünümü
    const map=L.map('dashMap'); map.setView([39.0,35.0],6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);
    const depotIcon=L.divIcon({html:'<i class="fa-solid fa-warehouse" style="color:#4f8cff;font-size:22px;"></i>'});

    (data.depolar||[]).forEach(d=>{ if(d.latitude&&d.longitude){ L.marker([d.latitude,d.longitude],{icon:depotIcon}).addTo(map).bindPopup(`<b>${d.ad}</b><br>${d.sehir}`); } });
    (data.rotalar||[]).forEach(r=>{ if(r.baslangic_lat&&r.bitis_lat){ L.polyline([[r.baslangic_lat,r.baslangic_lng],[r.bitis_lat,r.bitis_lng]],{color:'#4f8cff',weight:4,opacity:.85}).addTo(map); } });

    // Türkçe Açıklama: Heatmap — seçilen şehirdeki talepler
    const resHeat=await fetch('api/heatmap_data.php'); const heatData=await resHeat.json();
    const pts=(heatData.points||[]).filter(p=>p.sehir===city).map(p=>[parseFloat(p.latitude),parseFloat(p.longitude),Math.max(1,parseInt(p.kisi_sayisi))]);
    if(pts.length) L.heatLayer(pts,{radius:25,blur:12,maxZoom:17,gradient:{0.2:'#22c55e',0.5:'#f59e0b',0.8:'#ef4444'}}).addTo(map);
  }
  document.getElementById('applyCity').addEventListener('click',()=>load(citySel.value));
  load(citySel.value);
}

function initTrack(){
  const form=document.getElementById('trackForm'); const mapEl=document.getElementById('trackMap'); if(!form||!mapEl) return;
  const urlParams=new URLSearchParams(window.location.search); const preset=urlParams.get('talep_id'); if(preset){ document.getElementById('trackId').value=preset; }
  const map=L.map('trackMap').setView([39,35],6);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);
  let markers=[];

  async function query(id){
    const res=await fetch(`api/track_data.php?talep_id=${encodeURIComponent(id)}`); const data=await res.json(); const out=document.getElementById('trackResult');
    if(!data.ok){ out.innerHTML=`<div class="toast" style="display:block;">${data.msg}</div>`; return; }
    const t=data.talep||{}; const d=data.deliveries||[]; const r=data.routes||[];
    out.innerHTML=`<div class="table"><div><b>Talep:</b> #${t.id} — ${t.sehir} / ${t.mahalle} — ${t.sokak}</div><div><b>Durum:</b> ${t.durum} — <b>Aciliyet:</b> ${t.aciliyet_seviyesi}</div></div>`;
    markers.forEach(m=>map.removeLayer(m)); markers=[];
    if(t.latitude&&t.longitude){
      const userIcon=L.divIcon({html:'<i class="fa-solid fa-person-shelter" style="color:#ef4444;font-size:22px;"></i>'});
      const mk=L.marker([t.latitude,t.longitude],{icon:userIcon}).addTo(map).bindPopup(`Talep #${t.id}`); markers.push(mk); map.setView([t.latitude,t.longitude],12);
    }
    d.forEach(x=>{ if(x.depo_lat&&x.depo_lng){ const depotIcon=L.divIcon({html:'<i class="fa-solid fa-warehouse" style="color:#4f8cff;font-size:22px;"></i>'}); const mk=L.marker([x.depo_lat,x.depo_lng],{icon:depotIcon}).addTo(map).bindPopup(`${x.depo} (${x.depo_sehir})`); markers.push(mk); } });
    r.forEach(line=>{ if(line.baslangic_lat&&line.bitis_lat){ L.polyline([[line.baslangic_lat,line.baslangic_lng],[line.bitis_lat,line.bitis_lng]],{color:'#4f8cff',weight:4,opacity:.9}).addTo(map); } });
  }

  form.addEventListener('submit',e=>{ e.preventDefault(); const id=document.getElementById('trackId').value; query(id); });
  if(preset){ query(preset); }
}

async function initVolunteer(){
  const tbl=document.getElementById('volTasks'); const mapEl=document.getElementById('volMap'); if(!tbl||!mapEl) return;
  const res=await fetch('api/volunteer_tasks.php'); const data=await res.json();
  tbl.innerHTML=`<tr><th>Talep</th><th>Şehir/Mahalle</th><th>Kişi</th><th>Durum</th></tr>`+(data.tasks||[]).map(t=>`<tr><td>#${t.id}</td><td>${t.sehir}/${t.mahalle}</td><td>${t.kisi_sayisi}</td><td>${t.durum}</td></tr>`).join('');
  const map=L.map('volMap').setView([39,35],6);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);
  (data.tasks||[]).forEach(t=>{ if(t.latitude&&t.longitude){ L.marker([t.latitude,t.longitude]).addTo(map).bindPopup(`#${t.id} — ${t.sehir}/${t.mahalle}`); } });
}

async function initSupplier(){
  const matSel=document.getElementById('supMaterial'); const tbl=document.getElementById('supplierTasks'); if(!matSel||!tbl) return;
  const res=await fetch('api/supplier_data.php'); const data=await res.json();
  (data.materials||[]).forEach(m=>{ const o=document.createElement('option'); o.value=m.id; o.textContent=m.ad; matSel.appendChild(o); });
  tbl.innerHTML=`<tr><th>Talep</th><th>Malzeme</th><th>Miktar</th><th>İşlem</th></tr>`+(data.allocations||[]).map(a=>`<tr><td>#${a.talep_id}</td><td>${a.malzeme}</td><td>${a.miktar}</td><td><button class="btn outline" onclick="supplierAccept(${a.talep_id},${a.malzeme_id})"><i class="fa-solid fa-check"></i> Kabul</button></td></tr>`).join('');
  document.getElementById('supplierForm')?.addEventListener('submit',async(e)=>{ e.preventDefault(); const fd=new FormData(e.target); await fetch('api/supplier_data.php',{method:'POST',body:fd}); alert('Kaydedildi'); });
}
async function supplierAccept(talep_id,malzeme_id){ await fetch('api/supplier_accept.php',{method:'POST',body:new URLSearchParams({talep_id,malzeme_id})}); alert('Talep kabul edildi'); }

async function initDriver(){
  const tbl=document.getElementById('driverTasks'); const mapEl=document.getElementById('driverMap'); if(!tbl||!mapEl) return;
  const res=await fetch('api/driver_tasks.php'); const data=await res.json();
  tbl.innerHTML=`<tr><th>Teslimat</th><th>Talep</th><th>Mod</th><th>Durum</th><th>Aksiyon</th></tr>`+(data.tasks||[]).map(t=>`<tr><td>#${t.id}</td><td>#${t.talep_id}</td><td>${t.tasima_modu}</td><td>${t.durum}</td><td><button class="btn outline" onclick="updateDelivery(${t.id},'En Route')"><i class="fa-solid fa-play"></i> Başlat</button> <button class="btn primary" onclick="updateDelivery(${t.id},'Delivered')"><i class="fa-solid fa-check"></i> Teslim</button></td></tr>`).join('');
  const map=L.map('driverMap').setView([39.0,35.0],6);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19}).addTo(map);
  (data.routes||[]).forEach(r=>{ if(r.baslangic_lat&&r.bitis_lat){ L.polyline([[r.baslangic_lat,r.baslangic_lng],[r.bitis_lat,r.bitis_lng]],{color:'#55d6e8',weight:3}).addTo(map); } });
}
async function updateDelivery(id,status){ await fetch('api/delivery_update.php',{method:'POST',body:new URLSearchParams({teslimat_id:id,durum:status})}); alert('Güncellendi'); }



