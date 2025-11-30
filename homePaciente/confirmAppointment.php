<?php
session_start();
include('../db.php');

// Obtém o ID do horário selecionado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horario_id = $_POST['horario_id'];
    $paciente_id = $_SESSION['id'];

    // Busca informações do horário para confirmação
    $query = "SELECT dia, TIME_FORMAT(hora, '%H:%i') AS hora FROM horarios_medico WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $horario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $horario = $result->fetch_assoc();
    } else {
        echo "Horário não encontrado.";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Consulta</title>
</head>

<style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #e6f7ff; /* Fundo azul claro */
        color: #333; /* Texto em cor neutra */
    }

    .container {
        width: 90%;
        max-width: 600px;
        margin: 50px auto; /* Centralizar no meio da página */
        background-color: #ffffff; /* Fundo branco */
        border-radius: 10px; /* Bordas arredondadas */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra leve */
        padding: 20px;
        border: 2px solid #b3d9ff; /* Borda azul */
    }

    h2 {
        text-align: left;
        color: #00509e; /* Azul escuro */
        margin-bottom: 20px;
    }

    p {
        font-size: 16px;
        margin: 10px 0;
    }

    ul {
        list-style-type: none; /* Remove marcadores */
        padding: 0;
    }

    ul li {
        margin-left: 20px;
        font-size: 16px;
    }

    ul li strong {
        color: #00509e; /* Azul escuro */
    }

    form {
        margin-top: 20px;
        display: flex;
        justify-content: space-between; /* Botões lado a lado */
    }

    form button, form a {
        display: inline-block;
        padding: 10px 20px; /* Tamanho do botão */
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none; /* Remove sublinhado do link */
        border-radius: 5px; /* Bordas arredondadas */
        transition: background-color 0.3s, transform 0.2s; /* Animação ao passar o mouse */
    }

    form button {
        background-color: #116dba; /* Azul padrão */
        color: #ffffff; /* Texto branco */
        border: none;
        cursor: pointer;
    }

    form button:hover {
        background-color: #0d5b9d; /* Azul mais escuro */
        transform: scale(1.05); /* Leve aumento ao passar o mouse */
    }

    form a {
        background-color: #cccccc; /* Cinza para cancelar */
        color: #333; /* Texto escuro */
        border: 1px solid #999; /* Borda leve */
    }

    form a:hover {
        background-color: #bbbbbb; /* Cinza mais escuro no hover */
        transform: scale(1.05); /* Leve aumento ao passar o mouse */
    }

</style>

<body>
    <div class="container">
        <h2>Confirmação de Horário</h2>
        <p>Você selecionou o seguinte horário:</p>
        <ul>
            <li><strong>Dia:</strong> <?php echo htmlspecialchars($horario['dia']); ?></li>
            <li><strong>Hora:</strong> <?php echo htmlspecialchars($horario['hora']); ?></li>
        </ul>
        <p>Deseja confirmar este horário para sua consulta?</p>

        <form method="POST" action="finalizeAppointment.php">
            <input type="hidden" name="horario_id" value="<?php echo htmlspecialchars($horario_id); ?>">
            <button type="submit">Sim, confirmar</button>
            <a href="homePacient.php">Cancelar</a>
        </form>
    </div>
</body>

