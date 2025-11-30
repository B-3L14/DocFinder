<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda do Médico</title>
    <style>
        .horarios-tabela {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            text-align: center;
        }
        .horarios-tabela th, .horarios-tabela td {
            border: 1px solid black;
            padding: 8px;
        }
        .horarios-tabela thead th {
            background-color: #cce7ff;
        }
        .horarios-tabela tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .horarios-tabela tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        .horarios-tabela button {
            display: inline-block; /* Exibir o botão como inline-block */
            margin: 5px 0; /* Pequeno espaçamento interno */
            padding: 8px 12px; /* Espaçamento interno ajustado */
            background-color: #116dba; /* Azul escuro para botões disponíveis */
            color: #ffffff; /* Texto branco */
            border: none; /* Sem bordas externas */
            border-radius: 5px; /* Bordas arredondadas */
            font-weight: bold; /* Texto em negrito */
            cursor: pointer; /* Indicador de clique */
            text-align: center; /* Centralizar o texto */
            transition: background-color 0.3s, transform 0.2s; /* Animações suaves */
        }

        .horarios-tabela button:hover {
            background-color: #0d5b9d; /* Azul mais escuro no hover */
            transform: scale(1.05); /* Leve aumento ao passar o mouse */
        }

        .horarios-tabela button:disabled {
            background-color: #cccccc; /* Cinza para botões indisponíveis */
            color: #666666; /* Texto mais claro */
            cursor: not-allowed; /* Cursor indicando que não é clicável */
            text-decoration: line-through; /* Linha sobre o texto para indicar indisponibilidade */
        }

        .horarios-tabela button:disabled:hover {
            background-color: #cccccc; /* Manter a cor no hover para botões desativados */
            transform: none; /* Sem transformação para botões desativados */
        }

        h2 {
            text-align: left;
            color: #00509e; /* Azul escuro */
            margin-bottom: 20px;
            
        }

    </style>
</head>
<body>
<?php
include('../db.php');

// Obter o ID do médico
$medico_id = $_GET['medico_id'];

// Consultar horários do médico
$query = "SELECT id, dia, TIME_FORMAT(hora, '%H:%i') AS hora, paciente_id FROM horarios_medico WHERE medico_id = ? ORDER BY dia, hora";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $medico_id);
$stmt->execute();
$resultado = $stmt->get_result();

// Organizar horários por dia
$horarios = [];
$diasSemana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
while ($horario = $resultado->fetch_assoc()) {
    $horarios[$horario['dia']][] = [
        'id' => $horario['id'],
        'hora' => $horario['hora'],
        'paciente_id' => $horario['paciente_id']
    ];
}

// Gerar a tabela
echo "<h2>Agenda do Médico</h2>";
echo "<form method='POST' action='confirmAppointment.php'>";
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
            $disabled = $horario['paciente_id'] ? "disabled" : "";
            $style = $horario['paciente_id'] ? "text-decoration: line-through;" : "";
            $status = $horario['paciente_id'] ? "Indisponível" : "Disponível";
            echo '<td>' . htmlspecialchars($horario['hora']) . '</td>';
            echo '<td>';
            echo "<button type='submit' name='horario_id' value='{$horario['id']}' $disabled style='$style'>$status</button>";
            echo '</td>';
        } else {
            echo '<td></td><td></td>';
        }
    }
    echo '</tr>';
}
echo '</tbody>';
echo '</table>';
echo "</form>";
?>
</body>
</html>



