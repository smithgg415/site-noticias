-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/01/2025 às 17:26
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `blog`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `com_codigo` int(11) NOT NULL,
  `usu_codigo` int(11) NOT NULL,
  `not_codigo` int(11) NOT NULL,
  `com_conteudo` text NOT NULL,
  `com_criadoem` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`com_codigo`, `usu_codigo`, `not_codigo`, `com_conteudo`, `com_criadoem`) VALUES
(31, 9, 2, 'Muito triste', '2024-12-29 00:03:24'),
(36, 9, 2, 'qual o tempo que esse gordo fez?', '2024-12-29 15:11:58'),
(37, 9, 9, 'muito triste', '2024-12-29 16:11:05'),
(38, 9, 9, 'parabéns', '2024-12-29 16:15:49'),
(42, 9, 9, 'oioioi', '2024-12-29 16:49:48'),
(43, 9, 9, 'Bem feito', '2024-12-29 17:11:54'),
(44, 12, 8, 'belo boi, feia piscina', '2024-12-29 17:43:05'),
(45, 12, 8, 'se fudeu', '2024-12-29 17:43:26'),
(47, 9, 8, 'tadinho', '2024-12-29 17:57:18'),
(49, 27, 9, 'vai trouxa', '2025-01-03 22:17:21'),
(50, 9, 8, 'Parabéns', '2025-01-03 23:19:36'),
(51, 9, 9, 'Quando isso?', '2025-01-03 23:28:25'),
(53, 9, 9, 'Morreu', '2025-01-03 23:33:14'),
(55, 10, 22, 'App Muito Bom!', '2025-01-05 20:31:46'),
(56, 10, 22, 'Qual foi a empresa que desenvolveu?', '2025-01-05 20:34:31'),
(57, 9, 22, 'Gostei do app', '2025-01-05 20:35:05'),
(59, 9, 22, 'Beach tennis é esporte de viado', '2025-01-06 02:12:21'),
(60, 9, 22, 'vai porra', '2025-01-06 03:30:31');

-- --------------------------------------------------------

--
-- Estrutura para tabela `noticias`
--

