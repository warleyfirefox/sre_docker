<?php
$servername = "mysql"; // Nome do serviço do container MySQL
$username = "root";
$password = "root";
$dbname = "db_sre";

// Criando conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Checando a conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_sucesso = false;
$nome_usuario = "";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT dev FROM data_sre WHERE email=? AND password=?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nome_usuario);
        $stmt->fetch();
        $login_sucesso = true;
    } else {
        $msg = "<p class='error'>Credenciais inválidas!</p>";
    }

    $stmt->close();
}

// Registro de usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $nome = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<p class='error'>E-mail inválido!</p>";
    } elseif ($password !== $confirm_password) {
        $msg = "<p class='error'>As senhas não coincidem!</p>";
    } else {
        $stmt = $conn->prepare("INSERT INTO data_sre (email, password, dev) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $password, $nome);
        if ($stmt->execute()) {
            $msg = "<p class='success'>Cadastro realizado com sucesso!</p>";
        } else {
            $msg = "<p class='error'>Erro ao cadastrar!</p>";
        }
        $stmt->close();
    }
}

// Exibir tabela para o usuário "admin"
if ($login_sucesso && $email === "admin" && $password === "12345") {
    $query = "SELECT * FROM data_sre";
    $result = $conn->query($query);
    $tabela_html = "<h2>Tabela de Usuários</h2><table border='1'><tr><th>ID</th><th>Email</th><th>Nome</th><th>Senha</th></tr>";
    while ($row = $result->fetch_assoc()) {
        $tabela_html .= "<tr><td>{$row['id']}</td><td>{$row['email']}</td><td>{$row['dev']}</td><td>{$row['password']}</td></tr>";
    }
    $tabela_html .= "</table>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>

        table {
            width: 100%;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            border-collapse: collapse;
            border: 2px solid white;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #E0EFFF;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .inputs {
            margin-right: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: -22px;
        }
        input[type="submit"]:hover {
            background: #45a049;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
            font-weight: bold;
        }

        .register-link {
            font-size: 15px;
            color: blue;
            text-align: right;
            cursor: pointer;
            margin-right: -20px;
        }

        .back-link {
            font-size: 15px;
            color: blue;
            text-align: right;
            cursor: pointer;
            margin-right: 10px;
        }

        .back-login{
            width: 29%;
            padding: 10px;
            background: #008aff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            margin-top: 14px;
        }

        .back-login:hover {
            background: #007BFF;

        }

        .btn-voltar {
            width: 29%;
            padding: 10px;
            background: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
            margin-top: 14px;
        }
    </style>
    <script>
        function toggleForm(showRegister) {
            document.getElementById('loginForm').style.display = showRegister ? 'none' : 'block';
            document.getElementById('registerForm').style.display = showRegister ? 'block' : 'none';
        }
    </script>
</head>
<body>

<div class="container">
    <?php if ($login_sucesso): ?>
        <?php if ($email === "admin" && $password === "12345"): ?>
            <h2>Bem-vindo, Admin!</h2>
            <?php echo $tabela_html; ?>
            <button onclick="history.back()" class="btn-voltar">Voltar</button>
        <?php else: ?>
            <h2>Bem-vindo, <?php echo htmlspecialchars($nome_usuario); ?>!</h2>
            <form method="POST">
                <button class="back-login" type="submit">Voltar ao Login</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <div id="loginForm">
            <h2>Login SRE</h2>
            <div class="inputs">
                <form method="POST">
                    <input type="text" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Senha" required>
                    <div style="width: 100%; margin-bottom: 9px; text-align: right;">
                        <span class="register-link" onclick="toggleForm(true)">Cadastro</span>
                    </div>
                    <input type="submit" value="Entrar">
                </form>
            </div>
        </div>

        <div id="registerForm" style="display: none;">
            <h2>Cadastro</h2>
            <div class="inputs">
                <form method="POST">
                    <input type="text" name="name" placeholder="Nome" required>
                    <input type="text" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Senha" required>
                    <input type="password" name="confirm_password" placeholder="Confirme a Senha" required>
                    <input type="hidden" name="register" value="1">
                    <input type="submit" value="Cadastrar">
                </form>
            </div>
            <div style="width: 100%; margin-top: 12px; text-align: right;">
                <span class="back-link" onclick="toggleForm(false)">Voltar ao Login</span>
            </div>
        </div>
        <?php
            if ($msg !== "") {
                echo "<p id='msg'>$msg</p>"; // Exibir a mensagem
                echo "<script>
                    setTimeout(function() {
                        window.location.href = window.location.pathname;
                    }, 1500);
                </script>";
            }
        ?>

    <?php endif; ?>
</div>

</body>
</html>
