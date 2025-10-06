<?php
// Conexão com o banco de dados
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "PIBD";
$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
$conn->set_charset("utf8");

// Obter termo de busca
$termo = isset($_POST['termo']) ? $_POST['termo'] : '';

if (empty($termo)) {
    echo '<div class="sem-resultados">Digite um nome para buscar</div>';
    exit();
}

// Buscar usuários no banco
$sql = "SELECT idUsuario, nomeUsuario, bioUsuario, tipoUsuario 
        FROM Usuario 
        WHERE nomeUsuario LIKE ? 
        ORDER BY nomeUsuario 
        LIMIT 10";
$stmt = $conn->prepare($sql);
$termoBusca = "%" . $termo . "%";
$stmt->bind_param("s", $termoBusca);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($user = $result->fetch_assoc()) {
        $avatarTexto = strtoupper(substr($user['nomeUsuario'], 0, 2));
        $tipoUsuario = $user['tipoUsuario'] == 1 ? 'Admin' : 'Usuário';
        $classeTipo = $user['tipoUsuario'] == 1 ? 'admin' : '';
        
        echo '
        <div class="usuario-card" data-user-id="' . $user['idUsuario'] . '">
            <div class="usuario-avatar">' . $avatarTexto . '</div>
            <div class="usuario-info">
                <div class="usuario-nome">' . htmlspecialchars($user['nomeUsuario']) . '</div>
                <div class="usuario-bio">' . htmlspecialchars($user['bioUsuario'] ?? 'Sem biografia') . '</div>
                <span class="usuario-tipo ' . $classeTipo . '">' . $tipoUsuario . '</span>
            </div>
            <button type="button" class="ver-perfil-btn" data-user-id="' . $user['idUsuario'] . '">Ver Perfil</button>
        </div>';
    }
} else {
    echo '<div class="sem-resultados">Nenhum usuário encontrado para "' . htmlspecialchars($termo) . '"</div>';
}

$stmt->close();
$conn->close();
?>