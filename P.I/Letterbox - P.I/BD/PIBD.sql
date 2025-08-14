drop database PIBD;
create database PIBD;
use PIBD;
-- Usuário
CREATE TABLE Usuario (
    idUsuario INT PRIMARY KEY AUTO_INCREMENT,
    nomeUsuario VARCHAR(100) NOT NULL,
    emailUsuario VARCHAR(100) UNIQUE NOT NULL,
    senhaUsuario VARCHAR(100) NOT NULL,
    tipoUsuario INT,
    bioUsuario TEXT
);
-- Jogo
CREATE TABLE Jogo (
    idJogo INT PRIMARY KEY AUTO_INCREMENT,
    nomeJogo VARCHAR(200) NOT NULL,
    generoJogo VARCHAR(100),
    desenvolvedoraJogo VARCHAR(100),
    descricaoJogo TEXT
);
-- Avaliação
CREATE TABLE Avaliacao (
    idAvaliacao INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT,
    idJogo INT,
    notaAvaliacao INT CHECK (notaAvaliacao BETWEEN 0 AND 10),
    descricaoAvaliacao TEXT,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario) ON DELETE CASCADE,
    FOREIGN KEY (idJogo) REFERENCES Jogo(idJogo) ON DELETE CASCADE
);
-- Favorito
CREATE TABLE Favorito (
    idFavorito INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL,
    idJogo INT NOT NULL,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario) ON DELETE CASCADE,
    FOREIGN KEY (idJogo) REFERENCES Jogo(idJogo) ON DELETE CASCADE
);
-- Seguidor
CREATE TABLE Seguidor (
    idSeguimento INT PRIMARY KEY AUTO_INCREMENT,
    idSeguidor INT NOT NULL,
    idSeguido INT NOT NULL,
    FOREIGN KEY (idSeguidor) REFERENCES Usuario(idUsuario) ON DELETE CASCADE,
    FOREIGN KEY (idSeguido) REFERENCES Usuario(idUsuario) ON DELETE CASCADE
);
-- Report
CREATE TABLE Report (
    idReport INT PRIMARY KEY AUTO_INCREMENT,
    idUsuario INT NOT NULL, 
    idAvaliacao INT NOT NULL,    
    motivo TEXT NOT NULL,         
    dataReport DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUsuario) REFERENCES Usuario(idUsuario) ON DELETE CASCADE,
    FOREIGN KEY (idAvaliacao) REFERENCES Avaliacao(idAvaliacao) ON DELETE CASCADE
);
select * from Jogo;
INSERT INTO Jogo (nomeJogo, generoJogo, desenvolvedoraJogo, descricaoJogo) VALUES
('The Legend of Zelda: Breath of the Wild', 'Aventura / Ação', 'Nintendo', 'Explore um vasto mundo aberto como Link em sua jornada para salvar Hyrule.'),
('God of War (2018)', 'Ação / Aventura', 'Santa Monica Studio', 'Kratos e seu filho Atreus enfrentam os deuses nórdicos em uma jornada emocional e brutal.'),
('Red Dead Redemption 2', 'Ação / Mundo Aberto', 'Rockstar Games', 'Viva como um fora-da-lei no Velho Oeste em uma narrativa profunda e cinematográfica.'),
('The Witcher 3: Wild Hunt', 'RPG / Aventura', 'CD Projekt RED', 'Geralt de Rívia parte em uma missão para encontrar sua filha adotiva, enfrentando monstros e escolhas difíceis.'),
('Minecraft', 'Sandbox / Construção', 'Mojang Studios', 'Construa, explore e sobreviva em um mundo pixelado gerado proceduralmente.'),
('Hollow Knight', 'Metroidvania / Ação', 'Team Cherry', 'Explore um reino subterrâneo misterioso cheio de insetos e segredos.'),
('Stardew Valley', 'Simulação / Fazenda', 'ConcernedApe', 'Construa sua fazenda, conheça moradores e viva em um vilarejo encantador.'),
('Dark Souls III', 'Ação / RPG', 'FromSoftware', 'Enfrente inimigos desafiadores em um mundo sombrio e devastado.'),
('Celeste', 'Plataforma', 'Matt Makes Games', 'Ajude Madeline a escalar a montanha Celeste em um jogo de plataforma emocional e preciso.'),
('Hades', 'Roguelike / Ação', 'Supergiant Games', 'Escape do submundo como Zagreus em combates rápidos e narrativa envolvente.'),
('Super Mario Odyssey', 'Plataforma / Aventura', 'Nintendo', 'Mario viaja por reinos diversos para salvar a Princesa Peach e enfrentar Bowser.'),
('Among Us', 'Multiplayer / Dedução Social', 'Innersloth', 'Trabalhe em equipe para completar tarefas... ou sabote tudo como impostor.'),
('Resident Evil 4', 'Terror / Ação', 'Capcom', 'Leon S. Kennedy investiga o desaparecimento da filha do presidente em um vilarejo sombrio.'),
('Grand Theft Auto V', 'Mundo Aberto / Ação', 'Rockstar Games', 'Três criminosos unem forças em assaltos audaciosos em Los Santos.'),
('Portal 2', 'Puzzle / Ficção Científica', 'Valve', 'Resolva puzzles com portais em uma narrativa cheia de humor e surpresas.');

