<?php
// Türkçe Açıklama: Kullanıcı giriş sayfası
require_once 'config/db.php';
require_once 'config/auth.php'; // burada session başlatılır

$msg = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $pass  = trim($_POST['password'] ?? '');

    // Veritabanından kullanıcıyı email ile arıyoruz
    $stmt = $pdo->prepare("SELECT id, ad, rol, sifre_hash FROM kullanicilar WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if($user){
        $valid = false;

        // Şifre kontrolü: hem hash hem düz metin desteklenir
        if(!empty($user['sifre_hash'])){
            if(password_verify($pass, $user['sifre_hash'])){
                $valid = true;
            } elseif($pass === $user['sifre_hash']){
                $valid = true;
            }
        }

        if($valid){
            // Oturum bilgilerini kaydediyoruz
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['name']      = $user['ad'];
            $_SESSION['user_role'] = $user['rol'];

            // Rol'e göre yönlendirme
            switch($user['rol']){
                case 'Admin':
                    header("Location: dashboard.php");
                    break;
                case 'Tedarikci':
                    header("Location: supplier.php");
                    break;
                case 'Gonullu':
                    header("Location: volunteer.php");
                    break;
                case 'Surucu':
                    header("Location: driver.php");
                    break;
                case 'Afetzede':
                    header("Location: afetzede.php"); // Eğer afetzede için ayrı sayfa yaparsak buraya yönlendirilir
                    break;
                default:
                    header("Location: dashboard.php");
            }
            exit();
        } else {
            $msg = "Geçersiz email veya şifre!";
        }
    } else {
        $msg = "Geçersiz email veya şifre!";
    }
}

include 'templates/header.php';
?>

<!-- Giriş formu -->
<div class="container">
  <div class="card" style="max-width:420px;margin:auto;">
    <h2 class="title"><i class="fa-solid fa-user-shield"></i> Giriş</h2>
    <?php if($msg): ?><div class="toast" style="display:block;"><?php echo $msg; ?></div><?php endif; ?>
    <form method="POST">
      <label>Email</label>
      <input type="email" name="email" required>

      <label>Şifre</label>
      <input type="password" name="password" required>

      <button class="btn primary" type="submit">
        <i class="fa-solid fa-right-to-bracket"></i> Giriş
      </button>
    </form>
  </div>
</div>

<?php include 'templates/footer.php'; ?>

>





