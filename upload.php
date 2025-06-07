<?php
if (!isset($_FILES['poza']) || $_FILES['poza']['error'] !== UPLOAD_ERR_OK) {
    echo "❌ Eroare la încărcarea fișierului.";
    exit;
}

$file = $_FILES['poza'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'webm', 'avi'];

if (!in_array($ext, $allowed)) {
    echo "❌ Tip de fișier neacceptat.";
    exit;
}

$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$uniqueName = uniqid() . "." . $ext;
$targetPath = $uploadDir . $uniqueName;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // conectare DB și salvare path
    $conn = new mysqli("localhost", "root", "123456", "nunta");
    if ($conn->connect_error) {
        echo "❌ Eroare conexiune DB.";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO poze (filepath) VALUES (?)");
    $stmt->bind_param("s", $targetPath);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "<div style='background:#e8f5e9; color:#2e7d32; padding:24px; border:2px solid #a5d6a7; border-radius:12px; max-width:600px; margin:60px auto; font-family:Arial, sans-serif; text-align:center;'>
            <h2 style='margin-top:0;'>✅ Poza sau videoclipul a fost încărcat cu succes!</h2>
            <p>Mulțumim că ai contribuit cu o amintire frumoasă!</p>
            <a href='nunta.html' style='display:inline-block; margin-top:20px; padding:10px 20px; background:#2e7d32; color:white; border-radius:8px; text-decoration:none;'>← Înapoi la pagina principală</a>
          </div>";
} else {
    echo "<div style='background:#fee; color:#b00; padding:20px; border:1px solid #f99; border-radius:10px; max-width:500px; margin:40px auto;'>
            <strong>✘ Eroare la încărcarea fișierului!</strong><br>
            Te rugăm să încerci din nou.
            <br><br><a href='upload.html' style='color:#006400;'>← Înapoi</a>
          </div>";
}
?>