CREATE TABLE `noticias` (
  `not_codigo` int(11) NOT NULL,
  `not_titulo` varchar(200) NOT NULL,
  `not_conteudo` text NOT NULL,
  `not_autor_codigo` int(11) NOT NULL,
  `not_publicado_em` timestamp NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `not_imagem` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `noticias`
--

INSERT INTO `noticias` (`not_codigo`, `not_titulo`, `not_conteudo`, `not_autor_codigo`, `not_publicado_em`, `created_at`, `updated_at`, `not_imagem`) VALUES
(2, 'Pedro Conquista o Título de Campeão da Competição de Cachorro Quente em Presidente Venceslau', 'Na última edição da Competição de Cachorro Quente, realizada no centro da cidade, Pedro, um jovem de 28 anos, se consagrou o grande campeão do evento. A disputa, que contou com a participação de dezenas de competidores e atraiu um público de centenas de pessoas, foi marcada por uma competição acirrada, mas Pedro não deu chance para os adversários, superando todos com impressionante velocidade e habilidade.', 9, '2024-12-28 18:21:21', '2024-12-05 01:09:51', '2025-01-05 20:07:05', 'https://us.123rf.com/450wm/poznyakov/poznyakov1706/poznyakov170600012/79593578-falha-na-dieta-do-homem-gordo-comendo-cachorro-quente-de-fast-food-no-prato-caf%C3%A9-da-manh%C3%A3-para-a.jpg'),
(8, 'A Revolução do Ensino a Distância: Novas Perspectivas para a Educação Global', 'O ensino a distância (EAD) foi uma das grandes transformações que a educação passou nos últimos anos, acelerada pela necessidade de distanciamento social durante a pandemia. A tecnologia tem permitido que alunos de todo o mundo tenham acesso a cursos e programas de graduação sem sair de casa. Plataformas de aprendizado online estão se aprimorando, oferecendo recursos como aulas interativas, avaliações em tempo real e interação com professores e colegas. O EAD representa uma grande oportunidade para democratizar a educação, permitindo que mais pessoas, em diferentes partes do mundo, tenham acesso a cursos de qualidade.', 9, '2024-12-28 18:55:40', '2024-12-28 18:55:40', '2025-01-05 20:04:48', 'https://www.estudiosite.com.br/img/post/uOgXm23bLoeYNMcOjqrf64d68d8be675d.png'),
(9, ' Novo Parque Municipal de Diversões é Inaugurado em P.V.', 'Na manhã deste sábado, a cidade recebeu um dos maiores investimentos de infraestrutura de lazer dos últimos anos: o novo Parque Municipal de Diversões. Localizado no coração da cidade, o parque promete ser um local de diversão para toda a família.\r\n\r\nA inauguração contou com a presença de autoridades locais e centenas de visitantes que puderam explorar as primeiras atrações do local. O parque possui montanhas-russas, carrosséis, praça de alimentação e até uma área voltada para esportes radicais. “Este parque é um marco para a nossa cidade, trazendo entretenimento e gerando empregos para a população local”, afirmou o prefeito durante a cerimônia.', 9, '2025-01-05 20:08:29', '2024-12-28 19:27:43', '2025-01-05 20:21:42', 'https://blogger.googleusercontent.com/img/a/AVvXsEgrAa2bf8v5_PsGd8KY_eovvZUK9NArCn7Bl-MjOJ4ywOjsrWn-QWq8BWKAS3qWnPsaCvqTLGEyL-qjz9Rv4wwERVlqw3TOb8sRiDLlJyIRLgR7u9Oix8JVV1jsLCQPb7a7r85xOUWw0AkUKYgce_VN8eLfjc6rSeadPtJx4eCWIlN9x22fmsNb7Sor'),
(11, 'Avanços na Medicina: O Futuro dos Tratamentos Genéticos', 'A medicina genética está avançando rapidamente, proporcionando tratamentos inovadores que podem transformar a forma como lidamos com doenças hereditárias e complexas. Com a edição de genes e terapias celulares, especialistas acreditam que em breve será possível corrigir mutações genéticas antes que elas se manifestem, prevenindo condições como câncer e doenças cardiovasculares. No entanto, questões éticas e regulatórias ainda precisam ser resolvidas para que essas terapias se tornem acessíveis para todos.', 9, '2025-01-05 19:58:48', '2025-01-04 00:04:16', '2025-01-05 20:21:52', 'https://d2gizm3iyflxh9.cloudfront.net/artmed_blog/43946d10-696f-4a19-a37e-9022d935f555.jpg'),
(12, 'Desafios e Oportunidades do Setor de Energias Renováveis no Brasil', 'O Brasil, com seu vasto potencial natural, tem se destacado como um dos principais mercados para energias renováveis na América Latina. A energia solar, eólica e hidrelétrica têm ganhado terreno nos últimos anos, proporcionando uma alternativa mais sustentável e econômica à geração de energia tradicional. No entanto, o setor ainda enfrenta desafios como falta de infraestrutura e políticas públicas mais eficazes para promover o desenvolvimento de novas tecnologias. Mesmo assim, os investimentos em energias limpas continuam a crescer, e as perspectivas para o futuro são otimistas.', 9, '2025-01-05 20:04:16', '2025-01-04 00:04:16', '2025-01-05 20:22:33', 'https://pactoenergia.com.br/wp-content/uploads/2024/07/energia-renovavel-desafios-oportunidades-1-1024x683.jpg'),
(13, 'A Ascensão do Comércio Eletrônico: O Que Esperar para o Futuro', 'O comércio eletrônico tem experimentado um crescimento acelerado, impulsionado pela conveniência e pelo aumento das compras online. As empresas estão investindo cada vez mais em tecnologias como inteligência artificial, realidade aumentada e sistemas de pagamento seguros para melhorar a experiência do cliente. Com a pandemia, a transição para o digital se intensificou, e agora mais consumidores estão se acostumando com a ideia de fazer compras no conforto de suas casas. O futuro promete um comércio ainda mais integrado, com entregas mais rápidas e personalizadas.', 9, '2025-01-05 20:03:51', '2025-01-04 00:04:16', '2025-01-05 20:21:28', 'https://blog.bling.com.br/wp-content/uploads/2020/07/ecommerce-futuro-varejo-1024x576.webp?x99143'),
(14, 'Transformação Digital no Setor Financeiro: O Papel das Fintechs', 'As fintechs têm revolucionado o setor financeiro, oferecendo soluções inovadoras e mais acessíveis para consumidores e empresas. Desde pagamentos digitais até serviços bancários, essas empresas estão democratizando o acesso ao crédito e facilitando a gestão financeira. O crescimento das fintechs é impulsionado pela busca por serviços mais rápidos, seguros e com taxas mais competitivas, especialmente em um mundo cada vez mais digital.', 9, '2025-01-05 20:03:00', '2025-01-04 00:04:16', '2025-01-05 20:22:24', 'https://meta.com.br/wp-content/uploads/2023/01/META-Tranformacao-Digital-Sistema-Financeiro.jpg'),
(15, 'O Crescimento do Mercado de Alimentos Orgânicos no Brasil', 'O Brasil tem visto um aumento significativo no consumo de alimentos orgânicos, impulsionado por uma maior conscientização sobre a saúde e o meio ambiente. Consumidores estão cada vez mais preocupados com a origem dos alimentos que consomem, buscando opções livres de agrotóxicos e produzidas de forma sustentável. Supermercados, feiras e lojas especializadas estão atendendo a essa demanda crescente, oferecendo uma ampla variedade de produtos orgânicos.', 9, '2025-01-05 20:01:37', '2025-01-04 00:04:16', '2025-01-05 20:21:38', 'https://blog.sensix.ag/wp-content/uploads/2023/10/gettyimages-183744328.webp'),
(16, 'A Revolução do Trabalho Remoto: O Futuro do Home Office', 'O trabalho remoto, que ganhou força durante a pandemia de COVID-19, promete continuar sendo uma parte importante do mercado de trabalho. Empresas de diferentes setores estão adotando modelos híbridos ou totalmente remotos, oferecendo maior flexibilidade e qualidade de vida para os funcionários. Ferramentas de colaboração digital, como videoconferências, ferramentas de gerenciamento de projetos e chatbots, têm facilitado a comunicação e a produtividade, tornando o home office mais eficiente do que nunca.', 9, '2025-01-05 20:00:26', '2025-01-04 00:04:16', '2025-01-05 20:22:14', 'https://faculdadeith.com.br/wp-content/uploads/2024/05/home-office.webp'),
(17, 'Como a Inteligência Artificial Está Revolucionando a Educação', 'A inteligência artificial (IA) tem se mostrado uma ferramenta poderosa no campo da educação, criando soluções personalizadas para alunos de todas as idades. Plataformas baseadas em IA podem analisar o progresso de cada aluno e sugerir materiais de estudo específicos, ajudando a otimizar o aprendizado. Além disso, a IA está facilitando a criação de experiências interativas e práticas, como assistentes virtuais, que podem responder perguntas em tempo real e melhorar o engajamento dos estudantes.', 9, '2025-01-05 19:59:51', '2025-01-04 00:04:16', '2025-01-05 20:21:48', 'https://colegiosantaedwiges.com.br/blog/wp-content/uploads/2024/03/blog-foto-2.jpg'),
(18, 'Como a Sustentabilidade Está Moldando o Mercado Imobiliário', 'O mercado imobiliário tem passado por uma grande transformação nos últimos anos, com uma crescente demanda por imóveis sustentáveis. De edifícios de baixo consumo de energia até soluções ecológicas para construção, os consumidores estão cada vez mais preocupados com o impacto ambiental das suas escolhas. Arquitetos e construtoras estão investindo em tecnologias verdes e práticas sustentáveis para reduzir a pegada de carbono e aumentar a eficiência energética dos imóveis.', 9, '2025-01-05 19:57:24', '2025-01-04 00:04:16', '2025-01-05 20:22:10', 'https://f1ciaimobiliaria.com.br/wp-content/uploads/2024/08/Tendencias-de-Sustentabilidade-no-Mercado-Imobiliario-de-Florianopolis-2-1024x683.jpg'),
(19, 'Tecnologia 5G Chega ao Brasil: O Futuro da Conectividade', 'A chegada do 5G no Brasil promete transformar a forma como nos conectamos e interagimos com o mundo. Com uma velocidade de internet significativamente mais alta, o 5G permitirá uma experiência mais fluida para navegar, jogar online e até para aplicações de realidade aumentada. As cidades brasileiras começam a receber a infraestrutura necessária para a nova tecnologia, o que abre portas para novos negócios e avanços em áreas como saúde, educação e transporte.', 9, '2025-01-05 19:56:52', '2025-01-04 00:04:16', '2025-01-05 20:21:57', 'https://embarcados.com.br/wp-content/uploads/2022/10/imagem-de-destaque-27-850x510.png'),
(20, 'Explorando Novos Destinos Turísticos no Brasil', 'O Brasil, conhecido por suas praias paradisíacas e grandes centros urbanos, está se destacando também como destino para turistas que buscam experiências únicas em suas viagens. Cidades como Bonito (MS), Alter do Chão (PA) e Lençóis Maranhenses (MA) estão ganhando destaque no mercado turístico internacional. Além da natureza exuberante, esses destinos oferecem uma imersão cultural rica, além de atividades de ecoturismo que atraem viajantes de todo o mundo.', 9, '2025-01-05 19:58:06', '2025-01-04 00:04:16', '2025-01-05 20:22:19', 'https://magazine.zarpo.com.br/wp-content/uploads/2018/09/25-pontos-turisticos-do-brasil-para-conhecer-agora-mesmo-2.jpg'),
(21, 'Giacomelli Dev\'s leva Prêmio como Melhor desenvolvedora de Software da região de Pres. Prudente', 'No último fim de semana, a Giacomelli Dev\'s, uma das empresas mais inovadoras da área de tecnologia, foi premiada como a Melhor Desenvolvedora de Software da região de Presidente Prudente. A cerimônia de premiação aconteceu durante o evento “Tech Awards 2025”, que reuniu as principais empresas e profissionais da indústria de TI.\r\n\r\nA Giacomelli Dev\'s, especializada no desenvolvimento de soluções personalizadas de software para empresas de diversos setores, foi reconhecida pela sua excelência em inovação, qualidade de atendimento e impacto no mercado local. \"Este prêmio é o resultado do esforço contínuo da nossa equipe em proporcionar soluções tecnológicas que realmente fazem a diferença para nossos clientes\", afirmou Luís Felipe Giacomelli Rodrigues, CEO e Fundador da empresa.', 9, '2025-01-05 20:11:22', '2025-01-05 20:11:22', '2025-01-05 20:14:41', 'https://nutriflow.netlify.app/logos/giacomellilogo.png'),
(22, 'Lançamento do app BeachDuo é feito em Piquerobi', 'A partir de agora, os amantes de beach tennis têm uma nova maneira de se conectar e melhorar suas experiências na praia! O BeachDuo, aplicativo inovador desenvolvido especialmente para a comunidade do esporte, chega ao mercado para transformar a maneira como os jogadores encontram parceiros, marcam partidas e acompanham seu desempenho.\r\n\r\nCom uma interface amigável e funcionalidades inteligentes, o BeachDuo permite que os usuários criem perfis personalizados, busquem parceiros de jogo com base em nível técnico, localização e disponibilidade, e agendem partidas diretamente pelo app. Além disso, a plataforma oferece recursos para registrar estatísticas de jogos, competições, e até mesmo resultados, facilitando o acompanhamento da evolução de cada jogador.', 9, '2025-01-05 20:17:44', '2025-01-05 20:17:44', '2025-01-06 01:41:33', 'https://sportlife.com.br/wp-content/uploads/2023/05/Beach-Tennis.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `usu_codigo` int(11) NOT NULL,
  `usu_nome` varchar(100) NOT NULL,
  `usu_email` varchar(150) NOT NULL,
  `usu_senha` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usu_nivel` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usu_codigo`, `usu_nome`, `usu_email`, `usu_senha`, `created_at`, `updated_at`, `usu_nivel`) VALUES
(9, 'Luís Felipe Giacomelli Rodrigues', 'lfgiacomellirodrigues@gmail.com', '$2y$10$OWD2sFPrOi.Vs2p4ylxdcu5Bn2T.rrCSJe3UXQtujFmFUnJ6HHFJa', '2024-12-05 00:54:17', '2025-01-03 22:18:56', 'admin'),
(10, 'Ana Paula de Oliveira Giacomelli', 'paulagiacomelli28@hotmail.com', '$2y$10$rMUeFYRZkplozqPiARqz1OzzlJeWbf0/IsYh3DFRz0MXejvHZGtMS', '2024-12-05 01:44:15', '2024-12-29 15:23:22', 'usuario'),
(11, 'Ana paula de oliveira giacomelli', 'paulagiacomelli@hotmail.com', '$2y$10$CKv.uFMD5qE1keBe/sfD.eZNAnjFxqLc.IW5QfeKWYkzfz4q6EWdO', '2024-12-05 02:04:45', '2025-01-03 03:46:16', 'admin'),
(12, 'Fernando', 'luisfernandomachadorodrigues4@gmail.com', '$2y$10$xKGwmOGgGQaY9x9.4rEi0.1YwPXFo36rPPJcqHRVeWiTk9Fi56ndG', '2024-12-05 02:06:28', '2025-01-03 23:20:31', 'usuario'),
(13, 'João Silva', 'joao.silva@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(14, 'Maria Oliveira', 'maria.oliveira@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(15, 'Pedro Santos', 'pedro.santos@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(16, 'Ana Costa', 'ana.costa@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(17, 'Lucas Almeida', 'lucas.almeida@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(18, 'Fernanda Lima', 'fernanda.lima@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(19, 'Bruno Pereira', 'bruno.pereira@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(20, 'Carla Rocha', 'carla.rocha@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(21, 'Rafael Mendes', 'rafael.mendes@email.com', 'senha123', '2024-12-30 02:26:49', '2024-12-30 02:26:49', 'usuario'),
(22, 'Juliana Ferreira', 'juliana.ferreira@email.com', '$2y$10$j4I0GapCSbd0cZumq.4gJ.8FgfKMeVfYL1f2rDqtg25Bzu4KhDfIy', '2024-12-30 02:26:49', '2025-01-03 03:42:24', 'admin'),
(26, 'smith', 'pedo@gmail.com', '$2y$10$bR0b83aCsymqJ6Wq0p8Kce8qxFN4IzQRmIf6bNOCFExJEnZdDDMuG', '2025-01-03 03:58:04', '2025-01-03 03:58:04', 'usuario'),
(27, 'smith', 'smith@email.com', '$2y$10$9goqsIzIKvY4KBQXOYEBxuqbrRcdH4rI6CpYmfnhoxd5ZHqIIOmBC', '2025-01-03 22:17:01', '2025-01-03 22:18:27', 'admin');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`com_codigo`),
  ADD KEY `usu_codigo` (`usu_codigo`),
  ADD KEY `not_codigo` (`not_codigo`);

--
-- Índices de tabela `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`not_codigo`),
  ADD KEY `not_autor_codigo` (`not_autor_codigo`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usu_codigo`),
  ADD UNIQUE KEY `usu_email` (`usu_email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `com_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT de tabela `noticias`
--
ALTER TABLE `noticias`
  MODIFY `not_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usu_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`usu_codigo`) REFERENCES `usuarios` (`usu_codigo`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`not_codigo`) REFERENCES `noticias` (`not_codigo`) ON DELETE CASCADE;

--
-- Restrições para tabelas `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `noticias_ibfk_1` FOREIGN KEY (`not_autor_codigo`) REFERENCES `usuarios` (`usu_codigo`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
