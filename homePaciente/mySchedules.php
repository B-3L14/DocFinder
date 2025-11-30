<?php
include('../db.php');

$paciente_id = $_SESSION['id'];

// Consulta para buscar os horários marcados do paciente
$query = "SELECT hm.dia, TIME_FORMAT(hm.hora, '%H:%i') AS hora, um.nome AS medico_nome, um.especialidade 
          FROM horarios_medico hm
          JOIN usuarios_medicos um ON hm.medico_id = um.id
          WHERE hm.paciente_id = ?
          ORDER BY hm.dia, hm.hora";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    echo "<table>
            <tr>
                <th>Data</th>
                <th>Hora</th>
                <th>Médico</th>
                <th>Especialidade</th>
            </tr>";
    while ($consulta = $resultado->fetch_assoc()) {
        echo "<tr>
                <td>{$consulta['dia']}</td>
                <td>{$consulta['hora']}</td>
                <td>Dr(a). {$consulta['medico_nome']}</td>
                <td>{$consulta['especialidade']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>Você não possui consultas marcadas no momento.</p>";
}
?>
