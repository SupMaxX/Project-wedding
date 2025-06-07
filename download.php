<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Descarcă poze și video de la nuntă</title>
  <link rel="stylesheet" href="cssnunta.css">
</head>
<body>
  <a href="nunta.html" class="back-top-left">← Înapoi</a>

  <header>
    <h1>Descarcă poze și video 📥</h1>
  </header>

  <section class="gallery">
    <div class="gallery-container">
      <?php
      $conn = new mysqli("localhost", "root", "123456", "nunta");
      if ($conn->connect_error) {
          die("Eroare conexiune DB: " . $conn->connect_error);
      }

      $result = $conn->query("SELECT filepath FROM poze ORDER BY uploaded_at DESC");

      if ($result->num_rows === 0) {
          echo "<p style='grid-column: 1 / -1;'>Momentan nu sunt fișiere încărcate.</p>";
      }

      while ($row = $result->fetch_assoc()) {
          $file = htmlspecialchars($row["filepath"]);
          $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

          echo "<div>";
          if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
              echo "<img src='$file' alt='poza'>";
          } elseif (in_array($ext, ['mp4', 'mov', 'avi'])) {
              echo "<video src='$file' controls style='max-width: 100%; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1);'></video>";
          }
          echo "<br><a href='$file' download><button>Descarcă</button></a></div>";
      }

      $conn->close();
      ?>
    </div>
  </section>
</body>
</html>
