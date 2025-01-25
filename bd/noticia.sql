-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/01/2025 às 06:26
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
-- Banco de dados: `noticia`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `anuncios`
--

CREATE TABLE `anuncios` (
  `anu_codigo` int(11) NOT NULL,
  `anu_imagem` varchar(255) NOT NULL,
  `anu_linkacesso` varchar(255) DEFAULT NULL,
  `anu_nome` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `anuncios`
--

INSERT INTO `anuncios` (`anu_codigo`, `anu_imagem`, `anu_linkacesso`, `anu_nome`) VALUES
(2, 'https://www.portalbueno.com.br/uploads/images/2023/12/oftalmologoa-castilho.gif', 'https://oftalmocastro.com.br/', 'OftalmoCastro'),
(3, 'https://www.portalbueno.com.br/uploads/images/2024/11/andorinha12802825832988388520.jfif', 'https://api.whatsapp.com/send/?phone=551821044111&text=ANDORINHA&type=phone_number&app_absent=0', 'Andorinha Viagens'),
(4, 'https://www.portalbueno.com.br/uploads/images/2024/11/img-8564.gif', 'https://api.whatsapp.com/send/?phone=5518991055885&text=TRINUS&type=phone_number&app_absent=0', 'Trinus Odontologia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `com_codigo` int(11) NOT NULL,
  `usu_codigo` int(11) NOT NULL,
  `not_codigo` int(11) NOT NULL,
  `com_conteudo` text NOT NULL,
  `com_criadoem` timestamp NOT NULL DEFAULT current_timestamp(),
  `com_updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `comentarios`
--

INSERT INTO `comentarios` (`com_codigo`, `usu_codigo`, `not_codigo`, `com_conteudo`, `com_criadoem`, `com_updated_at`) VALUES
(88, 19, 23, 'Empresa sempre se destacando!', '2025-01-23 01:54:34', '2025-01-23 01:54:34'),
(90, 9, 23, 'Empresa de grande potêncial!!!!!!', '2025-01-24 17:54:19', '2025-01-24 18:41:34');

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
(23, 'Giacomelli Dev\'s leva Prêmio como Melhor desenvolvedora de Software da região de Pres. Prudente', 'Presidente Prudente, SP – A Giacomelli Dev\'s, startup especializada em desenvolvimento de software e soluções digitais, conquistou o prêmio de Melhor Desenvolvedora de Software da Região de Presidente Prudente. O reconhecimento foi entregue em uma cerimônia realizada na última semana, destacando a empresa como referência em inovação e tecnologia.\r\n\r\nO prêmio reflete o compromisso da Giacomelli Dev\'s com a excelência no desenvolvimento de sistemas, aplicativos e plataformas web, atendendo clientes de diversos segmentos com soluções eficientes e personalizadas. A startup, que vem ganhando notoriedade no setor, tem se destacado pelo uso de tecnologias avançadas e um atendimento diferenciado.\r\n\r\n\"Esse reconhecimento é fruto de muito trabalho, dedicação e da confiança que nossos clientes depositam em nós. Nosso objetivo é continuar inovando e entregando soluções que realmente fazem a diferença\", afirmou o fundador da empresa.\r\n\r\nCom esse prêmio, a Giacomelli Dev\'s reafirma sua posição como uma das principais desenvolvedoras da região, impulsionando o mercado de tecnologia e fortalecendo o ecossistema digital local.', 9, '2025-01-18 22:25:54', '2025-01-18 22:25:54', '2025-01-18 22:25:54', 'https://nutriflow.netlify.app/logos/giacomellilogo.png'),
(24, 'Governo Anuncia Poupança para Licenciatura: Novo Programa de Incentivo para Futuros Professores', 'O governo lançou nesta terça-feira (14) o programa Pé-de-Meia Licenciatura, uma iniciativa para atrair jovens ingressos no ensino superior para a docência, reduzir a evasão nos cursos de licenciatura e fortalecer a formação de professores no Brasil.\r\nOs estudantes devem atender aos seguintes critérios:\r\nNota igual ou superior a 650 pontos no Enem que desejam seguir carreira no magistério em escolas públicas;\r\nIngresso em curso de licenciatura pelo Sisu, Prouni ou Fies Social.\r\nE para manter o benefício e acessar a poupança:\r\nCursar o mínimo de créditos obrigatórios por período;\r\nTer desempenho satisfatório em 75% dos créditos matriculados a cada semestre;\r\nIngressar em uma rede pública de ensino em até cinco anos após a conclusão do curso.\r\n\r\nFonte: G1;\r\nFoto: Internet;', 9, '2025-01-25 04:41:08', '2025-01-25 04:41:08', '2025-01-25 04:56:57', 'https://portal1.iff.edu.br/nossos-campi/reitoria/noticias/mec-lanca-pe-de-meia-para-quem-quer-ser-professor/pe-de-meia-imagem.jpg/@@images/8fd3b735-2885-47b5-b740-f0fdf65832fd.jpeg'),
(25, 'Incêndio destrói casa de madeira em Presidente Venceslau', 'De acordo com o Corpo de Bombeiros, o incêndio ocorreu por volta das 5h45, por razões ainda desconhecidas. A casa foi totalmente consumida pelo fogo, destruindo móveis, utensílios domésticos, roupas e documentos. Além disso, não houve vítimas. Ainda conforme a corporação, os trabalhos já foram encerrados.\r\n\r\nFonte: G1;\r\nFoto: Portal Bueno;', 9, '2025-01-25 04:46:05', '2025-01-25 04:46:05', '2025-01-25 04:46:05', 'https://s2-g1.glbimg.com/u3GY2w9tMKlKtokxgwLixMM-88c=/0x0:1280x960/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_59edd422c0c84a879bd37670ae4f538a/internal_photos/bs/2025/U/d/CzLAvxS9CC4s3qbxtALQ/fc220f5a-af6e-49e1-b5bd-c56ef88a72a3.jfif'),
(26, 'Jovem morador de Dracena morre em guerra na Ucrânia', 'O jovem Igor Fagundes Lamas, filho do comerciante Júnior do PUB Coktail, de Dracena, foi confirmado como uma das vítimas da guerra entre Rússia e a Ucrânia.\r\nInicialmente, o rapaz foi dado como desaparecido, ao lado de outros dois jovens brasileiros, mas pouco tempo depois, os óbitos foram confirmados pelo Consulado brasileiro na Ucrânia.\r\nUma postagem no perfil “Militar.Ru” no Twitter relatou o “abatimento” de Igor, além de dois outros jovens brasileiros, residentes em Ourinhos e no Paraná, e de uma vítima portuguesa.\r\nDe acordo com publicações de familiares em redes sociais, Igor decidiu ir para a guerra sem consultar sua família, que se posicionava contrária pelo risco iminente de morte.\r\nAlém de família em Dracena, Igor também possui mais familiares na região, como a avó Ray Silva Lopes que reside em Pacaembu.\r\nAté o momento não há informações sobre o traslado do corpo de Igor para o Brasil.\r\nFonte: Portal Bueno;\r\nFoto: Portal Bueno;\r\n', 9, '2025-01-25 04:54:10', '2025-01-25 04:54:10', '2025-01-25 04:54:10', 'https://www.portalbueno.com.br/uploads/cache/24-01-2025-042656-133-1000-9a9f0f85.png'),
(27, 'Caminhão pega fogo na Rodovia Raposo Tavares, em Presidente Prudente', 'Conforme a Concessionária de Rodovias Cart, a ocorrência foi registrada no km 566,773 da rodovia, sentido oeste.\r\nA ocorrência teve início às 16h35 e mobilizou equipes da Cart, do Corpo de Bombeiros e da Polícia Militar Rodoviária.\r\nDe acordo com o Corpo de Bombeiros, o motorista teve uma parada cardiorrespiratória e foi levado ao Hospital Regional (HR).\r\nSegundo a concessionária, o incidente resultou no encaminhamento do homem ao hospital, e as faixas de rolamento (1 e 2) e o acostamento precisaram ser interditados temporariamente para atendimento.\r\n\r\nFonte: G1;\r\nFoto: G1;', 9, '2025-01-25 05:01:52', '2025-01-25 05:01:52', '2025-01-25 05:04:26', 'https://s2-g1.glbimg.com/65mcsQPSSVYoKipcbgPM0ZB8uLs=/0x0:828x655/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_59edd422c0c84a879bd37670ae4f538a/internal_photos/bs/2025/j/m/NrsRn0S9SkPH2nCpxYbQ/whatsapp-image-2025-01-24-at-16.53.26.jpeg'),
(28, 'Operação Atroz prende casal suspeito de praticar crime de extorsão em Dracena', 'Conforme a Polícia Civil, com o apoio operacional do Grupo de Operações Especiais (GOE), os agentes iniciaram as investigações após um homem, de 33 anos, registrar um Boletim de Ocorrência na última segunda-feira (20), relatando ter sido extorquido e agredido por um casal em uma suposta cobrança de dívida relacionada a drogas.\r\nA vítima apresentou lesões na cabeça, que afirmou terem sido causadas por golpes de coronha desferidos pelo agressor, além de ferimentos nas costas e um corte no braço esquerdo.\r\n\r\nFonte: G1;\r\nFoto: Polícia Civil;', 9, '2025-01-25 05:03:46', '2025-01-25 05:03:46', '2025-01-25 05:03:46', 'https://s2-g1.glbimg.com/aXzuYsjZwfTmXN6kPIbwIi5MqxI=/0x0:853x652/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_59edd422c0c84a879bd37670ae4f538a/internal_photos/bs/2025/2/B/9RJmjQTF2GDqO73ByWjw/arma-policia.jpg'),
(29, 'Azul suspende operação em 12 cidades brasileiras', 'A companhia aérea Azul informou nesta sexta-feira (24) a suspensão total de suas operações em 12 cidades brasileiras a partir do dia 10 de março. Os voos serão encerrados em Campos e Cabo Frio (RJ); Correia Pinto (SC); Crateús, São Benedito, Sobral e Iguatú (CE); Mossoró (RN); São Raimundo Nonato e Parnaíba (PI); Rio Verde (GO); e Barreirinha (MA).\r\nA companhia alegou, em nota à imprensa, uma série de fatores, \"que vão desde o aumento nos custos operacionais da aviação, impactados pela crise global na cadeia de suprimentos e a alta do dólar, somadas às questões de disponibilidade de frota e de ajustes de oferta e demanda\".\r\nMinistro diz que fusão entre Azul e Gol pode reduzir preço de passagem.\r\nAzul e dona da Gol assinam acordo para avaliar fusão entre as aéreas.\r\nPelas mesmas razões, haverá redução de oferta e readequações da operação da Azul em outras localidades.  Os voos para Fernando de Noronha (PE) serão operados somente a partir de Recife. Também a partir de 10 de março, os voos saindo de Juazeiro do Norte (CE) passarão a ter como destino o aeroporto de Viracopos, em Campinas (SP), principal hub da companhia. As operações no aeroporto de Caruaru (PE) serão readequadas, devido à baixa ocupação. Segundo a Azul, os voos passarão a ser realizados por aeronaves Cessna Grand Caravan, com capacidade para nove clientes.\r\n\"As mudanças fazem parte do planejamento operacional da Companhia, e os Clientes impactados estão sendo comunicados previamente e receberão a assistência necessária, conforme prevê a resolução 400 da Agência Nacional de Aviação Civil (Anac)\".\r\nNa semana passada, a Azul e a Abras, dona da Gol, outra companhia aérea nacional, assinaram um memorando de entendimento para iniciar as negociações para uma fusão. Caso a união se concretize, a nova empresa concentrará 60% do mercado aéreo no país.\r\n\r\nFonte: Portal Bueno;', 9, '2025-01-25 05:10:29', '2025-01-25 05:10:29', '2025-01-25 05:10:29', 'https://www.portalbueno.com.br/uploads/cache/azul-suspende-operacao-em-12-cidades-brasileiras-1000-dd001c02.jpg'),
(30, 'Meta destinará até US$ 65 bi para impulsionar metas de IA em 2025', 'A Meta planeja gastar entre US$ 60 bilhões e US$ 65 bilhões este ano para desenvolver infraestrutura de inteligência artificial (IA), disse o presidente-executivo da companhia, Mark Zuckerberg, nesta sexta-feira (24).\r\nComo parte do investimento, a Meta construirá um data center de mais de 2 gigawatts, que seria grande o suficiente para cobrir uma parte significativa de Manhattan. A empresa — um dos maiores clientes dos cobiçados chips de inteligência artificial da Nvidia — planeja terminar o ano com mais de 1,3 milhão de processadores gráficos.\r\n\r\nFonte: CNN', 9, '2025-01-25 05:22:44', '2025-01-25 05:22:44', '2025-01-25 05:22:44', 'https://www.cnnbrasil.com.br/wp-content/uploads/sites/12/2025/01/meta.jpg?w=928'),
(31, 'Para Brasil, aceno de Trump reduz risco de guerra comercial', 'O aceno do presidente americano Donald Trump de uma diálogo harmonioso com a China reduziu as expectativas de deflagração de uma nova guerra comercial.\r\nEm participação no Fórum Econômico Mundial, em Davos, Trump reconheceu a necessidade de renegociar tarifas, mas baixou o tom quanto à imposição de novas alíquotas sobre todos os produtos importados indiscriminadamente.\r\nDurante sua campanha, Trump falou em uma sobretaxa de 60% para mercadorias da China e de 10% para os demais fornecedores.\r\n\r\nFonte: CNN;', 9, '2025-01-25 05:24:52', '2025-01-25 05:24:52', '2025-01-25 05:24:52', 'https://s2-g1.glbimg.com/fwE8I4r7HvplWvMsgIszi_RFrH0=/0x0:3456x2304/984x0/smart/filters:strip_icc()/i.s3.glbimg.com/v1/AUTH_59edd422c0c84a879bd37670ae4f538a/internal_photos/bs/2025/E/0/j5r9gBSHmExb4F3kyWkg/2025-01-20t195042z-1475809835-rc2udcav17z7-rtrmadp-3-usa-trump-inauguration.jpg');

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
  `usu_nivel` varchar(50) DEFAULT NULL,
  `usu_foto` varchar(255) DEFAULT NULL,
  `usu_foto_de_fundo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`usu_codigo`, `usu_nome`, `usu_email`, `usu_senha`, `created_at`, `updated_at`, `usu_nivel`, `usu_foto`, `usu_foto_de_fundo`) VALUES
(9, 'Luís Felipe Giacomelli Rodrigues', 'lfgiacomellirodrigues@gmail.com', '$2y$10$w22Bk2TIzvxGCshtXTCHB..pkobg/cq2dAMTQZdmPBhcdOI.nQx6a', '2024-12-05 00:54:17', '2025-01-24 04:02:41', 'admin', 'https://github.com/smithgg415.png', 'https://thumbs.dreamstime.com/b/wallpaper-t%C3%A9cnico-do-c%C3%B3digo-de-programa%C3%A7%C3%A3o-hacker-rolagem-verde-anima%C3%A7%C3%A3o-fundo-291708501.jpg'),
(11, 'Ana paula de oliveira giacomelli', 'paulagiacomelli@hotmail.com', '$2y$10$CKv.uFMD5qE1keBe/sfD.eZNAnjFxqLc.IW5QfeKWYkzfz4q6EWdO', '2024-12-05 02:04:45', '2025-01-17 00:41:44', 'admin', NULL, NULL),
(12, 'Fernando', 'luisfernandomachadorodrigues4@gmail.com', '$2y$10$xKGwmOGgGQaY9x9.4rEi0.1YwPXFo36rPPJcqHRVeWiTk9Fi56ndG', '2024-12-05 02:06:28', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(13, 'João Silva', 'joao.silva@email.com', 'senha123', '2024-12-30 02:26:49', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(14, 'Maria Oliveira', 'maria.oliveira@email.com', 'senha123', '2024-12-30 02:26:49', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(15, 'Pedro Santos', 'pedro.santos@email.com', 'senha123', '2024-12-30 02:26:49', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(17, 'Lucas Almeida', 'lucas.almeida@email.com', 'senha123', '2024-12-30 02:26:49', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(18, 'Fernanda Lima', 'fernanda.lima@email.com', 'senha123', '2024-12-30 02:26:49', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(19, 'Bruno Pereira', 'bruno.pereira@email.com', '$2y$10$FAI0Vg9WieoNkkGCM0E2gu4NAEOzTxY5WvJQhZUUNuNurSmX8KPPC', '2024-12-30 02:26:49', '2025-01-23 01:54:47', 'usuario', 'https://www.creativefabrica.com/wp-content/uploads/2023/06/29/Profile-Photo-Professional-Business-Man-In-Suit-73311054-1.png', 'https://wallpapers.com/images/hd/accounting-background-b5jiod0mfbngcb7w.jpg'),
(20, 'Carla Rocha', 'carla.rocha@email.com', '$2y$10$dFCFUDXP.R5oIxqacvYRxuuzwRmjYREm09USqf8Ze0IdNyaAd/y0C', '2024-12-30 02:26:49', '2025-01-23 01:51:13', 'usuario', NULL, NULL),
(21, 'Rafael Mendes', 'rafael.mendes@email.com', 'senha123', '2024-12-30 02:26:49', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(22, 'Juliana Ferreira', 'juliana.ferreira@email.com', '$2y$10$j4I0GapCSbd0cZumq.4gJ.8FgfKMeVfYL1f2rDqtg25Bzu4KhDfIy', '2024-12-30 02:26:49', '2025-01-17 00:41:44', 'admin', NULL, NULL),
(26, 'smith', 'pedo@gmail.com', '$2y$10$bR0b83aCsymqJ6Wq0p8Kce8qxFN4IzQRmIf6bNOCFExJEnZdDDMuG', '2025-01-03 03:58:04', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(27, 'Pedro Álvares Cabral', 'smith@email.com', '$2y$10$VZaoDfMxqdSsplMVPXGICOr/3hOCd8XbL7Gy/PPbjuI3NiO/oOtAm', '2025-01-03 22:17:01', '2025-01-17 06:18:04', 'admin', NULL, NULL),
(28, 'Luís Felipe Giacomelli Rodrigues', 'emailteste@email.com', '$2y$10$K1bbs5RNkkkuzTS/9XSHD.i/q5oyP8aXjfcgtGn5vZT6FEtd2mM.O', '2025-01-17 07:31:06', '2025-01-17 21:28:42', 'usuário', NULL, NULL),
(29, 'Paula giacomelli', 'paula@email.com', '$2y$10$fk1TB7q7fLt3J1RZZT2MUOReQGMLtylkwPBrHz452dXDz67wTfw6.', '2025-01-17 21:32:58', '2025-01-17 21:53:00', 'admin', NULL, NULL),
(30, 'Ana Paula de Oliveira Giacomelli', 'paula1@gmail.com', '$2y$10$N/9yQAvjYeKuzOioyGny7ehO4/8RyWRqLz6UpPC2XKTWinuYMS2r6', '2025-01-17 21:56:21', '2025-01-17 21:56:55', 'admin', NULL, NULL),
(31, 'Pedro Costa', 'pedrocosta@email.com', '$2y$10$WnJMWHd2vQH..LYPAKtRPu2PxPbBRVrKP73y42z8a5vhzS22F8Nbm', '2025-01-18 22:01:12', '2025-01-18 22:01:12', 'admin', NULL, NULL),
(32, 'Ana Paula de Oliveira Giacomelli', 'paulagiacomelli@email.com', '$2y$10$0L6OgVHbCJ/bpfvutm7uluUylNEXVR6NAOgIgeV6XTSNbiheDu8Ue', '2025-01-18 22:02:13', '2025-01-23 01:51:34', 'usuario', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`anu_codigo`);

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
-- AUTO_INCREMENT de tabela `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `anu_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `com_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de tabela `noticias`
--
ALTER TABLE `noticias`
  MODIFY `not_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usu_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