-- Mais jogos

INSERT INTO Jogo (nomeJogo, generoJogo, desenvolvedoraJogo, descricaoJogo) VALUES
('Sekiro: Shadows Die Twice', 'Ação / Aventura / Soulslike', 'FromSoftware', 'Jogo intenso de ação com foco em parry e stealth no Japão feudal.'),
('Bloodborne', 'Ação / RPG / Terror', 'FromSoftware', 'Explore uma cidade gótica infestada por monstros e pesadelos.'),
('DOOM Eternal', 'FPS / Ação', 'id Software', 'Destrua demônios em combates frenéticos com armas pesadas.'),
('Cyberpunk 2077', 'RPG / Ação / Futurista', 'CD Projekt RED', 'Mergulhe em uma metrópole futurista como um mercenário cibernético.'),
('Final Fantasy VII Remake', 'RPG / Ação', 'Square Enix', 'Remake do clássico com gráficos modernos e combate em tempo real.'),
('Final Fantasy XV', 'RPG / Mundo Aberto', 'Square Enix', 'Acompanhe Noctis e seus amigos em uma jornada real pelo reino.'),
('Final Fantasy X', 'RPG', 'Square Enix', 'Viaje com Tidus e Yuna por Spira em uma história emocionante.'),
('Assassin\'s Creed Valhalla', 'Ação / Aventura / Histórico', 'Ubisoft', 'Viva como um viking explorando e invadindo a Inglaterra antiga.'),
('Assassin\'s Creed Odyssey', 'Ação / RPG', 'Ubisoft', 'Explore a Grécia Antiga em uma aventura com escolhas impactantes.'),
('Assassin\'s Creed Origins', 'Ação / Aventura', 'Ubisoft', 'Viaje ao Egito Antigo como o primeiro assassino.'),
('Far Cry 5', 'FPS / Ação / Mundo Aberto', 'Ubisoft', 'Lidere a resistência contra um culto religioso nos EUA.'),
('Far Cry 6', 'FPS / Ação / Revolução', 'Ubisoft', 'Derrube um ditador brutal na ilha fictícia de Yara.'),
('Death Stranding', 'Aventura / Entrega / Sci-fi', 'Kojima Productions', 'Reconecte uma América pós-apocalíptica em uma narrativa única.'),
('Detroit: Become Human', 'Drama Interativo / Sci-fi', 'Quantic Dream', 'Controle androides em uma história com múltiplos caminhos.'),
('Until Dawn', 'Terror / Escolhas / Drama', 'Supermassive Games', 'Sobreviva em uma cabana nas montanhas com múltiplas mortes possíveis.'),
('Life is Strange', 'Aventura / Drama / Escolhas', 'Dontnod Entertainment', 'Max descobre que pode voltar no tempo e tenta mudar o futuro.'),
('Life is Strange: Before the Storm', 'Aventura / Prequel', 'Deck Nine', 'Conheça a história de Chloe antes dos eventos do primeiro jogo.'),
('Life is Strange 2', 'Aventura / Drama / Escolhas', 'Dontnod Entertainment', 'Dois irmãos fogem e tentam sobreviver em meio a adversidades.'),
('The Last of Us', 'Ação / Drama / Zumbis', 'Naughty Dog', 'Joel e Ellie enfrentam um mundo devastado por uma infecção mortal.'),
('The Last of Us Part II', 'Ação / Drama / Vingança', 'Naughty Dog', 'Ellie busca vingança em uma jornada emocional e brutal.'),
('Spider-Man (PS4)', 'Ação / Aventura / Super-herói', 'Insomniac Games', 'Viva como Peter Parker em Nova York enfrentando vilões clássicos.'),
('Spider-Man: Miles Morales', 'Ação / Aventura', 'Insomniac Games', 'Assuma o papel de Miles como o novo herói do Queens.'),
('It Takes Two', 'Coop / Puzzle / Aventura', 'Hazelight Studios', 'Um casal transforma-se em bonecos e precisa cooperar para voltar ao normal.'),
('A Way Out', 'Coop / Ação / Fuga', 'Hazelight Studios', 'Dois presos devem trabalhar juntos para escapar e sobreviver.'),
('Overcooked! 2', 'Coop / Caos na Cozinha', 'Ghost Town Games', 'Gerencie uma cozinha com amigos em situações insanas e divertidas.'),
('Cuphead', 'Plataforma / Tiro / Retrô', 'Studio MDHR', 'Um jogo desafiador com estética de desenhos dos anos 30.'),
('Terraria', 'Aventura / Sandbox', 'Re-Logic', 'Explore, construa, lute e cave em um mundo 2D infinito.'),
('Don`t Starve', 'Sobrevivência / Exploração', 'Klei Entertainment', 'Sobreviva em um mundo sombrio e estranho com recursos limitados.'),
('Slay the Spire', 'Deckbuilding / Roguelike', 'MegaCrit', 'Suba a torre enfrentando inimigos com cartas estratégicas.'),
('Dead Cells', 'Roguelike / Ação / Metroidvania', 'Motion Twin', 'Combate fluido e rápido com mapa procedural e upgrades.'),
('Monster Hunter: World', 'Ação / Coop / RPG', 'Capcom', 'Cace monstros gigantes em mapas vastos com armas diversas.'),
('Monster Hunter Rise', 'Ação / Coop / RPG', 'Capcom', 'Versão mais ágil e verticalizada da famosa franquia de caçadas.'),
('Fall Guys', 'Battle Royale / Party Game', 'Mediatonic', 'Compita em provas malucas com 60 jogadores em busca da coroa.'),
('Valorant', 'FPS / Competitivo / Tático', 'Riot Games', 'FPS tático com habilidades únicas e estratégia por equipe.'),
('League of Legends', 'MOBA / Estratégia / Online', 'Riot Games', '5x5 em batalhas épicas de torres e campeões com habilidades únicas.'),
('Teamfight Tactics', 'Auto Chess / Estratégia', 'Riot Games', 'Monte composições e lute automaticamente contra outros jogadores.'),
('Fortnite', 'Battle Royale / Construção', 'Epic Games', 'Último sobrevivente em um mapa em constante mudança.'),
('Rocket League', 'Futebol com Carros', 'Psyonix', 'Jogue futebol com carros turbo em partidas frenéticas.'),
('Apex Legends', 'Battle Royale / FPS', 'Respawn Entertainment', 'Times de 3 jogadores disputam para sobreviver com habilidades únicas.'),
('PUBG: Battlegrounds', 'Battle Royale / Realista', 'PUBG Corporation', 'Um dos primeiros grandes sucessos do gênero battle royale.'),
('Escape from Tarkov', 'FPS / Simulação / Hardcore', 'Battlestate Games', 'Simulação intensa de combate e extração em mapas militares.'),
('Rainbow Six Siege', 'FPS / Estratégia / Online', 'Ubisoft', '5x5 tático com destruição de ambientes e operadores únicos.'),
('FIFA 24', 'Esporte / Futebol', 'EA Sports', 'Simulação de futebol com times, jogadores e modos realistas.'),
('eFootball 2024', 'Esporte / Futebol', 'Konami', 'Versão gratuita do tradicional PES com atualizações online.'),
('NBA 2K24', 'Esporte / Basquete', '2K Sports', 'Realismo total nas quadras de basquete com modo carreira.'),
('Gran Turismo 7', 'Corrida / Simulação', 'Polyphony Digital', 'Simulador de carros realista com centenas de veículos.'),
('Forza Horizon 5', 'Corrida / Mundo Aberto', 'Playground Games', 'Corra em um México detalhado com clima dinâmico e liberdade total.'),
('The Sims 4', 'Simulação / Vida', 'Maxis', 'Crie personagens, construa casas e viva histórias únicas.'),
('Planet Zoo', 'Simulação / Zoológico', 'Frontier Developments', 'Construa e gerencie um zoológico com detalhes realistas.'),
('Cities: Skylines', 'Simulação / Construção / Cidades', 'Colossal Order', 'Gerencie todos os aspectos de uma cidade moderna e em crescimento.');

