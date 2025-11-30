<?php
include('../db.php'); // Inclui o arquivo de conexão com o banco de dados

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $_POST['senha']; // Não escapamos aqui, pois será usada para validação

    // Verifica se os campos foram preenchidos
    if (empty($email) || empty($senha)) {
        die("Erro: Todos os campos são obrigatórios.");
    }

    // Consulta para verificar o email
    $sql_code = "SELECT * FROM usuarios_medicos WHERE email = ?";
    $stmt = $mysqli->prepare($sql_code);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        // Verifica se a senha informada corresponde à hash armazenada
        if (password_verify($senha, $usuario['senha'])) {
            session_start();
            $_SESSION['id'] = $usuario['id'];
            $_SESSION['nome'] = $usuario['nome'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['cpf'] = $usuario['cpf'];
            $_SESSION['telefone'] = $usuario['telefone'];
            $_SESSION['cidade'] = $usuario['cidade'];
            $_SESSION['bairro'] = $usuario['bairro'];
            $_SESSION['data_nascimento'] = $usuario['data_nascimento'];
            $_SESSION['genero'] = $usuario['genero'];
            $_SESSION['especialidade'] = $usuario['especialidade'];

            // Redireciona para a página inicial do paciente
            header("Location: ../homeMedico/homeDoctor.php");
            exit();
        } else {
            echo "Erro: E-mail ou senha incorretos.";
        }
    } else {
        echo "Erro: E-mail ou senha incorretos.";
    }

    $stmt->close();
}
?>
