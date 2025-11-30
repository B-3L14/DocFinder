<?php
session_start();
include('../db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $horario_id = $_POST['horario_id'];
    $paciente_id = $_SESSION['id'];

    // Atualiza a tabela para registrar a consulta
    $query = "UPDATE horarios_medico SET paciente_id = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $paciente_id, $horario_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Consulta marcada com sucesso!";
        header("Location: homePacient.php");
        exit();
    } else {
        echo "Erro ao marcar consulta.";
        exit();
    }
}
?>
