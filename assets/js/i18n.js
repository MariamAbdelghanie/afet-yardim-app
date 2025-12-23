// =======================================
// Türkçe Açıklama:
// Bu dosya uygulamanın çok dilli yapısını yönetir.
// Şu an TR ve EN desteklidir.
// =======================================

// 🌍 Dil sözlüğü
const dict = {
  tr: {
    submit: 'Talep Gönder',
    track: 'Talep Takibi',
    panel: 'Operatör Paneli',
    login: 'Giriş',
    volunteer: 'Gönüllü Görevleri',
    supplier: 'Tedarikçi Paneli',
    driver: 'Sürücü Görevleri',
    status: 'Durum',
    priority: 'Öncelik',
    people: 'Kişi Sayısı',
    needs: 'İhtiyaçlar',
    location: 'Konum',
    urgency: 'Aciliyet',
    map: 'Harita',
    dashboard: 'Yönetim Paneli',
    notifications: 'Bildirimler',
    deliveries: 'Teslimatlar',
    stocks: 'Stoklar',
    requests: 'Talepler'
  },

  en: {
    submit: 'Submit Request',
    track: 'Request Tracking',
    panel: 'Operator Panel',
    login: 'Login',
    volunteer: 'Volunteer Tasks',
    supplier: 'Supplier Panel',
    driver: 'Driver Tasks',
    status: 'Status',
    priority: 'Priority',
    people: 'People Count',
    needs: 'Needs',
    location: 'Location',
    urgency: 'Urgency',
    map: 'Map',
    dashboard: 'Dashboard',
    notifications: 'Notifications',
    deliveries: 'Deliveries',
    stocks: 'Stocks',
    requests: 'Requests'
  }
};

// 🌐 Dil ayarlama fonksiyonu
function setLang(lang = 'tr') {

  // Eğer dil yoksa TR’ye düş
  if (!dict[lang]) lang = 'tr';

  // Sayfadaki tüm data-i18n elemanlarını güncelle
  document.querySelectorAll('[data-i18n]').forEach(el => {
    const key = el.getAttribute('data-i18n');

    if (dict[lang][key]) {
      el.textContent = dict[lang][key];
    }
  });

  // Placeholder desteği
  document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
    const key = el.getAttribute('data-i18n-placeholder');

    if (dict[lang][key]) {
      el.placeholder = dict[lang][key];
    }
  });

  // Seçilen dili sakla
  localStorage.setItem('lang', lang);
}

// 🚀 Sayfa yüklendiğinde çalışır
document.addEventListener('DOMContentLoaded', () => {
  const savedLang = localStorage.getItem('lang') || 'tr';
  setLang(savedLang);
});