-- Usuários

INSERT INTO Usuario (nomeUsuario, emailUsuario, senhaUsuario, tipoUsuario, bioUsuario)
VALUES 
('Pita', 'pita@gmail.com', 'senha123', 1, 'Apaixonado por todas.'),
('Filipe', 'filipe@gmail.com', 'senha456', 1, 'Amante de jogos de tiro e eSports.'),
('FefasTheRunner', 'fefas@gmail.com', 'senha789', 1, 'Nao me enche o saco se nao sera baleado.'),
('Abysss', 'kaua@gmail.com', 'kauasemn', 2, 'Gosto de pokemon'),
('JoJo', 'joao@gmail.com', 'jooj', 2, 'Adoro jogos de terror') ;
 
INSERT INTO Avaliacao (idUsuario, idJogo, notaAvaliacao, descricaoAvaliacao)
VALUES (1, 1, 9, 'Excelente narrativa e jogabilidade imersiva!');
 
INSERT INTO Favorito (idUsuario, idJogo)
VALUES (2, 1);
 
INSERT INTO Seguidor (idSeguidor, idSeguido)
VALUES (3, 1);
 
INSERT INTO Report (idUsuario, idAvaliacao, motivo)
VALUES (4, 1, 'Comentário ofensivo sobre outros jogadores.');