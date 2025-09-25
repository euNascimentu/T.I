<?php
require_once 'conexao.php';

$apiKey = "SUA_CHAVE_AQUI"; // Coloque sua chave da API RAWG
$atualizarAposHoras = 24;   // Tempo de cache (em horas)

$sql = "SELECT * FROM Jogo";
$result = $conn->query($sql);
$jogos = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

        $precisaAtualizar = true;

        if (!empty($row['ultimaAtualizacaoApi'])) {
            $ultimaAtualizacao = strtotime($row['ultimaAtualizacaoApi']);
            $agora = time();

            if (($agora - $ultimaAtualizacao) < ($atualizarAposHoras * 3600)) {
                $precisaAtualizar = false; // Ainda válido
            }
        }

        // Só chama API se o cache estiver vencido
        if ($precisaAtualizar) {
            $nomeJogo = urlencode($row['nomeJogo']);
            $url = "https://api.rawg.io/api/games?key={$apiKey}&search={$nomeJogo}";
            $response = @file_get_contents($url);

            if ($response !== false) {
                $data = json_decode($response, true);
                if (!empty($data["results"])) {
                    $apiGame = $data["results"][0];

                    // Atualiza no banco
                    $stmt = $conn->prepare("UPDATE Jogo 
                        SET imagemApi=?, notaMediaApi=?, generoApi=?, descricaoApi=?, ultimaAtualizacaoApi=NOW()
                        WHERE id=?");
                    $stmt->bind_param(
                        "sdssi",
                        $apiGame["background_image"],
                        $apiGame["rating"],
                        !empty($apiGame["genres"]) ? $apiGame["genres"][0]["name"] : $row['generoJogo'],
                        $apiGame["short_screenshots"][0]["image"] ?? '',
                        $row['id']
                    );
                    $stmt->execute();

                    // Atualiza variável local
                    $row['imagemApi'] = $apiGame["background_image"];
                    $row['notaMediaApi'] = $apiGame["rating"];
                    $row['generoApi'] = !empty($apiGame["genres"]) ? $apiGame["genres"][0]["name"] : $row['generoJogo'];
                    $row['descricaoApi'] = $apiGame["short_screenshots"][0]["image"] ?? '';
                }
            }
        }

        // Valores padrão caso falhe
        $row['imagemApi'] = $row['imagemApi'] ?? 'source/sem-imagem.jpg';
        $row['notaMediaApi'] = $row['notaMediaApi'] ?? 'N/A';
        $row['generoApi'] = $row['generoApi'] ?? $row['generoJogo'];
        $row['descricaoApi'] = $row['descricaoApi'] ?? '';

        $jogos[] = $row;
    }
}
?>
