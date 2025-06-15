<?php
session_start();
include 'conexion.php';

$error = "";

if (isset($_POST["ingresar"])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['rol'] = $row['rol'];

            if ($row['rol'] === 'super_admin'  || $row['rol'] === 'admin') {
                header("Location: usuarios/admin_usuarios.php");
            } else {
                header("Location: nota/nota.php");
            }
            exit();
        } else {
            $error = "⛔ Contraseña incorrecta. Intenta nuevamente.";
        }
    } else {
        $error = "❌ Usuario no encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form | Dan Aleko</title>
  <link rel="stylesheet" href="styles.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

  <div class="wrapper">
    <form action="">
      <h1>Login</h1>
      <div class="input-box">
        <input type="email" name="email" class="form-control" placeholder="Correo Electrónico" required maxlength="50">
        <i class='bx bxs-user'></i>
      </div>
      <div class="input-box">
        <input type="password" name="password" class="form-control" placeholder="Contraseña" required require minlength="8">
        <i class='bx bxs-lock-alt' ></i>
      </div>
      <div class="remember-forgot">
        <label><input type="checkbox">Remember Me</label>
        <a href="#">Forgot Password</a>
      </div>
      <button type="submit" class="btn">Login</button>
      <div class="register-link">
                 <p class="text-center mt-3">
                <a href="usuarios/recupera.php" class="text-decoration-none text-primary">¿Olvidaste tu contraseña?</a>
            </p>
            <p class="text-center mt-2">
                ¿No tienes cuenta? <a href="usuarios/nuevo_usuario.php" class="text-decoration-none text-primary">Regístrate aquí</a>
            </p>
        
      </div>
    </form>
  </div>
</body>
</html>
<script>
  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('service-worker.js')
      .then(function(registration) {
        console.log('✅ Service Worker registrado con éxito:', registration.scope);
      })
      .catch(function(error) {
        console.log('❌ Error al registrar el Service Worker:', error);
      });
  }
</script>
</html>
