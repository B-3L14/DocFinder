<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    .doctor-box {
        background-color: #c9def2; /* Fundo branco */
        border: 3px solid #b3d9ff; /* Borda sutil azul */
        border-radius: 8px; /* Bordas arredondadas */
        padding: 20px; /* Espaçamento interno */
        margin: 15px 0; /* Espaçamento entre os blocos */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Sombra para destaque */
    }

    .doctor-box h3 {
        font-size: 26px; /* Tamanho do título */
        margin-bottom: 10px; /* Espaçamento inferior */
        color: #00509e; /* Azul escuro para o título */
    }

    .doctor-box p {
        font-size: 18px; /* Tamanho do texto */
        margin: 5px 0; /* Espaçamento entre os parágrafos */
        color: #333; /* Cor escura para o texto */
    }

    .doctor-box a {
        display: inline-block; /* Exibir o link como um botão */
        margin-top: 10px; /* Espaçamento superior */
        padding: 10px 20px; /* Espaçamento interno */
        background-color: #116dba; /* Fundo azul escuro */
        color: #ffffff; /* Texto branco */
        text-decoration: none; /* Remover sublinhado */
        border-radius: 5px; /* Bordas arredondadas */
        font-weight: bold; /* Texto em negrito */
        transition: background-color 0.3s, transform 0.2s; /* Animações */
    }

    .doctor-box a:hover {
        background-color: #003d73; /* Azul mais escuro ao passar o mouse */
        transform: scale(1.05); /* Leve ampliação ao passar o mouse */
    }

</style>

<body>
    <?php
    include('../db.php');

    // Captura os filtros
    $especialidade = $_GET['especialidade'] ?? '';
    $cidade = $_GET['cidade'] ?? '';
    $genero = $_GET['genero'] ?? '';

    // Query para buscar médicos com base nos filtros
    $query = "SELECT id, nome, genero, especialidade, cidade, TIMESTAMPDIFF(YEAR, data_nascimento, CURDATE()) AS idade 
            FROM usuarios_medicos 
            WHERE 1=1";

    if ($especialidade !== '') {
        $query .= " AND especialidade = ?";
    }
    if ($cidade !== '') {
        $query .= " AND cidade LIKE ?";
    }
    if ($genero !== '') {
        $query .= " AND genero = ?";
    }

    $stmt = $mysqli->prepare($query);

    $params = [];
    if ($especialidade !== '') $params[] = $especialidade;
    if ($cidade !== '') $params[] = "%$cidade%";
    if ($genero !== '') $params[] = $genero;

    // Executa a consulta
    $stmt->execute($params);
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        while ($medico = $resultado->fetch_assoc()) {
            echo "<div class='doctor-box'>
                    <h3>Dr(a). {$medico['nome']}</h3>
                    <p>Gênero: {$medico['genero']}</p>
                    <p>Idade: {$medico['idade']}</p>
                    <p>Localização: {$medico['cidade']}</p>
                    <p>Especialidade: {$medico['especialidade']}</p>
                    <a href='doctorSchedule.php?medico_id={$medico['id']}'>Ver agenda</a>
                </div>";
        }
    } else {
        echo "<p>Nenhum médico encontrado.</p>";
    }
    ?>
</body>
</html>
