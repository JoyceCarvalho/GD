-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 24/05/2018 às 16:38
-- Versão do servidor: 10.1.24-MariaDB
-- Versão do PHP: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gestaodocumentos`
--


-- --------------------------------------------------------

--
-- Estrutura para tabela `tbempresa`
--

CREATE TABLE `tbempresa` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cliente_code` varchar(45) DEFAULT NULL,
  `logo_code` varchar(45) DEFAULT NULL,
  `missao` varchar(255) DEFAULT NULL,
  `visao` varchar(255) DEFAULT NULL,
  `valores` text,
  `ativo` int(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbcargos`
--

CREATE TABLE `tbcargos` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `fk_idempresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbhorario_trab`
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

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbusuario`
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

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbgrupo`
--

CREATE TABLE `tbgrupo` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbausencia`
--

CREATE TABLE `tbausencia` (
  `id` int(11) NOT NULL,
  `dia_inicio` datetime DEFAULT NULL,
  `dia_fim` datetime DEFAULT NULL,
  `motivo` text,
  `fk_idusuario` int(11) NOT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbetapa`
--

CREATE TABLE `tbetapa` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `ativo` int(11) DEFAULT '1',
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbdocumento`
--

CREATE TABLE `tbdocumento` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) DEFAULT NULL,
  `fk_idgrupo` int(11) NOT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbdocumentoetapa`
--

CREATE TABLE `tbdocumentoetapa` (
  `iddocumento` int(11) NOT NULL,
  `idetapa` int(11) NOT NULL,
  `ordem` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbdocumentos_cad`
--

CREATE TABLE `tbdocumentos_cad` (
  `id` int(11) NOT NULL,
  `protocolo` varchar(200) DEFAULT NULL,
  `atos` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `prazo` date DEFAULT NULL,
  `fk_iddocumento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbcancelamento`
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
-- Estrutura para tabela `tbcompetencias`
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
-- Estrutura para tabela `tberros`
--

CREATE TABLE `tberros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tberros_documentos`
--

CREATE TABLE `tberros_documentos` (
  `fk_iderros` int(11) NOT NULL,
  `fk_iddocumentos` int(11) NOT NULL,
  `descricao` text,
  `data_hora` datetime DEFAULT NULL,
  `fk_idusuario` int(11) DEFAULT NULL,
  `fk_idetapa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbferiados`
--

CREATE TABLE `tbferiados` (
  `id` int(11) NOT NULL,
  `titulo` varchar(100) DEFAULT NULL,
  `dia` date DEFAULT NULL,
  `fk_idempresa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbferias_func`
--

CREATE TABLE `tbferias_func` (
  `id` int(11) NOT NULL,
  `dia_inicio` date DEFAULT NULL,
  `dia_fim` date DEFAULT NULL,
  `fk_idusuario` int(11) NOT NULL,
  `fk_idempresa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblog_documentos`
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

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblog_documentos_tempo`
--

CREATE TABLE `tblog_documentos_tempo` (
  `id` int(11) NOT NULL,
  `id_protocolo` int(11) DEFAULT NULL,
  `id_etapa` int(11) DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_fim` datetime DEFAULT NULL,
  `total_minutos` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tblog_sistema`
--

CREATE TABLE `tblog_sistema` (
  `id` int(11) NOT NULL,
  `usuario` int(11) DEFAULT NULL,
  `mensagem` text,
  `data_hora` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbprazoetapa`
--

CREATE TABLE `tbprazoetapa` (
  `id` int(11) NOT NULL,
  `prazo` date DEFAULT NULL,
  `fk_iddocumento` int(11) DEFAULT NULL,
  `fk_idetapas` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tbtimer`
--

CREATE TABLE `tbtimer` (
  `id` int(11) NOT NULL,
  `fk_iddoccad` int(11) DEFAULT NULL,
  `fk_idetapa` int(11) DEFAULT NULL,
  `action` varchar(5) DEFAULT NULL,
  `timestamp` int(10) UNSIGNED DEFAULT NULL,
  `fk_idusuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices de tabelas apagadas
--

--
-- Índices de tabela `tbausencia`
--
ALTER TABLE `tbausencia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbausencia_tbusuario1_idx` (`fk_idusuario`),
  ADD KEY `fk_empresas` (`fk_idempresa`);

--
-- Índices de tabela `tbcancelamento`
--
ALTER TABLE `tbcancelamento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbcancelamento_tbdocumentos_cad1_idx` (`fk_iddocumento`),
  ADD KEY `fk_tbcancelamento_tbusuario1_idx` (`fk_idusuario`);

--
-- Índices de tabela `tbcargos`
--
ALTER TABLE `tbcargos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbcargos_tbempresa1_idx` (`fk_idempresa`);

--
-- Índices de tabela `tbcompetencias`
--
ALTER TABLE `tbcompetencias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbdocumento`
--
ALTER TABLE `tbdocumento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbdocumento_tbgrupo1_idx` (`fk_idgrupo`),
  ADD KEY `fk_empresa_documento1` (`fk_idempresa`);

--
-- Índices de tabela `tbdocumentoetapa`
--
ALTER TABLE `tbdocumentoetapa`
  ADD PRIMARY KEY (`iddocumento`,`idetapa`),
  ADD KEY `fk_tbdocumento_has_tbetapa_tbetapa1_idx` (`idetapa`,`iddocumento`);

--
-- Índices de tabela `tbdocumentos_cad`
--
ALTER TABLE `tbdocumentos_cad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbdocumentos_cad_tbdocumento1_idx` (`fk_iddocumento`);

--
-- Índices de tabela `tbempresa`
--
ALTER TABLE `tbempresa`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tberros`
--
ALTER TABLE `tberros`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tberros_documentos`
--
ALTER TABLE `tberros_documentos`
  ADD PRIMARY KEY (`fk_iderros`,`fk_iddocumentos`),
  ADD KEY `fk_tberros_has_tbdocumentos_cad_tbdocumentos_cad1_idx` (`fk_iddocumentos`),
  ADD KEY `fk_tberros_documentos_tbusuario1_idx` (`fk_idusuario`),
  ADD KEY `fk_tberros_documentos_tbetapa1_idx` (`fk_idetapa`),
  ADD KEY `fk_tberros_has_tbdocumentos_cad_tberros1_idx` (`fk_iderros`);

--
-- Índices de tabela `tbetapa`
--
ALTER TABLE `tbetapa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idempresa` (`fk_idempresa`);

--
-- Índices de tabela `tbferiados`
--
ALTER TABLE `tbferiados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbferiados_tbempresa1_idx` (`fk_idempresa`);

--
-- Índices de tabela `tbferias_func`
--
ALTER TABLE `tbferias_func`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbferias_func_tbusuario1_idx` (`fk_idusuario`),
  ADD KEY `fk_empresa` (`fk_idempresa`);

--
-- Índices de tabela `tbgrupo`
--
ALTER TABLE `tbgrupo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_idempresa` (`fk_idempresa`);

--
-- Índices de tabela `tbhorario_trab`
--
ALTER TABLE `tbhorario_trab`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_empresa_horario_trab` (`fk_idempresa`);

--
-- Índices de tabela `tblog_documentos`
--
ALTER TABLE `tblog_documentos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tblog_documentos_tempo`
--
ALTER TABLE `tblog_documentos_tempo`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tblog_sistema`
--
ALTER TABLE `tblog_sistema`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tbprazoetapa`
--
ALTER TABLE `tbprazoetapa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_iddocumento` (`fk_iddocumento`),
  ADD KEY `fk_idetapas` (`fk_idetapas`);

--
-- Índices de tabela `tbtimer`
--
ALTER TABLE `tbtimer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_iddoccad` (`fk_iddoccad`),
  ADD KEY `fk_idetapa` (`fk_idetapa`),
  ADD KEY `fk_idusuario` (`fk_idusuario`);

--
-- Índices de tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tbusuario_tbcargos1_idx` (`fk_idcargos`),
  ADD KEY `fk_tbusuario_tbempresa_idx` (`fk_idempresa`),
  ADD KEY `fk_tbusuario_tbhorario_trab1_idx` (`fk_idhorariotrab`);

--
-- AUTO_INCREMENT de tabelas apagadas
--

--
-- AUTO_INCREMENT de tabela `tbausencia`
--
ALTER TABLE `tbausencia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbcancelamento`
--
ALTER TABLE `tbcancelamento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbcargos`
--
ALTER TABLE `tbcargos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbcompetencias`
--
ALTER TABLE `tbcompetencias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbdocumento`
--
ALTER TABLE `tbdocumento`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbdocumentos_cad`
--
ALTER TABLE `tbdocumentos_cad`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbempresa`
--
ALTER TABLE `tbempresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tberros`
--
ALTER TABLE `tberros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbetapa`
--
ALTER TABLE `tbetapa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbferiados`
--
ALTER TABLE `tbferiados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbferias_func`
--
ALTER TABLE `tbferias_func`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbgrupo`
--
ALTER TABLE `tbgrupo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbhorario_trab`
--
ALTER TABLE `tbhorario_trab`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tblog_documentos`
--
ALTER TABLE `tblog_documentos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tblog_documentos_tempo`
--
ALTER TABLE `tblog_documentos_tempo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tblog_sistema`
--
ALTER TABLE `tblog_sistema`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbprazoetapa`
--
ALTER TABLE `tbprazoetapa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbtimer`
--
ALTER TABLE `tbtimer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Restrições para dumps de tabelas
--

--
-- Restrições para tabelas `tbausencia`
--
ALTER TABLE `tbausencia`
  ADD CONSTRAINT `fk_empresas` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`),
  ADD CONSTRAINT `fk_tbausencia_tbusuario1` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbcancelamento`
--
ALTER TABLE `tbcancelamento`
  ADD CONSTRAINT `fk_tbcancelamento_tbdocumentos_cad1` FOREIGN KEY (`fk_iddocumento`) REFERENCES `tbdocumentos_cad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbcancelamento_tbusuario1` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbdocumento`
--
ALTER TABLE `tbdocumento`
  ADD CONSTRAINT `fk_empresa_documento1` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbdocumento_tbgrupo1` FOREIGN KEY (`fk_idgrupo`) REFERENCES `tbgrupo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbdocumentoetapa`
--
ALTER TABLE `tbdocumentoetapa`
  ADD CONSTRAINT `fk_tbdocumento_has_tbetapa_tbdocumento1` FOREIGN KEY (`iddocumento`) REFERENCES `tbdocumento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbdocumento_has_tbetapa_tbetapa1` FOREIGN KEY (`idetapa`) REFERENCES `tbetapa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbdocumentos_cad`
--
ALTER TABLE `tbdocumentos_cad`
  ADD CONSTRAINT `fk_tbdocumentos_cad_tbdocumento1` FOREIGN KEY (`fk_iddocumento`) REFERENCES `tbdocumento` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tberros_documentos`
--
ALTER TABLE `tberros_documentos`
  ADD CONSTRAINT `fk_tberros_documentos_tbusuario1` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tberros_documentos_tbetapa1` FOREIGN KEY (`fk_idetapa`) REFERENCES `tbetapa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tberros_has_tbdocumentos_cad_tbdocumentos_cad1` FOREIGN KEY (`fk_iddocumentos`) REFERENCES `tbdocumentos_cad` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tberros_has_tbdocumentos_cad_tberros1` FOREIGN KEY (`fk_iderros`) REFERENCES `tberros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbetapa`
--
ALTER TABLE `tbetapa`
  ADD CONSTRAINT `tbetapa_ibfk_1` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`);

--
-- Restrições para tabelas `tbferiados`
--
ALTER TABLE `tbferiados`
  ADD CONSTRAINT `fk_tbferiados_tbempresa1` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbferias_func`
--
ALTER TABLE `tbferias_func`
  ADD CONSTRAINT `fk_empresa` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`),
  ADD CONSTRAINT `fk_tbferias_func_tbusuario1` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbgrupo`
--
ALTER TABLE `tbgrupo`
  ADD CONSTRAINT `tbgrupo_ibfk_1` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`);

--
-- Restrições para tabelas `tbhorario_trab`
--
ALTER TABLE `tbhorario_trab`
  ADD CONSTRAINT `fk_empresa_horario_trab` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `tbprazoetapa`
--
ALTER TABLE `tbprazoetapa`
  ADD CONSTRAINT `tbprazoetapa_ibfk_1` FOREIGN KEY (`fk_iddocumento`) REFERENCES `tbdocumentos_cad` (`id`),
  ADD CONSTRAINT `tbprazoetapa_ibfk_2` FOREIGN KEY (`fk_idetapas`) REFERENCES `tbetapa` (`id`);

--
-- Restrições para tabelas `tbtimer`
--
ALTER TABLE `tbtimer`
  ADD CONSTRAINT `tbtimer_ibfk_1` FOREIGN KEY (`fk_iddoccad`) REFERENCES `tbdocumentos_cad` (`id`),
  ADD CONSTRAINT `tbtimer_ibfk_2` FOREIGN KEY (`fk_idetapa`) REFERENCES `tbetapa` (`id`),
  ADD CONSTRAINT `tbtimer_ibfk_3` FOREIGN KEY (`fk_idusuario`) REFERENCES `tbusuario` (`id`);

--
-- Restrições para tabelas `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD CONSTRAINT `fk_tbusuario_tbcargos1` FOREIGN KEY (`fk_idcargos`) REFERENCES `tbcargos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbusuario_tbempresa` FOREIGN KEY (`fk_idempresa`) REFERENCES `tbempresa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tbusuario_tbhorario_trab1` FOREIGN KEY (`fk_idhorariotrab`) REFERENCES `tbhorario_trab` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Banco de dados: `gestaodocumentos`
--

--
-- Fazendo dump de dados para tabela `tbempresa`
--

INSERT INTO `tbempresa` (`id`, `nome`, `cliente_code`, `logo_code`, `missao`, `visao`, `valores`, `ativo`) VALUES
(1, 'SGT - Gestão e Tecnologia', 'sgtgestaoetecnologia', NULL, 'Proporcionar ferramentas que garantam o acesso a melhores práticas de gestão organizacional e de qualidade', NULL, 'Respeito ao usuário.\r\nRapidez nas soluções.\r\nInovação permantente.', 1);

--
-- Fazendo dump de dados para tabela `tbcargos`
--

INSERT INTO `tbcargos` (`id`, `titulo`, `fk_idempresa`) VALUES
(1, 'Administrador Master', 1);

--
-- Fazendo dump de dados para tabela `tbhorario_trab`
--

INSERT INTO `tbhorario_trab` (`id`, `titulo`, `manha_entrada`, `manha_saida`, `tarde_entrada`, `tarde_saida`, `fk_idempresa`, `ativo`) VALUES
(1, 'Regular', '07:00:00', '12:00:00', '13:00:00', '18:00:00', 1, 1);

--
-- Fazendo dump de dados para tabela `tbusuario`
--

INSERT INTO `tbusuario` (`id`, `nome`, `email`, `usuario`, `senha`, `ativo`, `fk_idempresa`, `fk_idcargos`, `fk_idhorariotrab`) VALUES
(1, 'Administrador', 'suporte@sgtgestaoetecnologia.com.br', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 1, 1, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
