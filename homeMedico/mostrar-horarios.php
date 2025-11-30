<?php
include('../db.php');

// Verificar se o médico está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../loginMedico/doctorLogin.html");
    exit();
}

// Obter o ID do médico
$medico_id = $_SESSION['id'];

// Consultar os horários associados ao médico
$query = "
    SELECT horarios_medico.dia, horarios_medico.hora, 
           usuarios_pacientes.nome AS paciente_nome, 
           usuarios_pacientes.data_nascimento AS paciente_data_nascimento, 
           usuarios_pacientes.genero AS paciente_genero, 
           usuarios_pacientes.telefone AS paciente_telefone
    FROM horarios_medico
    LEFT JOIN usuarios_pacientes ON horarios_medico.paciente_id = usuarios_pacientes.id
    WHERE horarios_medico.medico_id = ?
    ORDER BY horarios_medico.dia, horarios_medico.hora;
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $medico_id);
$stmt->execute();
$result = $stmt->get_result();

// Inicializar array para organizar horários por dias
$horarios = [];
$diasSemana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];

// Processar resultados
while ($row = $result->fetch_assoc()) {
    $dia = $row['dia'];
    $horarios[$dia][] = [
        'hora' => date("H:i", strtotime($row['hora'])),
        'status' => $row['paciente_nome'] ? 'Agendado' : 'Livre',
        'paciente' => $row['paciente_nome'],
        'idade' => isset($row['paciente_data_nascimento']) ? (new DateTime($row['paciente_data_nascimento']))->diff(new DateTime())->y : null,
        'genero' => $row['paciente_genero'],
        'telefone' => $row['paciente_telefone']
    ];
}

// Gerar a tabela
echo '<table class="horarios-tabela">';
echo '<thead>';
echo '<tr>';
foreach ($diasSemana as $dia) {
    echo '<th colspan="2">' . htmlspecialchars($dia) . '</th>';
}
echo '</tr>';
echo '<tr>';
foreach ($diasSemana as $dia) {
    echo '<th>Horário</th><th>Status</th>';
}
echo '</tr>';
echo '</thead>';
echo '<tbody>';

// Calcular o maior número de horários em qualquer dia
$maxHorarios = max(array_map('count', $horarios));

// Preencher linhas da tabela
for ($i = 0; $i < $maxHorarios; $i++) {
    echo '<tr>';
    foreach ($diasSemana as $dia) {
        if (isset($horarios[$dia][$i])) {
            $horario = $horarios[$dia][$i];
            echo '<td>' . htmlspecialchars($horario['hora']) . '</td>';
            echo '<td>';
            echo htmlspecialchars($horario['status']);
            if ($horario['status'] === 'Agendado') {
                $pacienteData = htmlspecialchars(json_encode([
                    'nome' => $horario['paciente'],
                    'idade' => $horario['idade'],
                    'genero' => $horario['genero'],
                    'telefone' => $horario['telefone']
                ]), ENT_QUOTES, 'UTF-8');
                echo '<br> <button onclick="mostrarFichaPaciente(\'' . $pacienteData . '\')" class="btn-ficha" >Ver Ficha</button>';
            }
            echo '</td>';
        } else {
            echo '<td></td><td></td>';
        }
    }
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
?>
