<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: index.php");
    exit;
}

$host = "localhost";
$db   = "menu"; 
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}


if (isset($_POST['acao']) && $_POST['acao'] === "adicionar") {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $foto = $_POST['foto'] ?? '';

    $stmt = $conn->prepare("INSERT INTO produtos (nome, descricao, preco, foto) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssds", $nome, $descricao, $preco, $foto);
    $stmt->execute();
    $stmt->close();
}


if (isset($_POST['acao']) && $_POST['acao'] === "editar") {
    $id = $_POST['id'] ?? 0;
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $foto = $_POST['foto'] ?? '';

    $stmt = $conn->prepare("UPDATE produtos SET nome=?, descricao=?, preco=?, foto=? WHERE id_produto=?");
    $stmt->bind_param("ssdsi", $nome, $descricao, $preco, $foto, $id);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['acao']) && $_GET['acao'] === "excluir") {
    $id = $_GET['id'] ?? 0;
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id_produto=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query("SELECT p.id_produto, p.nome, p.descricao, p.preco, p.foto FROM produtos p ORDER BY p.id_produto DESC");

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<title>Estoque Café Blues</title>
<style>
body { font-family: Arial; margin: 20px; background: #f2f2f2; }
h1 { text-align:center; }
table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background:#fff; }
th, td { border: 1px solid #ccc; padding: 10px; text-align: center; }
th { background: #6B3E26; color: #fff; }
form { margin-bottom: 20px; background:#fff; padding:20px; border-radius:10px; }
input { padding:5px; margin:5px 0; width: 100%; }
button { padding:8px 12px; margin-top:5px; cursor:pointer; }
button.excluir { background:red; color:#fff; border:none; }
button.editar { background:green; color:#fff; border:none; }
</style>
</head>
<body>

<h1>Estoque Café Blues</h1>

<h2>Adicionar Produto</h2>
<form method="POST">
    <input type="hidden" name="acao" value="adicionar">
    <input type="text" name="nome" placeholder="Nome do produto" required>
    <textarea name="descricao" placeholder="Descrição"></textarea>
    <input type="number" step="0.01" name="preco" placeholder="Preço" required>
    <input type="text" name="foto" placeholder="URL da foto">
    <button type="submit">Adicionar</button>
</form>

<h2>Produtos</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Descrição</th>
        <th>Preço</th>
        <th>Foto</th>
        <th>Ações</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <form method="POST">
            <input type="hidden" name="acao" value="editar">
            <input type="hidden" name="id" value="<?php echo $row['id_produto']; ?>">
            <td><?php echo $row['id_produto']; ?></td>
            <td><input type="text" name="nome" value="<?php echo $row['nome']; ?>"></td>
            <td><textarea name="descricao"><?php echo $row['descricao']; ?></textarea></td>
            <td><input type="number" step="0.01" name="preco" value="<?php echo $row['preco']; ?>"></td>
            <td><input type="text" name="foto" value="<?php echo $row['foto']; ?>"></td>
            <td>
                <button type="submit" class="editar">Editar</button>
                <a href="?acao=excluir&id=<?php echo $row['id_produto']; ?>"><button type="button" class="excluir">Excluir</button></a>
            </td>
        </form>
    </tr>
    <?php endwhile; ?>
</table>

<a href="logout.php"><button>Voltar ao site</button></a>

</body>
</html>
