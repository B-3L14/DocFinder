<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    header("Location: ../loginMedico/doctorLogin.html");
    exit();
}

// Dados do médico
$nome = $_SESSION['nome'];
$email = $_SESSION['email'];
$cpf = $_SESSION['cpf'];
$telefone = $_SESSION['telefone'];
$cidade = $_SESSION['cidade'];
$bairro = $_SESSION['bairro'];
$data_nascimento = $_SESSION['data_nascimento'];
$genero = $_SESSION['genero'];
$especialidade = $_SESSION['especialidade'];

// Formata a data de nascimento para dd/mm/aaaa
$data_nascimento_formatada = date("d/m/Y", strtotime($data_nascimento));

// Determina a saudação com base no gênero
$saudacao = ($genero === "masculino") ? "Bem-vindo" : "Bem-vinda";
$honra = ($genero === "masculino") ? ", Dr. " : ", Dra. ";
include('../db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Médico</title>
    <style>
        .form-container, #dados-medico, #horarios-container { display: none; }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e6f7ff; 
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 800px;
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

        .info-section h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #003d73; 
        }

        .info-item {
            font-size: 16px;
            margin-bottom: 5px;
            color: #000; 
        }

        .schedule {
            margin: 20px 0;
            border: 1px solid #99ccff; 
            border-radius: 8px;
            padding: 15px;
            background-color: #e1f0ff; 
        }

        .schedule h2 {
            font-size: 18px;
            margin-bottom: 10px;
            color: #003d73; 
        }

        /* Estilo das tabelas */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #99ccff; /* Mantendo o estilo do site */
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        thead th {
            background-color: #cce7ff; /* Cabeçalho com cor do site */
            color: #003d73;
        }

        tbody tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternando cores para as linhas */
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        /* Botão de logout */
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
            width: 75px;
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
    </style>

    <script>
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            section.style.display = section.style.display === 'none' || section.style.display === '' ? 'block' : 'none';
        }

        function mostrarFichaPaciente(pacienteData) {
            const paciente = JSON.parse(pacienteData);

            const fichaHtml = `
                <div>
                    <h2>Ficha do Paciente</h2>
                    <p><strong>Nome:</strong> ${paciente.nome}</p>
                    <p><strong>Idade:</strong> ${paciente.idade} anos</p>
                    <p><strong>Gênero:</strong> ${paciente.genero}</p>
                    <p><strong>Telefone:</strong> ${paciente.telefone}</p>
                    <button onclick="fecharFicha()" class="btn-ficha" >Fechar</button>
                </div>
            `;

            const fichaDiv = document.createElement('div');
            fichaDiv.id = 'ficha-paciente';
            fichaDiv.innerHTML = fichaHtml;
            fichaDiv.style.position = 'fixed';
            fichaDiv.style.top = '50%';
            fichaDiv.style.left = '50%';
            fichaDiv.style.transform = 'translate(-50%, -50%)';
            fichaDiv.style.padding = '20px';
            fichaDiv.style.background = '#fff';
            fichaDiv.style.border = '1px solid #ccc';
            fichaDiv.style.boxShadow = '0px 4px 10px rgba(0, 0, 0, 0.1)';

            document.body.appendChild(fichaDiv);
        }

        function fecharFicha() {
            const fichaDiv = document.getElementById('ficha-paciente');
            if (fichaDiv) {
                fichaDiv.remove();
            }
        }


    </script>
</head>
<body>
    <h1><?php echo htmlspecialchars($saudacao) . htmlspecialchars($honra) . htmlspecialchars($nome); ?>!</h1>
    <button onclick="toggleSection('dados-medico')" class="btn-login" >Meus dados</button>

    <div id="dados-medico">
        <h2>Dados da conta:</h2>
        <ul>
            <li><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></li>
            <li><strong>CPF:</strong> <?php echo htmlspecialchars($cpf); ?></li>
            <li><strong>Telefone:</strong> <?php echo htmlspecialchars($telefone); ?></li>
            <li><strong>Cidade:</strong> <?php echo htmlspecialchars($cidade); ?></li>
            <li><strong>Bairro:</strong> <?php echo htmlspecialchars($bairro); ?></li>
            <li><strong>Data de Nascimento:</strong> <?php echo htmlspecialchars($data_nascimento_formatada); ?></li>
            <li><strong>Gênero:</strong> <?php echo htmlspecialchars($genero); ?></li>
            <li><strong>Especialidade:</strong> <?php echo htmlspecialchars($especialidade); ?></li>
        </ul>
    </div>

    <!-- Botão e formulário para configurar horários -->
    <br>
    <button onclick="toggleSection('formContainer')" class="btn-login" >Criar Horários</button>
    <div class="form-container" id="formContainer">
        <form action="gerar-horarios.php" method="post">
            <label for="diasDisponiveis">Dias Disponíveis:</label><br>
            <input type="checkbox" name="diasDisponiveis[]" value="Segunda"> Segunda
            <input type="checkbox" name="diasDisponiveis[]" value="Terça"> Terça
            <input type="checkbox" name="diasDisponiveis[]" value="Quarta"> Quarta
            <input type="checkbox" name="diasDisponiveis[]" value="Quinta"> Quinta
            <input type="checkbox" name="diasDisponiveis[]" value="Sexta"> Sexta
            <input type="checkbox" name="diasDisponiveis[]" value="Sábado"> Sábado
            <input type="checkbox" name="diasDisponiveis[]" value="Domingo"> Domingo<br><br>

            <label for="horaInicio">Hora de Início:</label>
            <input type="time" id="horaInicio" name="horaInicio" required><br><br>

            <label for="horaFim">Hora de Fim:</label>
            <input type="time" id="horaFim" name="horaFim" required><br><br>

            <label for="duracaoConsulta">Duração da Consulta:</label>
            <input type="number" id="duracaoConsulta" name="duracaoConsulta" value="50" required><br><br>

            <label for="horaAlmocoInicio">Início do Almoço:</label>
            <input type="time" id="horaAlmocoInicio" name="horaAlmocoInicio"><br><br>

            <label for="horaAlmocoFim">Fim do Almoço:</label>
            <input type="time" id="horaAlmocoFim" name="horaAlmocoFim"><br><br>

            <input type="submit" value="Gerar Horários">
        </form>
    </div>

    <!-- Tabela de horários -->
    <br>
    <button onclick="toggleSection('horarios-container')" class="btn-login">Meus horários</button>
    <div id="horarios-container">
        <h2>Horários de Atendimento</h2>
        <?php
        include('mostrar-horarios.php');
        ?>
    
    </div>

    <a href="logoutDoctor.php" class="btn-logout">Sair da Conta</a>
</body>
</html>
