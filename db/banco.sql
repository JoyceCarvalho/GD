-- MySQL Workbench Synchronization
-- Generated: 2018-02-20 08:18
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: SGT

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `gestaodocumentos` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbempresa` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NULL DEFAULT NULL,
  `cliente_code` VARCHAR(45) NULL DEFAULT NULL,
  `logo_code` VARCHAR(45) NULL DEFAULT NULL,
  `missao` VARCHAR(255) NULL DEFAULT NULL,
  `visao` VARCHAR(255) NULL DEFAULT NULL,
  `valores` TEXT NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbcargos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(100) NULL DEFAULT NULL,
  `fk_idempresa` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbcargos_tbempresa1_idx` (`fk_idempresa` ASC),
  CONSTRAINT `fk_tbcargos_tbempresa1`
    FOREIGN KEY (`fk_idempresa`)
    REFERENCES `gestaodocumentos`.`tbempresa` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbhorario_trab` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255),
  `manha_entrada` TIME NULL DEFAULT NULL,
  `manha_saida` TIME NULL DEFAULT NULL,
  `tarde_entrada` TIME NULL DEFAULT NULL,
  `tarde_saida` TIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbusuario` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NULL DEFAULT NULL,
  `email` VARCHAR(255) NULL DEFAULT NULL,
  `usuario` VARCHAR(45) NULL DEFAULT NULL,
  `senha` VARCHAR(100) NULL DEFAULT NULL,
  `ativo` INT DEFAULT 1,
  `fk_idempresa` INT(11) NOT NULL,
  `fk_idcargos` INT(11) NOT NULL,
  `fk_idhorariotrab` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbusuario_tbcargos1_idx` (`fk_idcargos` ASC),
  INDEX `fk_tbusuario_tbempresa_idx` (`fk_idempresa` ASC),
  INDEX `fk_tbusuario_tbhorario_trab1_idx` (`fk_idhorariotrab` ASC),
  CONSTRAINT `fk_tbusuario_tbempresa`
    FOREIGN KEY (`fk_idempresa`)
    REFERENCES `gestaodocumentos`.`tbempresa` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tbusuario_tbcargos1`
    FOREIGN KEY (`fk_idcargos`)
    REFERENCES `gestaodocumentos`.`tbcargos` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tbusuario_tbhorario_trab1`
    FOREIGN KEY (`fk_idhorariotrab`)
    REFERENCES `gestaodocumentos`.`tbhorario_trab` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
PACK_KEYS = DEFAULT;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbferiados` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(100) NULL DEFAULT NULL,
  `dia` DATE NULL DEFAULT NULL,
  `fk_idempresa` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbferiados_tbempresa1_idx` (`fk_idempresa` ASC),
  CONSTRAINT `fk_tbferiados_tbempresa1`
    FOREIGN KEY (`fk_idempresa`)
    REFERENCES `gestaodocumentos`.`tbempresa` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbferias_func` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dia_inicio` DATE NULL DEFAULT NULL,
  `dia_fim` DATE NULL DEFAULT NULL,
  `fk_idusuario` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbferias_func_tbusuario1_idx` (`fk_idusuario` ASC),
  CONSTRAINT `fk_tbferias_func_tbusuario1`
    FOREIGN KEY (`fk_idusuario`)
    REFERENCES `gestaodocumentos`.`tbusuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbgrupo` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbdocumento` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(200) NULL DEFAULT NULL,
  `fk_idgrupo` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbdocumento_tbgrupo1_idx` (`fk_idgrupo` ASC),
  CONSTRAINT `fk_tbdocumento_tbgrupo1`
    FOREIGN KEY (`fk_idgrupo`)
    REFERENCES `gestaodocumentos`.`tbgrupo` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbetapa` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NULL DEFAULT NULL,
  `ativo` INT(11) NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbdocumentoetapa` (
  `iddocumento` INT(11) NOT NULL,
  `idetapa` INT(11) NOT NULL,
  `ordem` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`iddocumento`, `idetapa`),
  INDEX `fk_tbdocumento_has_tbetapa_tbetapa1_idx` (`idetapa` ASC, `iddocumento` ASC),
  CONSTRAINT `fk_tbdocumento_has_tbetapa_tbdocumento1`
    FOREIGN KEY (`iddocumento`)
    REFERENCES `gestaodocumentos`.`tbdocumento` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tbdocumento_has_tbetapa_tbetapa1`
    FOREIGN KEY (`idetapa`)
    REFERENCES `gestaodocumentos`.`tbetapa` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbdocumentos_cad` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `protocolo` VARCHAR(200) NULL DEFAULT NULL,
  `atos` INT(11) NULL DEFAULT NULL,
  `status` INT(11) NULL DEFAULT NULL,
  `prazo` DATE NULL DEFAULT NULL,
  `fk_iddocumento` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbdocumentos_cad_tbdocumento1_idx` (`fk_iddocumento` ASC),
  CONSTRAINT `fk_tbdocumentos_cad_tbdocumento1`
    FOREIGN KEY (`fk_iddocumento`)
    REFERENCES `gestaodocumentos`.`tbdocumento` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tblog_documentos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(255) NULL DEFAULT NULL,
  `data_hora` DATETIME NULL DEFAULT NULL,
  `ultima_etapa` VARCHAR(5) NULL DEFAULT NULL,
  `usuario` INT(11) NOT NULL,
  `etapa` INT(11) NOT NULL,
  `documento` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbprazoetapa` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `prazo` DATE NULL DEFAULT NULL,
  `fk_iddocumento` INT(11) NOT NULL,
  `fk_idetapa` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbprazoetapa_tbdocumentoetapa1_idx` (`fk_iddocumento` ASC, `fk_idetapa` ASC),
  CONSTRAINT `fk_tbprazoetapa_tbdocumentoetapa1`
    FOREIGN KEY (`fk_iddocumento` , `fk_idetapa`)
    REFERENCES `gestaodocumentos`.`tbdocumentoetapa` (`iddocumento` , `idetapa`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tberros` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(255) NULL DEFAULT NULL,
  `tipo` VARCHAR(50) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tberros_documentos` (
  `fk_iderros` INT(11) NULL DEFAULT NULL,
  `fk_iddocumentos` INT(11) NULL DEFAULT NULL,
  `descricao` TEXT NULL DEFAULT NULL,
  `data_hora` DATETIME NULL DEFAULT NULL,
  `fk_idusuario` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`fk_iderros`, `fk_iddocumentos`),
  INDEX `fk_tberros_has_tbdocumentos_cad_tbdocumentos_cad1_idx` (`fk_iddocumentos` ASC),
  INDEX `fk_tberros_documentos_tbusuario1_idx` (`fk_idusuario` ASC),
  INDEX `fk_tberros_has_tbdocumentos_cad_tberros1_idx` (`fk_iderros` ASC),
  CONSTRAINT `fk_tberros_has_tbdocumentos_cad_tberros1`
    FOREIGN KEY (`fk_iderros`)
    REFERENCES `gestaodocumentos`.`tberros` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tberros_has_tbdocumentos_cad_tbdocumentos_cad1`
    FOREIGN KEY (`fk_iddocumentos`)
    REFERENCES `gestaodocumentos`.`tbdocumentos_cad` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tberros_documentos_tbusuario1`
    FOREIGN KEY (`fk_idusuario`)
    REFERENCES `gestaodocumentos`.`tbusuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbcancelamento` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `motivo` VARCHAR(255) NULL DEFAULT NULL,
  `data_hora` DATETIME NULL DEFAULT NULL,
  `fk_iddocumento` INT(11) NOT NULL,
  `fk_idusuario` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbcancelamento_tbdocumentos_cad1_idx` (`fk_iddocumento` ASC),
  INDEX `fk_tbcancelamento_tbusuario1_idx` (`fk_idusuario` ASC),
  CONSTRAINT `fk_tbcancelamento_tbdocumentos_cad1`
    FOREIGN KEY (`fk_iddocumento` , `fk_idusuario`)
    REFERENCES `gestaodocumentos`.`tbdocumentos_cad` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_tbcancelamento_tbusuario1`
    FOREIGN KEY (`fk_idusuario`)
    REFERENCES `gestaodocumentos`.`tbusuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tblog_sistema` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario` INT(11) NULL DEFAULT NULL,
  `mensagem` TEXT NULL DEFAULT NULL,
  `data_hora` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `gestaodocumentos`.`tbausencia` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dia_inicio` DATE NULL DEFAULT NULL,
  `dia_fim` DATE NULL DEFAULT NULL,
  `motivo` TEXT NULL DEFAULT NULL,
  `fk_idusuario` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_tbausencia_tbusuario1_idx` (`fk_idusuario` ASC),
  CONSTRAINT `fk_tbausencia_tbusuario1`
    FOREIGN KEY (`fk_idusuario`)
    REFERENCES `gestaodocumentos`.`tbusuario` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- 
