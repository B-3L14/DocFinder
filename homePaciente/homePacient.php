<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../loginPaciente/pacientLogin.html");
    exit();
}

// Dados do paciente
$nome = $_SESSION['nome'];
$email = $_SESSION['email'];
$cpf = $_SESSION['cpf'];
$telefone = $_SESSION['telefone'];
$cidade = $_SESSION['cidade'];
$bairro = $_SESSION['bairro'];
$data_nascimento = $_SESSION['data_nascimento'];
$genero = $_SESSION['genero'];

// Formata a data de nascimento para dd/mm/aaaa
$data_nascimento_formatada = date("d/m/Y", strtotime($data_nascimento));

// Determina a saudação com base no gênero
$saudacao = ($genero === "masculino") ? "Bem-vindo" : "Bem-vinda";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Paciente</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e6f7ff; 
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff; 
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border: 2px solid #b3d9ff; 
        }

        h1 {
            text-align: center;
            color: #00509e; 
        }

        .info-section {
            margin: 20px 0;
            border: 1px solid #99ccff; 
            border-radius: 8px;
            padding: 15px;
            background-color: #cce7ff; 
        }

        h2 {
            font-size: 25px;
            margin-bottom: 10px;
            margin-left: 20px;
            color: #003d73; 
        }

        .info-section h2 {
            font-size: 18px;
            margin-bottom: 10px;
            margin-left: 30px;
            color: #003d73; 
        }

        .info-item {
            font-size: 16px;
            margin-bottom: 5px;
            color: #000; 
        }

        .btn-logout {
            display: block;
            width: 100%;
            padding: 10px;
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
            background-color: #004f91; 
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            font-weight: bold;
        }

        .btn-logout:hover {
            background-color: #003d73; 
        }

        button.btn-login {
            cursor: pointer;
            width: 10%;
            height: 45px; 
            border-radius: 20px;
            border: none;
            font-size: 16px;
            background-color: #005f73; 
            color: #FFF;
            font-weight: bold;
            margin-top: 20px;
            margin-left: 20px;
            transition: background-color 0.3s, transform 0.2s;
        }

        button.btn-login:hover {
            background-color: #003f51;
            transform: scale(1.05);
        }

        button.btn-ficha {
            cursor: pointer;
            width: 85px;
            height: 30px; 
            border-radius: 20px;
            border: none;
            font-size: 12px;
            background-color: #005f73; 
            color: #FFF;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s, transform 0.2s;
        }

        button.btn-ficha:hover {
            background-color: #003f51;
            transform: scale(1.05);
        }

        /* Configuração inicial */
        .form-container, #dados-paciente, #procurar-medicos, #minhas-consultas { 
            display: none; 
        }

        /* Estilo da tabela */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #ddd; /* Mantendo bordas sutis */
        }

        th, td {
            padding: 10px;
            text-align: left; /* Alinhamento do conteúdo da tabela */
            border: 1px solid #ddd; 
        }

        thead th {
            background-color: #99bce7; /* Azul mais escuro para destaque do cabeçalho */
            color: #003d73; /* Texto em azul escuro */
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternância de cor para linhas pares */
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff; /* Mantendo linhas ímpares brancas */
        }

    </style>
    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            section.style.display = section.style.display === 'none' || section.style.display === '' ? 'block' : 'none';
        }
    </script>
</head>
<body>
    <h1><?php echo htmlspecialchars($saudacao) . ", " . htmlspecialchars($nome); ?>!</h1>
    <button onclick="toggleSection('dados-paciente')" class="btn-login" >Meus dados</button>
    <div id="dados-paciente">
        <h2>Dados da conta:</h2>
        <ul>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
            <li><strong>CPF:</strong> <?php echo htmlspecialchars($cpf); ?></li>
            <li><strong>Telefone:</strong> <?php echo htmlspecialchars($telefone); ?></li>
            <li><strong>Cidade:</strong> <?php echo htmlspecialchars($cidade); ?></li>
            <li><strong>Bairro:</strong> <?php echo htmlspecialchars($bairro); ?></li>
            <li><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($data_nascimento_formatada); ?></li>
            <li><strong>Gênero:</strong> <?php echo htmlspecialchars($genero); ?></li>
        </ul>
    </div>
    <br>
    <button onclick="toggleSection('procurar-medicos')" class="btn-login" >Procurar médicos</button>
    <div id="procurar-medicos">
    <h2>Procurar Médicos</h2>
    <form id="filterDoctors" method="GET" action="findDoctors.php">
        <label for="especialidade">Especialidade:</label>
        <select name="especialidade" id="especialidade">
            <option value="Psicólogo">Psicólogo</option>
            <option value="Psiquiatra">Psiquiatra</option>
        </select><br><br>

        <label for="cidade">Cidade:</label>
        <input type="text" id="cidade" name="cidade" placeholder="Digite a cidade"><br><br>

        <label for="genero">Gênero:</label>
        <select name="genero" id="genero">
            <option value="masculino">Masculino</option>
            <option value="feminino">Feminino</option>
        </select><br><br>

        <button type="submit" class="btn-ficha" >FindDoctor</button>
    </form>
    </div>

    <br>
    <button onclick="toggleSection('minhas-consultas')" class="btn-login" >Minhas consultas</button>
    <div id="minhas-consultas">
        <h2>Minhas consultas</h2>
        <?php
        include('mySchedules.php')
        ?>
    </div>

    <a href="logoutPacient.php" class="btn-logout" >Sair da Conta</a>
</body>
</html>
