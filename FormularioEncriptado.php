<?php
$clave     = "clave123456789012";
$algoritmo = "AES-128-CBC";
$cifrado   = $descifrado = $paquete = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $texto  = trim($_POST["texto"] ?? "");
    $accion = $_POST["accion"] ?? "";

    if ($accion === "cifrar" && $texto !== "") {
        $iv      = openssl_random_pseudo_bytes(openssl_cipher_iv_length($algoritmo));
        $cifrado = openssl_encrypt($texto, $algoritmo, $clave, 0, $iv);
        $paquete = base64_encode($iv) . ":" . $cifrado;
    } elseif ($accion === "descifrar" && str_contains($texto, ":")) {
        [$ivB64, $enc] = explode(":", $texto, 2);
        $descifrado    = openssl_decrypt($enc, $algoritmo, $clave, 0, base64_decode($ivB64));
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cifrado AES-128-CBC</title>
    <style>
        body { font-family: monospace; max-width: 600px; margin: 40px auto; padding: 0 1rem; background: #f4f4f4; }
        h2   { color: #333; }
        textarea { width: 100%; height: 80px; padding: 8px; font-family: monospace; }
        button   { padding: 8px 20px; margin-right: 8px; cursor: pointer; }
        .box     { background: #fff; border: 1px solid #ccc; padding: 10px; margin-top: 12px; word-break: break-all; }
        label    { font-size: .85rem; color: #555; }
    </style>
</head>
<body>
    <h2>Cifrado Simétrico — AES-128-CBC</h2>

    <form method="POST">
        <label>Texto (para descifrar, pega el resultado completo):</label><br>
        <textarea name="texto"><?= htmlspecialchars($_POST["texto"] ?? "") ?></textarea><br><br>
        <button name="accion" value="cifrar">🔒 Cifrar</button>
        <button name="accion" value="descifrar">🔓 Descifrar</button>
    </form>

    <?php if ($paquete): ?>
        <div class="box">
            <label>Resultado cifrado (copia esto para descifrar):</label><br>
            <strong><?= htmlspecialchars($paquete) ?></strong>
        </div>
    <?php endif; ?>

    <?php if ($descifrado): ?>
        <div class="box">
            <label>Texto descifrado:</label><br>
            <strong><?= htmlspecialchars($descifrado) ?></strong>
        </div>
    <?php endif; ?>
</body>
</html>