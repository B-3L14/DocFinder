<?php
include('../db.php');
session_start();

// Verificar se o médico está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../loginMedico/doctorLogin.html");
    exit();
}

// Obter o ID do médico
$medico_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $diasDisponiveis = $_POST['diasDisponiveis'];
    $horaInicio = $_POST['horaInicio'];
    $horaFim = $_POST['horaFim'];
    $duracaoConsulta = $_POST['duracaoConsulta'];
    $horaAlmocoInicio = $_POST['horaAlmocoInicio'];
    $horaAlmocoFim = $_POST['horaAlmocoFim'];

    // Convertendo hora de início e fim para timestamps
    $horaInicioTimestamp = strtotime($horaInicio);
    $horaFimTimestamp = strtotime($horaFim);

    foreach ($diasDisponiveis as $dia) {
        $horaAtual = $horaInicioTimestamp;

        while ($horaAtual < $horaFimTimestamp) {
            $horaConsulta = date("H:i", $horaAtual);

            // Verifica se o horário de consulta se sobrepõe ao horário de almoço
            if ($horaConsulta >= $horaAlmocoInicio && $horaConsulta < $horaAlmocoFim) {
                $horaAtual = strtotime("+$duracaoConsulta minutes", strtotime($horaAlmocoFim));
                continue;
            }

            // Inserir os horários no banco de dados
            $query = "INSERT INTO horarios_medico (medico_id, dia, hora) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param("iss", $medico_id, $dia, $horaConsulta);
            $stmt->execute();

            // Avança para o próximo horário de consulta
            $horaAtual = strtotime("+$duracaoConsulta minutes", $horaAtual);
        }
    }
    echo "Horários gerados com sucesso!";
}
?>
