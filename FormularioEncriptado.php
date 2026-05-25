<?php
$algoritmo  = "AES-128-CBC";
$iv_hex     = $cifrado_b64 = $descifrado = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $texto = trim($_POST["texto"] ?? "");
    $clave = trim($_POST["clave"] ?? "");

    if ($texto !== "" && $clave !== "") {
        // Normalizar clave a exactamente 16 caracteres
        $clave = str_pad(substr($clave, 0, 16), 16, "0");

        $iv          = openssl_random_pseudo_bytes(openssl_cipher_iv_length($algoritmo));
        $iv_hex      = bin2hex($iv);
        $cifrado_b64 = openssl_encrypt($texto, $algoritmo, $clave, 0, $iv);
        $descifrado  = openssl_decrypt($cifrado_b64, $algoritmo, $clave, 0, $iv);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cifrado AES-128-CBC</title>
    <style>
        body    { font-family: monospace; max-width: 600px; margin: 40px auto; padding: 0 1rem; background: #f0f0f0; }
        h2      { color: #222; }
        label   { display: block; margin-top: 12px; font-size: .85rem; color: #444; }
        textarea, input[type=text] { width: 100%; padding: 8px; font-family: monospace; box-sizing: border-box; }
        textarea { height: 80px; }
        button  { margin-top: 14px; padding: 9px 24px; cursor: pointer; font-size: 1rem; }
        .box    { background: #fff; border-left: 4px solid #555; padding: 10px 14px; margin-top: 14px; word-break: break-all; }
        .box b  { display: block; font-size: .8rem; color: #666; margin-bottom: 4px; }
        .ok     { border-color: green; }
    </style>
</head>
<body>
    <h2>Cifrado Simétrico — AES-128-CBC</h2>

    <form method="POST">
        <label>Mensaje en claro (Textarea):</label>
        <textarea name="texto"><?= htmlspecialchars($_POST["texto"] ?? "") ?></textarea>

        <label>Clave Secreta Compartida (se normaliza a 16 caracteres):</label>
        <input type="text" name="clave" value="<?= htmlspecialchars($_POST["clave"] ?? "") ?>">

        <button type="submit">Cifrar y Descifrar</button>
    </form>

    <?php if ($iv_hex): ?>
        <div class="box">
            <b>Vector de Inicialización — IV (hexadecimal):</b>
            <?= htmlspecialchars($iv_hex) ?>
        </div>
        <div class="box">
            <b>Texto Cifrado (Base64):</b>
            <?= htmlspecialchars($cifrado_b64) ?>
        </div>
        <div class="box ok">
            <b>Resultado Final del Descifrado:</b>
            <?= htmlspecialchars($descifrado) ?>
        </div>
    <?php endif; ?>
</body>
</html>