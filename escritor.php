<?php

session_start();
include 'db_connect.php';

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $autor = $_SESSION['username'];
    $texto = $_POST['texto'];
    $imagem = $_FILES['imagem']['name'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($imagem);
    move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file);

    $query = $conn->prepare("INSERT INTO noticias (titulo, autor, texto, imagem) VALUES (?, ?, ?, ?)");
    $query->bind_param("ssss", $titulo, $autor, $texto, $imagem);
    $query->execute();
    $success = "Notícia enviada para aprovação!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escritor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="escritor.css" rel="stylesheet"> 
    <style>
        .center-screen {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container center-screen">
        <div class="bg-light p-4 rounded shadow-sm w-100" style="max-width: 600px;">
            <h1 class="text-center">Criar Notícia</h1>
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="texto" class="form-label">Texto</label>
                    <textarea name="texto" id="texto" class="form-control" rows="5" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="imagem" class="form-label">Imagem</label>
                    <input type="file" name="imagem" id="imagem" class="form-control" required>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="index.php" class="btn btn-secondary">Voltar para Página Inicial</a>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
