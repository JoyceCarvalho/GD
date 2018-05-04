-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 18-Abr-2018 às 21:50
-- Versão do servidor: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestaodocumentos`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbausencia`
--

CREATE TABLE `tbausencia` (
  `id` int(11) NOT NULL,
  `dia_inicio` datetime DEFAULT NULL,
  `dia_fim` datetime DEFAULT NULL,
  `motivo` text,
  `fk_idusuario` int(11) NOT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbausencia`
--

INSERT INTO `tbausencia` (`id`, `dia_inicio`, `dia_fim`, `motivo`, `fk_idusuario`, `fk_idempresa`) VALUES
(1, '2018-03-23 08:00:00', '2018-03-27 12:00:00', '<p>teste teste mais teste</p>', 9, 2),
(4, '2018-03-28 12:00:00', '2018-03-28 14:00:00', '<p>testando edi&ccedil;&atilde;o novamente</p>', 8, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbcancelamento`
--

CREATE TABLE `tbcancelamento` (
  `id` int(11) NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `data_hora` datetime DEFAULT NULL,
  `fk_iddocumento` int(11) NOT NULL,
  `fk_idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbcargos`
--

CREATE TABLE `tbcargos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `fk_idempresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbcargos`
--

INSERT INTO `tbcargos` (`id`, `titulo`, `fk_idempresa`) VALUES
(1, 'Administrador Master', 1),
(2, 'Coordenador', 2),
(4, 'Coordenador', 4),
(6, 'Coordenador', 1),
(7, 'Operacional I', 1),
(9, 'Administrativo', 2),
(10, 'Operacional II', 2),
(11, 'Administrativo', 4),
(12, 'Financeiro', 2),
(13, 'Geral', 1),
(14, 'Gestão de clientes', 1),
(15, 'Gestão Institucional', 2),
(16, 'Imagem', 2),
(17, 'Qualidade', 2),
(18, 'Coordenador', 5),
(19, 'Coordenador', 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbcompetencias`
--

CREATE TABLE `tbcompetencias` (
  `id` int(11) NOT NULL,
  `tipo` varchar(12) DEFAULT NULL,
  `fk_iddocumento` int(11) DEFAULT NULL,
  `fk_idetapa` int(11) DEFAULT NULL,
  `fk_idusuario` int(11) DEFAULT NULL,
  `fk_idcargo` int(11) DEFAULT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbdocumento`
--

CREATE TABLE `tbdocumento` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `fk_idgrupo` int(11) NOT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbdocumento`
--

INSERT INTO `tbdocumento` (`id`, `titulo`, `fk_idgrupo`, `fk_idempresa`) VALUES
(1, 'ALTERAÇÃO DE ESTATUTO', 1, 2),
(2, 'ATAS EM GERAL', 2, 2),
(4, 'Primeiro teste com etapas', 1, 2),
(5, 'Segundo teste com etapas', 2, 2),
(6, 'Terceiro teste com etapas', 2, 2),
(7, 'Quarto teste com etapas (agora vai)', 2, 2),
(8, 'quinto teste com etapas (agora vai messsmo)', 2, 2),
(10, 'teste', 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbdocumentoetapa`
--

CREATE TABLE `tbdocumentoetapa` (
  `iddocumento` int(11) NOT NULL,
  `idetapa` int(11) NOT NULL,
  `ordem` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbdocumentoetapa`
--

INSERT INTO `tbdocumentoetapa` (`iddocumento`, `idetapa`, `ordem`) VALUES
(1, 3, 1),
(1, 5, 2),
(2, 2, 1),
(2, 3, 2),
(2, 4, 3),
(2, 5, 4),
(4, 3, 1),
(4, 4, 3),
(4, 5, 2),
(5, 2, 1),
(5, 3, 2),
(5, 4, 3),
(6, 2, 2),
(6, 3, 1),
(7, 3, 1),
(8, 2, 1),
(8, 3, 2),
(10, 2, 1),
(10, 3, 2),
(10, 4, 3),
(10, 5, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbdocumentos_cad`
--

CREATE TABLE `tbdocumentos_cad` (
  `id` int(11) NOT NULL,
  `protocolo` varchar(200) DEFAULT NULL,
  `atos` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `prazo` date DEFAULT NULL,
  `fk_iddocumento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbdocumentos_cad`
--

INSERT INTO `tbdocumentos_cad` (`id`, `protocolo`, `atos`, `status`, `prazo`, `fk_iddocumento`) VALUES
(6, 'TESTE', 4, 'Criado', '2018-04-17', 6),
(7, 'LOGDOCUMENTOS', 2, 'Criado', '2018-04-19', 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbempresa`
--

CREATE TABLE `tbempresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cliente_code` varchar(45) DEFAULT NULL,
  `logo_code` varchar(45) DEFAULT NULL,
  `missao` varchar(255) DEFAULT NULL,
  `visao` varchar(255) DEFAULT NULL,
  `valores` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbempresa`
--

INSERT INTO `tbempresa` (`id`, `nome`, `cliente_code`, `logo_code`, `missao`, `visao`, `valores`) VALUES
(1, 'SGT - Gestão e Tecnologia', 'sgtgestaoetecnologia', NULL, 'Proporcionar ferramentas que garantam o acesso a melhores práticas de gestão organizacional e de qualidade', NULL, 'Respeito ao usuário.\r\nRapidez nas soluções.\r\nInovação permantente.'),
(2, 'Txai Desenvolvimento', 'txai', NULL, '<p style=\"text-align: justify;\">Contribuir para a felicidade das pessoas, proporcionando o desenvolvimento humano e empresarial. </p>', '<p style=\"text-align: center;\">Atuar em todos os estado brasileiros</p>', '<p>Amor - amar o que se faz;</p>\r\n<p>Alian&ccedil;a - fortalecimento dos relacionamentos;</p>\r\n<p>Aperfei&ccedil;oamento - cont&iacute;nuo;</p>\r\n<p>Credibilidade - no que se faz;</p>\r\n<p>Qualidade - exc&ecirc;lencia nos servi&ccedil;os;</p>\r\n<p>Lucratividade - para reinvestir;</p>'),
(4, 'Testadora de Software ME', 'teste', NULL, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In eu metus bibendum, porta nisi ut, auctor arcu. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>', '<p>Mauris sed tempor ligula. Nulla fringilla vitae mauris id faucibus. Aenean feugiat erat ac nulla mollis aliquet. Morbi vel felis ex.</p>', '<p>Nullam ac maximus ante, a lacinia leo. Praesent non venenatis augue. Integer eget lobortis risus, vel mollis neque. Aenean convallis a risus et eleifend. Ut accumsan tellus semper mi dignissim egestas. Fusce gravida blandit pulvinar. Vivamus in ante euismod nisi egestas convallis. Mauris condimentum eleifend felis, eu varius justo vehicula nec. Aenean vel egestas dui. Duis convallis risus velit, sit amet pretium felis luctus sed. Vivamus vulputate metus sem, sed condimentum mi rutrum in</p>');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tberros`
--

CREATE TABLE `tberros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tberros_documentos`
--

CREATE TABLE `tberros_documentos` (
  `fk_iderros` int(11) NOT NULL,
  `fk_iddocumentos` int(11) NOT NULL,
  `descricao` text,
  `data_hora` datetime DEFAULT NULL,
  `fk_idusuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbetapa`
--

CREATE TABLE `tbetapa` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbetapa`
--

INSERT INTO `tbetapa` (`id`, `titulo`, `ativo`, `fk_idempresa`) VALUES
(2, 'Desenvolvimento', 1, 2),
(3, 'Registro', 1, 2),
(4, 'Programação', 1, 2),
(5, 'Atendimento', 1, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbferiados`
--

CREATE TABLE `tbferiados` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `dia` date DEFAULT NULL,
  `fk_idempresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbferiados`
--

INSERT INTO `tbferiados` (`id`, `titulo`, `dia`, `fk_idempresa`) VALUES
(1, 'Carnaval', '2018-02-13', 2),
(3, 'Paixão de Cristo', '2018-03-30', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbferias_func`
--

CREATE TABLE `tbferias_func` (
  `id` int(11) NOT NULL,
  `dia_inicio` date DEFAULT NULL,
  `dia_fim` date DEFAULT NULL,
  `fk_idusuario` int(11) NOT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbferias_func`
--

INSERT INTO `tbferias_func` (`id`, `dia_inicio`, `dia_fim`, `fk_idusuario`, `fk_idempresa`) VALUES
(1, '2018-03-28', '2018-04-05', 8, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbgrupo`
--

CREATE TABLE `tbgrupo` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbgrupo`
--

INSERT INTO `tbgrupo` (`id`, `titulo`, `fk_idempresa`) VALUES
(1, 'Setor de Registro', 2),
(2, 'Protesto', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbhorario_trab`
--

CREATE TABLE `tbhorario_trab` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `manha_entrada` time DEFAULT NULL,
  `manha_saida` time DEFAULT NULL,
  `tarde_entrada` time DEFAULT NULL,
  `tarde_saida` time DEFAULT NULL,
  `fk_idempresa` int(11) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbhorario_trab`
--

INSERT INTO `tbhorario_trab` (`id`, `titulo`, `manha_entrada`, `manha_saida`, `tarde_entrada`, `tarde_saida`, `fk_idempresa`, `ativo`) VALUES
(1, 'Regular', '07:00:00', '12:00:00', '13:00:00', '18:00:00', 1, 1),
(2, 'Regular', '07:00:00', '12:00:00', '13:30:00', '18:30:00', 2, 1),
(3, 'Regular', '07:00:00', '12:00:00', '13:00:00', '18:00:00', 4, 1),
(4, 'Meio Turno 1', '07:00:00', '12:00:00', '00:00:00', '00:00:00', 2, 1),
(5, 'Meio Turno 2', '00:00:00', '00:00:00', '12:00:00', '06:00:00', 2, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblog_documentos`
--

CREATE TABLE `tblog_documentos` (
  `id` int(11) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  `data_hora` datetime DEFAULT NULL,
  `ultima_etapa` varchar(5) DEFAULT NULL,
  `usuario` int(11) NOT NULL,
  `etapa` int(11) NOT NULL,
  `documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tblog_documentos`
--

INSERT INTO `tblog_documentos` (`id`, `descricao`, `data_hora`, `ultima_etapa`, `usuario`, `etapa`, `documento`) VALUES
(1, 'CRIADO', '2018-04-10 13:30:54', 'false', 8, 0, 6),
(2, 'CRIADO', '2018-04-11 14:11:19', 'false', 8, 0, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblog_sistema`
--

CREATE TABLE `tblog_sistema` (
  `id` int(11) NOT NULL,
  `usuario` int(11) DEFAULT NULL,
  `mensagem` text,
  `data_hora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbprazoetapa`
--

CREATE TABLE `tbprazoetapa` (
  `id` int(11) NOT NULL,
  `prazo` date DEFAULT NULL,
  `fk_iddocumento` int(11) DEFAULT NULL,
  `fk_idetapas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbprazoetapa`
--

INSERT INTO `tbprazoetapa` (`id`, `prazo`, `fk_iddocumento`, `fk_idetapas`) VALUES
(6, '2018-04-02', 6, 3),
(7, '2018-04-16', 6, 2),
(8, '2018-04-02', 7, 3),
(9, '2018-04-10', 7, 5),
(10, '2018-04-18', 7, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbusuario`
--

CREATE TABLE `tbusuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `usuario` varchar(45) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `fk_idempresa` int(11) NOT NULL,
  `fk_idcargos` int(11) NOT NULL,
  `fk_idhorariotrab` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `tbusuario`
--

INSERT INTO `tbusuario` (`id`, `nome`, `email`, `usuario`, `senha`, `ativo`, `fk_idempresa`, `fk_idcargos`, `fk_idhorariotrab`) VALUES
(1, 'Administrador', 'suporte@sgtgestaoetecnologia.com.br', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 1, 1, 1),
(2, 'Cruz & Feltrin LTDA', 'txai@txaidesenvolvimento.com', 'master', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 2, 2, 2),
(4, 'Testivaldo Junior', 'teste@master.com', 'teste', '2e6f9b0d5885b6010f9167787445617f553a735f', 1, 4, 4, 3),
(6, 'Juvenildo Juvenal da Silva', 'juju@gmail.com', 'juve', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 0, 1, 1, 1),
(7, 'Ana Maria de Jesus', 'ana@gmail.com', 'ana', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 1, 7, 1),
(8, 'Joyce Carvalho', 'desenvolvimento@sgtgestaoetecnologia.com.br', 'joyce', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 1, 2, 10, 4),
(9, 'Mateus Lencina', 'mateus@txaidesenvolvimento.com.br', 'mateus', '8cb2237d0679ca88db6464eac60da96345513964', 1, 2, 17, 2),
(11, 'hhsdhjsdhj\'idtf', 'dasdas@dmwoed.com', 'teste', '2e6f9b0d5885b6010f9167787445617f553a735f', 0, 2, 9, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbausencia`
--
ALTER TABLE `tbausencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbausencia_tbusuario1_idx` (`fk_idusuario`),
  ADD KEY `fk_empresas` (`fk_idempresa`);

--
-- Indexes for table `tbcancelamento`
--
ALTER TABLE `tbcancelamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbcancelamento_tbdocumentos_cad1_idx` (`fk_iddocumento`),
  ADD KEY `fk_tbcancelamento_tbusuario1_idx` (`fk_idusuario`);

--
-- Indexes for table `tbcargos`
--
ALTER TABLE `tbcargos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbcargos_tbempresa1_idx` (`fk_idempresa`);

--
-- Indexes for table `tbcompetencias`
--
ALTER TABLE `tbcompetencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_iddocumento` (`fk_iddocumento`),
  ADD KEY `fk_idetapa` (`fk_idetapa`),
  ADD KEY `fk_idusuario` (`fk_idusuario`),
  ADD KEY `fk_idcargo` (`fk_idcargo`),
  ADD KEY `fk_empresa_competencia` (`fk_idempresa`);

--
-- Indexes for table `tbdocumento`
--
ALTER TABLE `tbdocumento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbdocumento_tbgrupo1_idx` (`fk_idgrupo`),
  ADD KEY `fk_empresa_documento1` (`fk_idempresa`);

--
-- Indexes for table `tbdocumentoetapa`
--
ALTER TABLE `tbdocumentoetapa`
  ADD PRIMARY KEY (`iddocumento`,`idetapa`),
  ADD KEY `fk_tbdocumento_has_tbetapa_tbetapa1_idx` (`idetapa`,`iddocumento`);

--
-- Indexes for table `tbdocumentos_cad`
--
ALTER TABLE `tbdocumentos_cad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbdocumentos_cad_tbdocumento1_idx` (`fk_iddocumento`);

--
-- Indexes for table `tbempresa`
--
ALTER TABLE `tbempresa`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tberros`
--
ALTER TABLE `tberros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tberros_documentos`
--
ALTER TABLE `tberros_documentos`
  ADD PRIMARY KEY (`fk_iderros`,`fk_iddocumentos`),
  ADD KEY `fk_tberros_has_tbdocumentos_cad_tbdocumentos_cad1_idx` (`fk_iddocumentos`),
  ADD KEY `fk_tberros_documentos_tbusuario1_idx` (`fk_idusuario`),
  ADD KEY `fk_tberros_has_tbdocumentos_cad_tberros1_idx` (`fk_iderros`);

--
-- Indexes for table `tbetapa`
--
ALTER TABLE `tbetapa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idempresa` (`fk_idempresa`);

--
-- Indexes for table `tbferiados`
--
ALTER TABLE `tbferiados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbferiados_tbempresa1_idx` (`fk_idempresa`);

--
-- Indexes for table `tbferias_func`
--
ALTER TABLE `tbferias_func`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbferias_func_tbusuario1_idx` (`fk_idusuario`),
  ADD KEY `fk_empresa` (`fk_idempresa`);

--
-- Indexes for table `tbgrupo`
--
ALTER TABLE `tbgrupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idempresa` (`fk_idempresa`);

--
-- Indexes for table `tbhorario_trab`
--
ALTER TABLE `tbhorario_trab`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresa_horario_trab` (`fk_idempresa`);

--
-- Indexes for table `tblog_documentos`
--
ALTER TABLE `tblog_documentos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblog_sistema`
--
ALTER TABLE `tblog_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbprazoetapa`
--
ALTER TABLE `tbprazoetapa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_iddocumento` (`fk_iddocumento`),
  ADD KEY `fk_idetapas` (`fk_idetapas`);

--
-- Indexes for table `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbusuario_tbcargos1_idx` (`fk_idcargos`),
  ADD KEY `fk_tbusuario_tbempresa_idx` (`fk_idempresa`),
  ADD KEY `fk_tbusuario_tbhorario_trab1_idx` (`fk_idhorariotrab`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbausencia`
--
ALTER TABLE `tbausencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbcancelamento`
--
ALTER TABLE `tbcancelamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbcargos`
--
ALTER TABLE `tbcargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `tbcompetencias`
--
ALTER TABLE `tbcompetencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbdocumento`
--
ALTER TABLE `tbdocumento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbdocumentos_cad`
--
ALTER TABLE `tbdocumentos_cad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tbempresa`
--
ALTER TABLE `tbempresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tberros`
--
ALTER TABLE `tberros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbetapa`
--
ALTER TABLE `tbetapa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tbferiados`
--
ALTER TABLE `tbferiados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbferias_func`
--
ALTER TABLE `tbferias_func`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbgrupo`
--
ALTER TABLE `tbgrupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbhorario_trab`
--
ALTER TABLE `tbhorario_trab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tblog_documentos`
--
ALTER TABLE `tblog_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tblog_sistema`
--
ALTER TABLE `tblog_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbprazoetapa`
--
ALTER TABLE `tbprazoetapa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `tbusuario`
--
ALTER TABLE `tbusuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tbausencia`
--
ALTER TABLE `tbausencia`
  ADD CONSTRAINT `fk_empresas` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`),
  ADD CONSTRAINT `fk_tbausencia_tbusuario1` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tbcancelamento`
--
ALTER TABLE `tbcancelamento`
  ADD CONSTRAINT `fk_tbcancelamento_tbdocumentos_cad1` FOREIGN KEY (`fk_iddocumento`) REFERENCES `tbdocumentos_cad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbcancelamento_tbusuario1` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tbcompetencias`
--
ALTER TABLE `tbcompetencias`
  ADD CONSTRAINT `fk_empresa_competencia` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`),
  ADD CONSTRAINT `tbcompetencias_ibfk_1` FOREIGN KEY (`fk_iddocumento`) REFERENCES `tbdocumento` (`id`),
  ADD CONSTRAINT `tbcompetencias_ibfk_2` FOREIGN KEY (`fk_idetapa`) REFERENCES `tbetapa` (`id`),
  ADD CONSTRAINT `tbcompetencias_ibfk_3` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`),
  ADD CONSTRAINT `tbcompetencias_ibfk_4` FOREIGN KEY (`fk_idcargo`) REFERENCES `tbcargos` (`id`);

--
-- Limitadores para a tabela `tbdocumento`
--
ALTER TABLE `tbdocumento`
  ADD CONSTRAINT `fk_empresa_documento1` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbdocumento_tbgrupo1` FOREIGN KEY (`fk_idgrupo`) REFERENCES `tbgrupo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tbdocumentoetapa`
--
ALTER TABLE `tbdocumentoetapa`
  ADD CONSTRAINT `fk_tbdocumento_has_tbetapa_tbdocumento1` FOREIGN KEY (`iddocumento`) REFERENCES `tbdocumento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbdocumento_has_tbetapa_tbetapa1` FOREIGN KEY (`idetapa`) REFERENCES `tbetapa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tbdocumentos_cad`
--
ALTER TABLE `tbdocumentos_cad`
  ADD CONSTRAINT `fk_tbdocumentos_cad_tbdocumento1` FOREIGN KEY (`fk_iddocumento`) REFERENCES `tbdocumento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tberros_documentos`
--
ALTER TABLE `tberros_documentos`
  ADD CONSTRAINT `fk_tberros_documentos_tbusuario1` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tberros_has_tbdocumentos_cad_tbdocumentos_cad1` FOREIGN KEY (`fk_iddocumentos`) REFERENCES `tbdocumentos_cad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tberros_has_tbdocumentos_cad_tberros1` FOREIGN KEY (`fk_iderros`) REFERENCES `tberros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tbetapa`
--
ALTER TABLE `tbetapa`
  ADD CONSTRAINT `tbetapa_ibfk_1` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`);

--
-- Limitadores para a tabela `tbferiados`
--
ALTER TABLE `tbferiados`
  ADD CONSTRAINT `fk_tbferiados_tbempresa1` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tbferias_func`
--
ALTER TABLE `tbferias_func`
  ADD CONSTRAINT `fk_empresa` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`),
  ADD CONSTRAINT `fk_tbferias_func_tbusuario1` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tbgrupo`
--
ALTER TABLE `tbgrupo`
  ADD CONSTRAINT `tbgrupo_ibfk_1` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`);

--
-- Limitadores para a tabela `tbhorario_trab`
--
ALTER TABLE `tbhorario_trab`
  ADD CONSTRAINT `fk_empresa_horario_trab` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tbprazoetapa`
--
ALTER TABLE `tbprazoetapa`
  ADD CONSTRAINT `tbprazoetapa_ibfk_1` FOREIGN KEY (`fk_iddocumento`) REFERENCES `tbdocumentos_cad` (`id`),
  ADD CONSTRAINT `tbprazoetapa_ibfk_2` FOREIGN KEY (`fk_idetapas`) REFERENCES `tbetapa` (`id`);

--
-- Limitadores para a tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD CONSTRAINT `fk_tbusuario_tbcargos1` FOREIGN KEY (`fk_idcargos`) REFERENCES `tbcargos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbusuario_tbempresa` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbusuario_tbhorario_trab1` FOREIGN KEY (`fk_idhorariotrab`) REFERENCES `tbhorario_trab` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
