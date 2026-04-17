<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña — CensoApp</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --navy:#0f1f3d; --teal:#0d7377; --gold:#e8a020; --light:#f4f6f9; --muted:#8896a8; --white:#ffffff; --radius:12px; }
        body { font-family: 'DM Sans', sans-serif; background: var(--navy); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .card { background: var(--light); border-radius: 18px; padding: 44px 40px; width: 100%; max-width: 420px; }
        .icon { font-size: 40px; margin-bottom: 16px; }
        h2 { font-family: 'DM Serif Display', serif; font-size: 28px; color: var(--navy); margin-bottom: 6px; }
        .subtitle { font-size: 14px; color: var(--muted); margin-bottom: 28px; line-height: 1.6; }
        .form-group { margin-bottom: 20px; }
        label { display: block; font-size: 13px; font-weight: 600; color: var(--navy); margin-bottom: 8px; }
        input[type="email"] { width: 100%; padding: 13px 16px; border: 1.5px solid #dce2ea; border-radius: var(--radius); font-family: 'DM Sans', sans-serif; font-size: 14px; color: var(--navy); background: var(--white); outline: none; transition: border-color .2s; }
        input:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(13,115,119,.12); }
        .btn-submit { width: 100%; padding: 14px; background: var(--navy); color: var(--white); border: none; border-radius: var(--radius); font-family: 'DM Sans', sans-serif; font-size: 15px; font-weight: 600; cursor: pointer; transition: background .2s; }
        .btn-submit:hover { background: var(--teal); }
        .alert { padding: 14px 16px; border-radius: var(--radius); font-size: 14px; margin-bottom: 20px; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1.5px solid #93c5fd; }
        .back { display: block; text-align: center; margin-top: 20px; font-size: 13px; color: var(--muted); }
        .back a { color: var(--teal); font-weight: 600; text-decoration: none; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🔑</div>
        <h2>Recuperar contraseña</h2>
        <p class="subtitle">Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.</p>

        <?php if (!empty($mensaje)): ?>
            <div class="alert alert-info">✉️ <?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="solicitar_recuperacion">
            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" id="correo" name="correo" placeholder="tu@correo.com" required>
            </div>
            <button type="submit" class="btn-submit">Enviar enlace</button>
        </form>

        <span class="back"><a href="login.html">← Volver al inicio de sesión</a></span>
    </div>
</body>
</html>