-- Dados iniciais da tabela tbcargo


INSERT INTO `tbcargos` (`id`, `titulo`, `fk_idempresa`) VALUES
(1, 'Administrador', 1);

--
-- Dados iniciais da tabela `tbempresa`
--

INSERT INTO `tbempresa` (`id`, `nome`, `cliente_code`, `logo_code`, `missao`, `visao`, `valores`) VALUES
(1, 'SGT - Gestão e Tecnologia', 'sgtgestaoetecnologia', NULL, 'Proporcionar ferramentas que garantam o acesso a melhores práticas de gestão organizacional e de qualidade', NULL, 'Respeito ao usuário.\r\nRapidez nas soluções.\r\nInovação permantente.');

--
-- Dados iniciais da tabela `tbhorario_trab`
--

INSERT INTO `tbhorario_trab` (`id`, `titulo`, `manha_entrada`, `manha_saida`, `tarde_entrada`, `tarde_saida`) VALUES
(1, 'Regular', '07:00:00', '12:00:00', '13:00:00', '18:00:00');

--
-- Dados iniciais dados da tabela `tbusuario`
--

INSERT INTO `tbusuario` (`id`, `nome`, `email`, `usuario`, `senha`, `ativo`, `fk_idempresa`, `fk_idcargos`, `fk_idhorariotrab`) VALUES
(1, 'Administrador', 'suporte@sgtgestaoetecnologia.com.br', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 1, 1, 1);
COMMIT;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
