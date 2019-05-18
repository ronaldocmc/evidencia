CREATE DATABASE  IF NOT EXISTS `evidencia` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `evidencia`;
-- MySQL dump 10.13  Distrib 5.7.23, for Win64 (x86_64)
--
-- Host: dbprdevidencia01.mysql.database.azure.com    Database: evidencia
-- ------------------------------------------------------
-- Server version	5.6.39.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`,`ip_address`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cidadaos`
--

DROP TABLE IF EXISTS `cidadaos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cidadaos` (
  `cidadao_pk` int(11) NOT NULL AUTO_INCREMENT,
  `cidadao_nome` varchar(100) DEFAULT NULL,
  `cidadao_email` varchar(100) DEFAULT NULL,
  `cidadao_cpf` varchar(15) DEFAULT NULL,
  `cidadao_telefone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cidadao_pk`),
  UNIQUE KEY `cidadao_cpf_UNIQUE` (`cidadao_cpf`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `contador_cod_os`
--

DROP TABLE IF EXISTS `contador_cod_os`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `contador_cod_os` (
  `contador_cod_os_pk` int(11) NOT NULL AUTO_INCREMENT,
  `organizacao_fk` varchar(45) NOT NULL,
  `proximo_cod` int(11) NOT NULL,
  PRIMARY KEY (`contador_cod_os_pk`),
  UNIQUE KEY `organizacao_fk_UNIQUE` (`organizacao_fk`),
  CONSTRAINT `contador_organizacoes` FOREIGN KEY (`organizacao_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `debug`
--

DROP TABLE IF EXISTS `debug`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `debug` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mensagem` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `departamentos`
--

DROP TABLE IF EXISTS `departamentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departamentos` (
  `departamento_pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `departamento_nome` varchar(100) NOT NULL,
  `organizacao_fk` varchar(10) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`departamento_pk`),
  KEY `departamentos_organizacoes_colecao` (`organizacao_fk`),
  CONSTRAINT `departamentos_organizacoes_colecao` FOREIGN KEY (`organizacao_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `estados`
--

DROP TABLE IF EXISTS `estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `estados` (
  `estado_pk` varchar(2) NOT NULL,
  `estado_nome` varchar(45) NOT NULL,
  PRIMARY KEY (`estado_pk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `filtros_relatorios_setores`
--

DROP TABLE IF EXISTS `filtros_relatorios_setores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filtros_relatorios_setores` (
  `relatorio_fk` int(11) NOT NULL,
  `setor_fk` int(11) NOT NULL,
  PRIMARY KEY (`relatorio_fk`,`setor_fk`),
  KEY `fk_setor_fk_idx` (`setor_fk`),
  CONSTRAINT `fk_relatorio_fk` FOREIGN KEY (`relatorio_fk`) REFERENCES `relatorios` (`relatorio_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_setor_fk` FOREIGN KEY (`setor_fk`) REFERENCES `setores` (`setor_pk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `filtros_relatorios_tipos_servicos`
--

DROP TABLE IF EXISTS `filtros_relatorios_tipos_servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `filtros_relatorios_tipos_servicos` (
  `relatorio_fk` int(11) NOT NULL,
  `tipo_servico_fk` int(11) NOT NULL,
  PRIMARY KEY (`relatorio_fk`,`tipo_servico_fk`),
  KEY `fk_tipo_servico_fk_idx` (`tipo_servico_fk`),
  CONSTRAINT `fk_tipo_servico_fk` FOREIGN KEY (`tipo_servico_fk`) REFERENCES `tipos_servicos` (`tipo_servico_pk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fqwek_relatorio_fk` FOREIGN KEY (`relatorio_fk`) REFERENCES `relatorios` (`relatorio_pk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `funcionarios`
--

DROP TABLE IF EXISTS `funcionarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funcionarios` (
  `funcionario_pk` int(11) NOT NULL AUTO_INCREMENT,
  `organizacao_fk` varchar(10) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `funcionario_login` varchar(100) NOT NULL,
  `funcionario_senha` varchar(300) NOT NULL,
  `funcionario_nome` varchar(100) NOT NULL,
  `funcionario_cpf` varchar(15) NOT NULL,
  `funcao_fk` int(11) NOT NULL,
  `departamento_fk` int(11) DEFAULT NULL,
  `funcionario_caminho_foto` text,
  PRIMARY KEY (`funcionario_pk`),
  UNIQUE KEY `funcionario_login_UNIQUE` (`funcionario_login`),
  UNIQUE KEY `funcionario_cpf_UNIQUE` (`funcionario_cpf`),
  KEY `funcionario_organizacao_colecao` (`organizacao_fk`),
  KEY `funcionarios_funcoes_idx` (`funcao_fk`),
  CONSTRAINT `funcionarios_funcoes` FOREIGN KEY (`funcao_fk`) REFERENCES `funcoes` (`funcao_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `funcionarios_organizacoes` FOREIGN KEY (`organizacao_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `funcionarios_setores`
--

DROP TABLE IF EXISTS `funcionarios_setores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funcionarios_setores` (
  `funcionario_setor_pk` int(11) NOT NULL AUTO_INCREMENT,
  `funcionario_fk` int(11) NOT NULL,
  `setor_fk` int(11) NOT NULL,
  PRIMARY KEY (`funcionario_setor_pk`),
  KEY `funcionarios_setores_setores_colecao` (`setor_fk`),
  KEY `funcionarios_setores_funcionarios_colecao` (`funcionario_fk`),
  CONSTRAINT `funcionarios_setores_funcionarios_colecao` FOREIGN KEY (`funcionario_fk`) REFERENCES `funcionarios` (`funcionario_pk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `funcionarios_setores_setores_colecao` FOREIGN KEY (`setor_fk`) REFERENCES `setores` (`setor_pk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `funcoes`
--

DROP TABLE IF EXISTS `funcoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `funcoes` (
  `funcao_pk` int(11) NOT NULL AUTO_INCREMENT,
  `funcao_nome` varchar(50) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `organizacao_fk` varchar(45) NOT NULL,
  PRIMARY KEY (`funcao_pk`),
  KEY `oganizacao_fk_n_idx` (`organizacao_fk`),
  CONSTRAINT `organizacao_fk_fk` FOREIGN KEY (`organizacao_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `geral_atualizacao`
--

DROP TABLE IF EXISTS `geral_atualizacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `geral_atualizacao` (
  `geral_atualizacao_pk` int(11) NOT NULL AUTO_INCREMENT,
  `geral_atualizacao_tempo` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `empresa_fk` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`geral_atualizacao_pk`),
  KEY `fk_empresa_idx` (`empresa_fk`),
  CONSTRAINT `organizacao_fk` FOREIGN KEY (`empresa_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historicos_ordens`
--

DROP TABLE IF EXISTS `historicos_ordens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historicos_ordens` (
  `historico_ordem_pk` int(11) NOT NULL AUTO_INCREMENT,
  `ordem_servico_fk` int(11) NOT NULL,
  `funcionario_fk` int(11) NOT NULL,
  `situacao_fk` int(10) unsigned NOT NULL,
  `historico_ordem_tempo` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `historico_ordem_comentario` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`historico_ordem_pk`),
  KEY `historicos_ordens_ordens_servicos_colecao` (`ordem_servico_fk`),
  KEY `historicos_ordens_funcionarios_colecao` (`funcionario_fk`),
  KEY `historicos_ordens_situacoes` (`situacao_fk`),
  CONSTRAINT `historicos_ordens_funcionarios_colecao` FOREIGN KEY (`funcionario_fk`) REFERENCES `funcionarios` (`funcionario_pk`),
  CONSTRAINT `historicos_ordens_ordens_servicos_colecao` FOREIGN KEY (`ordem_servico_fk`) REFERENCES `ordens_servicos` (`ordem_servico_pk`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `historicos_ordens_situacoes` FOREIGN KEY (`situacao_fk`) REFERENCES `situacoes` (`situacao_pk`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `imagens_os`
--

DROP TABLE IF EXISTS `imagens_os`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `imagens_os` (
  `imagem_os_pk` int(11) NOT NULL AUTO_INCREMENT,
  `ordem_servico_fk` int(11) NOT NULL,
  `imagem_os_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `situacao_fk` int(11) NOT NULL,
  `imagem_os` text NOT NULL,
  PRIMARY KEY (`imagem_os_pk`),
  KEY `imagens_ordens_servicos_idx` (`ordem_servico_fk`),
  CONSTRAINT `imagens_ordens_servicos` FOREIGN KEY (`ordem_servico_fk`) REFERENCES `ordens_servicos` (`ordem_servico_pk`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `localizacoes`
--

DROP TABLE IF EXISTS `localizacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `localizacoes` (
  `localizacao_pk` int(11) NOT NULL AUTO_INCREMENT,
  `localizacao_lat` varchar(45) DEFAULT NULL,
  `localizacao_long` varchar(45) DEFAULT NULL,
  `localizacao_rua` varchar(100) DEFAULT NULL,
  `localizacao_num` varchar(5) DEFAULT NULL,
  `localizacao_bairro` varchar(100) DEFAULT NULL,
  `localizacao_municipio` int(11) NOT NULL,
  `localizacao_ponto_referencia` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`localizacao_pk`),
  KEY `municipio_fk_idx` (`localizacao_municipio`),
  CONSTRAINT `municipio_fk` FOREIGN KEY (`localizacao_municipio`) REFERENCES `municipios` (`municipio_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=177 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `log`
--

DROP TABLE IF EXISTS `log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log` (
  `log_pk` int(11) NOT NULL AUTO_INCREMENT,
  `log_descricao` varchar(200) NOT NULL,
  `log_ip` varchar(45) NOT NULL,
  `log_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `log_pessoa_fk` int(11) DEFAULT NULL,
  PRIMARY KEY (`log_pk`),
  KEY `fk_pessoa_dd_idx` (`log_pessoa_fk`),
  CONSTRAINT `fk_pessoa_dd` FOREIGN KEY (`log_pessoa_fk`) REFERENCES `populacao` (`pessoa_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `mensagens_feedback`
--

DROP TABLE IF EXISTS `mensagens_feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mensagens_feedback` (
  `mensagem_pk` int(11) NOT NULL AUTO_INCREMENT,
  `funcionario_fk` int(11) NOT NULL,
  `mensagem_texto` longtext NOT NULL,
  `mensagem_data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mensagem_lida` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`mensagem_pk`),
  KEY `mensagens_funcionario_idx` (`funcionario_fk`),
  CONSTRAINT `mensagens_funcionario` FOREIGN KEY (`funcionario_fk`) REFERENCES `funcionarios` (`funcionario_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `municipios`
--

DROP TABLE IF EXISTS `municipios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `municipios` (
  `municipio_pk` int(11) NOT NULL AUTO_INCREMENT,
  `municipio_nome` varchar(100) NOT NULL,
  `estado_fk` varchar(2) NOT NULL,
  PRIMARY KEY (`municipio_pk`),
  KEY `estado_fk_idx` (`estado_fk`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ordens_servicos`
--

DROP TABLE IF EXISTS `ordens_servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordens_servicos` (
  `ordem_servico_pk` int(11) NOT NULL AUTO_INCREMENT,
  `localizacao_fk` int(11) NOT NULL,
  `prioridade_fk` int(10) NOT NULL,
  `procedencia_fk` int(11) NOT NULL,
  `servico_fk` int(11) NOT NULL,
  `setor_fk` int(11) NOT NULL,
  `ordem_servico_cod` varchar(45) NOT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '1',
  `ordem_servico_desc` text NOT NULL,
  `funcionario_fk` int(11) NOT NULL,
  `situacao_inicial_fk` int(10) NOT NULL,
  `situacao_atual_fk` int(10) DEFAULT NULL,
  `ordem_servico_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ordem_servico_finalizacao` timestamp NULL DEFAULT NULL,
  `ordem_servico_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ordem_servico_comentario` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`ordem_servico_pk`),
  KEY `localizacao_fk_idx` (`localizacao_fk`),
  KEY `ordens_servicos_procedencia_idx` (`procedencia_fk`),
  KEY `ordens_servicos_servico_idx` (`servico_fk`),
  KEY `ordens_servicos_setor_idx` (`setor_fk`),
  KEY `ordens_servicos_funcionario_idx` (`funcionario_fk`),
  CONSTRAINT `ordens_servicos_funcionario` FOREIGN KEY (`funcionario_fk`) REFERENCES `funcionarios` (`funcionario_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_localizacao` FOREIGN KEY (`localizacao_fk`) REFERENCES `localizacoes` (`localizacao_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_procedencia` FOREIGN KEY (`procedencia_fk`) REFERENCES `procedencias` (`procedencia_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_servico` FOREIGN KEY (`servico_fk`) REFERENCES `servicos` (`servico_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_setor` FOREIGN KEY (`setor_fk`) REFERENCES `setores` (`setor_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`admin_prudenco`@`%`*/ /*!50003 TRIGGER `updateOrdemServicoDataTracking` 
BEFORE UPDATE ON `ordens_servicos` 
FOR EACH ROW INSERT INTO `ordens_servicos_data_tracking` 
(`ordem_servico_fk`, `localizacao_fk`, `prioridade_fk`, `procedencia_fk`, `servico_fk`,
`setor_fk`, `ordem_servico_cod`, `ativo`, `ordem_servico_desc`, `funcionario_fk`, 
`situacao_inicial_fk`, `situacao_atual_fk`,`ordem_servico_criacao`, `ordem_servico_atualizacao`, `ordem_servico_comentario`, `timestamp`) 

VALUES (old.ordem_servico_pk, old.localizacao_fk, old.prioridade_fk, old.procedencia_fk, old.servico_fk,
old.setor_fk, old.ordem_servico_cod, old.ativo, old.ordem_servico_desc, old.funcionario_fk,
old.situacao_inicial_fk, old.situacao_atual_fk, old.ordem_servico_criacao, old.ordem_servico_atualizacao, old.ordem_servico_comentario, now()) */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `ordens_servicos_data_tracking`
--

DROP TABLE IF EXISTS `ordens_servicos_data_tracking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ordens_servicos_data_tracking` (
  `ordem_servico_data_tracking_pk` int(11) NOT NULL AUTO_INCREMENT,
  `ordem_servico_fk` int(11) NOT NULL,
  `localizacao_fk` int(11) NOT NULL,
  `prioridade_fk` int(10) NOT NULL,
  `procedencia_fk` int(11) NOT NULL,
  `servico_fk` int(11) NOT NULL,
  `setor_fk` int(11) NOT NULL,
  `ordem_servico_cod` varchar(45) NOT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '1',
  `ordem_servico_desc` text NOT NULL,
  `funcionario_fk` int(11) NOT NULL,
  `situacao_inicial_fk` int(10) NOT NULL,
  `situacao_atual_fk` int(10) DEFAULT NULL,
  `ordem_servico_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ordem_servico_finalizacao` timestamp NULL DEFAULT NULL,
  `ordem_servico_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ordem_servico_comentario` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ordem_servico_data_tracking_pk`),
  KEY `localizacao_fk_idx` (`localizacao_fk`),
  KEY `ordem_servico_fk_idx` (`ordem_servico_fk`),
  KEY `ordens_servicos_procedencia_idx` (`procedencia_fk`),
  KEY `ordens_servicos_servico_idx` (`servico_fk`),
  KEY `ordens_servicos_setor_idx` (`setor_fk`),
  KEY `ordens_servicos_funcionario_idx` (`funcionario_fk`),
  CONSTRAINT `ordens_servicos_data_tracking_funcionario` FOREIGN KEY (`funcionario_fk`) REFERENCES `funcionarios` (`funcionario_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_data_tracking_localizacao` FOREIGN KEY (`localizacao_fk`) REFERENCES `localizacoes` (`localizacao_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_data_tracking_ordem_servico` FOREIGN KEY (`ordem_servico_fk`) REFERENCES `ordens_servicos` (`ordem_servico_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_data_tracking_procedencia` FOREIGN KEY (`procedencia_fk`) REFERENCES `procedencias` (`procedencia_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_data_tracking_servico` FOREIGN KEY (`servico_fk`) REFERENCES `servicos` (`servico_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `ordens_servicos_data_tracking_setor` FOREIGN KEY (`setor_fk`) REFERENCES `setores` (`setor_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `organizacoes`
--

DROP TABLE IF EXISTS `organizacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organizacoes` (
  `organizacao_pk` varchar(10) NOT NULL,
  `organizacao_nome` varchar(60) NOT NULL,
  `organizacao_cnpj` varchar(18) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `proximo_cod` int(11) NOT NULL DEFAULT '1',
  `localizacao_fk` int(11) NOT NULL,
  PRIMARY KEY (`organizacao_pk`),
  KEY `organizacao_localizacao_idx` (`localizacao_fk`),
  CONSTRAINT `organizacao_localizacao` FOREIGN KEY (`localizacao_fk`) REFERENCES `localizacoes` (`localizacao_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `prioridades`
--

DROP TABLE IF EXISTS `prioridades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prioridades` (
  `prioridade_pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prioridade_nome` varchar(128) NOT NULL,
  `ativo` tinyint(4) NOT NULL DEFAULT '1',
  `organizacao_fk` varchar(10) NOT NULL,
  PRIMARY KEY (`prioridade_pk`),
  KEY `pioridade_organizacao_colecao` (`organizacao_fk`),
  CONSTRAINT `pioridade_organizacao_colecao` FOREIGN KEY (`organizacao_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `procedencias`
--

DROP TABLE IF EXISTS `procedencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procedencias` (
  `procedencia_pk` int(11) NOT NULL AUTO_INCREMENT,
  `procedencia_nome` varchar(45) NOT NULL,
  `procedencia_desc` varchar(150) DEFAULT NULL,
  `organizacao_fk` varchar(10) NOT NULL,
  PRIMARY KEY (`procedencia_pk`),
  KEY `procedecias_organizacao_fk_idx` (`organizacao_fk`),
  CONSTRAINT `procedecias_organizacao_fk` FOREIGN KEY (`organizacao_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `recuperacoes_senha`
--

DROP TABLE IF EXISTS `recuperacoes_senha`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recuperacoes_senha` (
  `superusuario_fk` int(11) NOT NULL,
  `recuperacao_token` varchar(128) NOT NULL,
  `recuperacao_tempo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `recuperacoes_funcionarios_idx` (`superusuario_fk`),
  CONSTRAINT `recuperacoes_funcionarios` FOREIGN KEY (`superusuario_fk`) REFERENCES `superusuarios` (`superusuario_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='recu';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relatorios`
--

DROP TABLE IF EXISTS `relatorios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relatorios` (
  `relatorio_pk` int(11) NOT NULL AUTO_INCREMENT,
  `relatorio_func_responsavel` int(11) NOT NULL,
  `relatorio_data_criacao` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ativo` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0 - Em Andamento 1 - Finalizado (serve para indicar se todas as OS desse relatório foram concluídas.',
  `relatorio_data_entrega` datetime DEFAULT NULL,
  `relatorio_criador` int(11) NOT NULL,
  `relatorio_data_inicio_filtro` timestamp NOT NULL,
  `relatorio_data_fim_filtro` timestamp NOT NULL,
  `relatorio_situacao` varchar(20) NOT NULL,
  PRIMARY KEY (`relatorio_pk`),
  KEY `relatorios_funcionarios_idx` (`relatorio_criador`),
  CONSTRAINT `relatorios_funcionarios` FOREIGN KEY (`relatorio_criador`) REFERENCES `funcionarios` (`funcionario_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `relatorios_os`
--

DROP TABLE IF EXISTS `relatorios_os`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `relatorios_os` (
  `relatorio_fk` int(11) NOT NULL,
  `os_fk` int(11) NOT NULL,
  `os_verificada` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`relatorio_fk`,`os_fk`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `servicos`
--

DROP TABLE IF EXISTS `servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servicos` (
  `servico_pk` int(11) NOT NULL AUTO_INCREMENT,
  `servico_nome` varchar(30) NOT NULL,
  `servico_desc` varchar(200) DEFAULT NULL,
  `situacao_padrao_fk` int(10) unsigned DEFAULT NULL,
  `tipo_servico_fk` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `servico_abreviacao` varchar(10) NOT NULL,
  PRIMARY KEY (`servico_pk`),
  KEY `servicos_tipos_servicos_colecao` (`tipo_servico_fk`),
  KEY `servicos_situacoes_colecao` (`situacao_padrao_fk`),
  CONSTRAINT `servicos_situacoes_colecao` FOREIGN KEY (`situacao_padrao_fk`) REFERENCES `situacoes` (`situacao_pk`),
  CONSTRAINT `servicos_tipos_servicos_colecao` FOREIGN KEY (`tipo_servico_fk`) REFERENCES `tipos_servicos` (`tipo_servico_pk`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `setores`
--

DROP TABLE IF EXISTS `setores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `setores` (
  `setor_pk` int(11) NOT NULL AUTO_INCREMENT,
  `setor_nome` varchar(50) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `organizacao_fk` varchar(10) NOT NULL,
  PRIMARY KEY (`setor_pk`),
  KEY `setores_organizacoes_colecao` (`organizacao_fk`),
  CONSTRAINT `setores_organizacoes_colecao` FOREIGN KEY (`organizacao_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `situacoes`
--

DROP TABLE IF EXISTS `situacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `situacoes` (
  `situacao_pk` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `situacao_nome` varchar(50) NOT NULL,
  `situacao_descricao` text NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `organizacao_fk` varchar(10) NOT NULL,
  PRIMARY KEY (`situacao_pk`),
  KEY `colecao_situacao_organizacao` (`organizacao_fk`),
  CONSTRAINT `colecao_situacao_organizacao` FOREIGN KEY (`organizacao_fk`) REFERENCES `organizacoes` (`organizacao_pk`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `superusuarios`
--

DROP TABLE IF EXISTS `superusuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `superusuarios` (
  `superusuario_pk` int(11) NOT NULL AUTO_INCREMENT,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `superusuario_login` varchar(100) NOT NULL,
  `superusuario_senha` varchar(200) NOT NULL,
  `superusuario_nome` varchar(150) NOT NULL,
  `superusuario_email` varchar(120) NOT NULL,
  PRIMARY KEY (`superusuario_pk`),
  UNIQUE KEY `superusuario_login_UNIQUE` (`superusuario_login`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;


LOCK TABLES `superusuarios` WRITE;
/*!40000 ALTER TABLE `superusuarios` DISABLE KEYS */;
INSERT INTO `superusuarios` VALUES (1,1,'darlannakamura@admin','67092b61f77b8303e5265125bd2dbc4488797d7e446b1766896c9eedf4764152dad7330e2d75792113644bc5c08e9928b4d9f49c2f0951684e740fb358da39d6','Darlan Nakamura','darlannakamura@hotmail.com');
/*!40000 ALTER TABLE `superusuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tentativas_login`
--

DROP TABLE IF EXISTS `tentativas_login`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tentativas_login` (
  `tentativa_ip` varchar(45) NOT NULL,
  `tentativa_tempo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tentativa_ip`,`tentativa_tempo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tentativas_recuperacoes`
--

DROP TABLE IF EXISTS `tentativas_recuperacoes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tentativas_recuperacoes` (
  `tentativa_ip` varchar(45) NOT NULL,
  `tentativa_tempo` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tentativa_email` varchar(100) NOT NULL,
  PRIMARY KEY (`tentativa_ip`,`tentativa_tempo`,`tentativa_email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_servicos`
--

DROP TABLE IF EXISTS `tipos_servicos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_servicos` (
  `tipo_servico_pk` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_servico_nome` varchar(30) NOT NULL,
  `tipo_servico_desc` varchar(200) DEFAULT NULL,
  `prioridade_padrao_fk` int(10) unsigned DEFAULT NULL,
  `departamento_fk` int(10) unsigned NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `tipo_servico_abreviacao` varchar(10) NOT NULL,
  PRIMARY KEY (`tipo_servico_pk`),
  KEY `tipos_servicos_prioridades_colecao` (`prioridade_padrao_fk`),
  KEY `tipos_servicos_departamentos_colecao` (`departamento_fk`),
  CONSTRAINT `tipos_servicos_departamentos_colecao` FOREIGN KEY (`departamento_fk`) REFERENCES `departamentos` (`departamento_pk`),
  CONSTRAINT `tipos_servicos_prioridades_colecao` FOREIGN KEY (`prioridade_padrao_fk`) REFERENCES `prioridades` (`prioridade_pk`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `token`
--

DROP TABLE IF EXISTS `token`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `token` (
  `token` varchar(128) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `funcionario_fk` int(11) DEFAULT NULL,
  PRIMARY KEY (`token`),
  KEY `token_funcionarios_idx` (`funcionario_fk`),
  CONSTRAINT `token_funcionarios` FOREIGN KEY (`funcionario_fk`) REFERENCES `funcionarios` (`funcionario_pk`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'evidencia'
--

--
-- Dumping routines for database 'evidencia'
--
/*!50003 DROP PROCEDURE IF EXISTS `getForMobile` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `getForMobile`(IN org_id VARCHAR(10), IN id_func INT)
setores: BEGIN    
  
  DECLARE fim_setores, setor INT;

  DECLARE cursor_setores CURSOR FOR
    SELECT evidencia.funcionarios_setores.setor_fk 
    FROM evidencia.funcionarios_setores
    WHERE evidencia.funcionarios_setores.funcionario_fk = id_func;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET fim_setores = 1;
  SET fim_setores = 0;

  DROP TABLE IF EXISTS relatorio_json_mobile;

  CREATE TEMPORARY TABLE relatorio_json_mobile(
    id INT,
    descricao VARCHAR(200),
    cod VARCHAR(45),
    prioridade INT,
    servico INT,
    situacao INT,
    latitude VARCHAR(25),
    longitude VARCHAR(25),
    logradouro_nome VARCHAR(128),
    local_complemento VARCHAR(50),
    local_num INT,
    bairro_nome VARCHAR(80),
    data_inicial datetime
  );


  OPEN cursor_setores;
  setores_repeat: REPEAT
    IF fim_setores != 1 THEN
      FETCH cursor_setores INTO setor;

        ordens: BEGIN

          DECLARE os_pk, prioridade, fim_ordens, num_local, sit, aux, servico INT;
          DECLARE os_cod VARCHAR(45);
          DECLARE os_desc VARCHAR(200);
          DECLARE lat, lng VARCHAR(25);
          DECLARE complemento VARCHAR(50);
          DECLARE logradouro_nome VARCHAR(128);
          DECLARE bairro_nome VARCHAR(80);
          DECLARE primeira_data datetime;

          DECLARE cursor_os CURSOR FOR 
            SELECT evidencia.ordens_servicos.ordem_servico_pk, 
                   evidencia.ordens_servicos.ordem_servico_desc,
                   evidencia.ordens_servicos.ordem_servico_cod,
                   evidencia.ordens_servicos.prioridade_fk,
                   evidencia.ordens_servicos.servico_fk,
                   evidencia.coordenadas.coordenada_lat,
                   evidencia.coordenadas.coordenada_long,
                   evidencia.locais.local_complemento,
                   evidencia.locais.local_num,
                   evidencia.logradouros.logradouro_nome,
                   evidencia.bairros.bairro_nome
            FROM evidencia.ordens_servicos 
                INNER JOIN evidencia.coordenadas ON evidencia.coordenadas.coordenada_pk = evidencia.ordens_servicos.coordenada_fk
                INNER JOIN evidencia.locais ON evidencia.locais.local_pk = evidencia.coordenadas.local_fk
                INNER JOIN evidencia.logradouros ON evidencia.logradouros.logradouro_pk = evidencia.locais.logradouro_fk
                INNER JOIN evidencia.bairros ON evidencia.bairros.bairro_pk = evidencia.locais.bairro_fk
                INNER JOIN evidencia.prioridades ON evidencia.prioridades.prioridade_pk = evidencia.ordens_servicos.prioridade_fk
            WHERE evidencia.prioridades.organizacao_fk = org_id AND evidencia.ordens_servicos.setor_fk = setor 
            AND evidencia.ordens_servicos.ordem_servico_status = 1;

          DECLARE CONTINUE HANDLER FOR NOT FOUND SET fim_ordens = 1;
          SET fim_ordens = 0;

          OPEN cursor_os;
          ordens_repeat: REPEAT
            IF fim_ordens != 1 THEN
            

              FETCH cursor_os INTO os_pk, os_desc, os_cod, prioridade, servico, lat, lng, complemento, num_local, logradouro_nome, bairro_nome;  
              SELECT evidencia.historicos_ordens.situacao_fk INTO sit FROM evidencia.historicos_ordens WHERE ordem_servico_fk = os_pk
                order by historico_ordem_pk DESC limit 1;

              IF sit = 1 OR sit = 2 THEN
                DELETE FROM relatorio_json_mobile WHERE relatorio_json_mobile.id = os_pk;
                
                INSERT INTO relatorio_json_mobile (id, descricao, cod, prioridade,servico, latitude, longitude, local_complemento, logradouro_nome,
                local_num, bairro_nome, situacao)
                VALUES (os_pk, os_desc, os_cod, prioridade,servico, lat, lng, complemento, logradouro_nome, num_local, bairro_nome, sit);
                      
                UPDATE relatorio_json_mobile SET relatorio_json_mobile.data_inicial = (SELECT historicos_ordens.historico_ordem_tempo FROM historicos_ordens 
                WHERE historicos_ordens.ordem_servico_fk = os_pk ORDER BY historicos_ordens.historico_ordem_pk ASC LIMIT 1) WHERE relatorio_json_mobile.id = os_pk;
              END IF;

            END IF;

          UNTIL (fim_ordens=1)
          END REPEAT ordens_repeat;

          CLOSE cursor_os;

        END ordens;
        
    END IF;
    
  UNTIL (fim_setores=1)  
  END REPEAT setores_repeat;
  CLOSE cursor_setores;
  
  

  SELECT * FROM relatorio_json_mobile ORDER BY relatorio_json_mobile.id ASC;

END setores ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `getJsonForMobile` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `getJsonForMobile`(IN org_id VARCHAR(10), IN id_func INT)
setores: BEGIN    
  
  DECLARE fim_setores, setor INT;

  DECLARE cursor_setores CURSOR FOR
    SELECT evidencia.funcionarios_setores.setor_fk 
    FROM evidencia.funcionarios_setores
    WHERE evidencia.funcionarios_setores.funcionario_fk = id_func;

  DECLARE CONTINUE HANDLER FOR NOT FOUND SET fim_setores = 1;
  SET fim_setores = 0;

  DROP TABLE IF EXISTS relatorio_json_mobile;

  CREATE TEMPORARY TABLE relatorio_json_mobile(
    id INT,
    descricao VARCHAR(200),
    cod VARCHAR(45),
    prioridade INT,
    situacao INT,
    latitude VARCHAR(25),
    longitude VARCHAR(25),
    logradouro_nome VARCHAR(128),
    local_complemento VARCHAR(50),
    local_num INT,
    bairro_nome VARCHAR(80),
    data_inicial datetime
  );

  OPEN cursor_setores;
  setores_repeat: REPEAT
    IF fim_setores != 1 THEN
      FETCH cursor_setores INTO setor;

        ordens: BEGIN

          DECLARE os_pk, prioridade, fim_ordens, num_local, sit, aux INT;
          DECLARE os_cod VARCHAR(45);
          DECLARE os_desc VARCHAR(200);
          DECLARE lat, lng VARCHAR(25);
          DECLARE complemento VARCHAR(50);
          DECLARE logradouro_nome VARCHAR(128);
          DECLARE bairro_nome VARCHAR(80);
          DECLARE primeira_data datetime;

          DECLARE cursor_os CURSOR FOR 
            SELECT evidencia.ordens_servicos.ordem_servico_pk, 
                   evidencia.ordens_servicos.ordem_servico_desc,
                   evidencia.ordens_servicos.ordem_servico_cod,
                   evidencia.ordens_servicos.prioridade_fk,
                   evidencia.coordenadas.coordenada_lat,
                   evidencia.coordenadas.coordenada_long,
                   evidencia.locais.local_complemento,
                   evidencia.locais.local_num,
                   evidencia.logradouros.logradouro_nome,
                   evidencia.bairros.bairro_nome
            FROM evidencia.ordens_servicos 
                INNER JOIN evidencia.coordenadas ON evidencia.coordenadas.coordenada_pk = evidencia.ordens_servicos.coordenada_fk
                INNER JOIN evidencia.locais ON evidencia.locais.local_pk = evidencia.coordenadas.local_fk
                INNER JOIN evidencia.logradouros ON evidencia.logradouros.logradouro_pk = evidencia.locais.logradouro_fk
                INNER JOIN evidencia.bairros ON evidencia.bairros.bairro_pk = evidencia.locais.bairro_fk
                INNER JOIN evidencia.prioridades ON evidencia.prioridades.prioridade_pk = evidencia.ordens_servicos.prioridade_fk
            WHERE evidencia.prioridades.organizacao_fk = org_id AND evidencia.ordens_servicos.setor_fk = setor;

          DECLARE CONTINUE HANDLER FOR NOT FOUND SET fim_ordens = 1;
          SET fim_ordens = 0;

          OPEN cursor_os;
          ordens_repeat: REPEAT
            IF fim_ordens != 1 THEN

              FETCH cursor_os INTO os_pk, os_desc, os_cod, prioridade, lat, lng, complemento, num_local, logradouro_nome, bairro_nome;  
              SELECT evidencia.historicos_ordens.situacao_fk INTO sit FROM evidencia.historicos_ordens WHERE ordem_servico_fk = os_pk
                order by historico_ordem_pk DESC limit 1;

              IF sit = 1 OR sit = 2 THEN
                DELETE FROM relatorio_json_mobile WHERE relatorio_json_mobile.id = os_pk;
                
                INSERT INTO relatorio_json_mobile (id, descricao, cod, prioridade, latitude, longitude, local_complemento, logradouro_nome,
                local_num, bairro_nome, situacao)
                VALUES (os_pk, os_desc, os_cod, prioridade, lat, lng, complemento, logradouro_nome, num_local, bairro_nome, sit);
                      
                UPDATE relatorio_json_mobile SET relatorio_json_mobile.data_inicial = (SELECT historicos_ordens.historico_ordem_tempo FROM historicos_ordens 
                WHERE historicos_ordens.ordem_servico_fk = os_pk ORDER BY historicos_ordens.historico_ordem_pk ASC LIMIT 1) WHERE relatorio_json_mobile.id = os_pk;
              END IF;

            END IF;

          UNTIL (fim_ordens=1)
          END REPEAT ordens_repeat;

          CLOSE cursor_os;

        END ordens;
        
    END IF;
    
  UNTIL (fim_setores=1)  
  END REPEAT setores_repeat;
  CLOSE cursor_setores;

  SELECT * FROM relatorio_json_mobile ORDER BY relatorio_json_mobile.id ASC;

END setores ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_ano` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_ano`(IN inicio VARCHAR(10), IN final VARCHAR(10), IN id_org VARCHAR(10))
BEGIN

SELECT
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS janeiro,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS fevereiro,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS marco,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS abril,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS maio,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS junho,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 7,1,0)) AS julho,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 8,1,0)) AS agosto,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 9,1,0)) AS setembro,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 10,1,0)) AS outubro,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 11,1,0)) AS novembro,
sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 12,1,0)) AS dezembro,
count(historicos_ordens.historico_ordem_tempo) AS total
FROM ordens_servicos as ORD
JOIN (SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens GROUP BY historicos_ordens.ordem_servico_fk) as historicos_ordens ON historicos_ordens.ordem_servico_fk = ORD.ordem_servico_pk
INNER JOIN prioridades ON prioridades.prioridade_pk = ORD.prioridade_fk
WHERE prioridades.organizacao_fk = id_org AND historicos_ordens.historico_ordem_tempo between inicio and final;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_bairros_ano` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_bairros_ano`(IN id_org VARCHAR(10))
BEGIN

	DECLARE z, teste, ano INT;
    DECLARE bairro VARCHAR(80);
	DECLARE cursor_bairros CURSOR FOR 
		SELECT bairros.bairro_nome FROM bairros 
		INNER JOIN locais ON locais.bairro_fk = bairros.bairro_pk 
		INNER JOIN coordenadas ON coordenadas.local_fk = locais.local_pk
        group by bairros.bairro_nome;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
    
    SELECT YEAR(CURDATE()) INTO ano;
    
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		id INT AUTO_INCREMENT PRIMARY KEY,
        bairro_nome VARCHAR(80),
        janeiro INT,
        fevereiro INT,
        marco INT,
        abril INT,
        maio INT,
        junho INT,
        julho INT,
        agosto INT,
        setembro INT,
        outubro INT,
        novembro INT,
        dezembro INT,
        total INT
    );

    OPEN cursor_bairros;

    REPEAT

    	FETCH cursor_bairros INTO bairro;
		IF z != 1 THEN
	    	INSERT INTO relatorio (bairro_nome, janeiro, fevereiro, marco,
	        					   abril, maio, junho, julho, agosto, setembro,
	        					   outubro, novembro, dezembro, total)
	    	SELECT 
	    	bairros.bairro_nome,
	    	sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS janeiro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS fevereiro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS marco,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS abril,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS maio,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS junho,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 7,1,0)) AS julho,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 8,1,0)) AS agosto,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 9,1,0)) AS setembro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 10,1,0)) AS outubro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 11,1,0)) AS novembro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 12,1,0)) AS dezembro,
            count(historicos_ordens.historico_ordem_tempo)
	    	FROM ordens_servicos INNER JOIN
				(SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
				GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
			ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
			INNER JOIN (SELECT coordenadas.coordenada_pk, coordenadas.local_fk FROM coordenadas) AS coordenadas
			ON coordenadas.coordenada_pk = ordens_servicos.coordenada_fk
			INNER JOIN (SELECT locais.local_pk, locais.bairro_fk FROM locais) AS locais
			ON locais.local_pk = coordenadas.local_fk
			INNER JOIN (SELECT bairros.bairro_pk, bairros.bairro_nome FROM bairros) AS bairros
			ON bairros.bairro_pk = locais.bairro_fk
			INNER JOIN (SELECT setores.setor_pk, setores.organizacao_fk FROM setores) AS setores
			ON setores.setor_pk = ordens_servicos.setor_fk
			WHERE setores.organizacao_fk = id_org AND bairros.bairro_nome = bairro
            AND YEAR(historicos_ordens.historico_ordem_tempo) = ano;
		
        END IF;

		SELECT MAX(relatorio.id) FROM relatorio INTO teste;
        
        IF ((SELECT relatorio.janeiro FROM relatorio WHERE relatorio.id = teste) IS NULL) THEN
			DELETE FROM relatorio WHERE relatorio.id = teste;
		END IF;

    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_bairros;
    
    SELECT * FROM relatorio;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_bairros_semana` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_bairros_semana`(IN id_org VARCHAR(10))
BEGIN

	DECLARE bairro,z, teste INT;
	DECLARE cursor_bairros CURSOR FOR 
		SELECT bairros.bairro_pk FROM bairros 
		INNER JOIN locais ON locais.bairro_fk = bairros.bairro_pk 
		INNER JOIN coordenadas ON coordenadas.local_fk = locais.local_pk
		GROUP BY bairros.bairro_nome;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		id INT AUTO_INCREMENT PRIMARY KEY,
        bairro_nome VARCHAR(50),
        domingo INT,
        segunda INT,
        terca INT,
        quarta INT,
        quinta INT,
        sexta INT,
        sabado INT,
        total INT
    );

    OPEN cursor_bairros;

    REPEAT

    	FETCH cursor_bairros INTO bairro;
		IF z != 1 THEN
	    	INSERT INTO relatorio (bairro_nome, domingo, segunda, terca,
								   quarta, quinta, sexta, sabado, total)
	    	SELECT 
	    	bairros.bairro_nome,
	    	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS domingo,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 0,1,0)) AS segunda,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS terça,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS quarta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS quinta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS sexta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS sábado,
            count(historicos_ordens.historico_ordem_tempo)
	    	FROM ordens_servicos INNER JOIN
				(SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
				GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
			ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
			INNER JOIN (SELECT coordenadas.coordenada_pk FROM coordenadas) AS coordenadas
			ON coordenadas.coordenada_pk = ordens_servicos.coordenada_fk
			INNER JOIN (SELECT locais.local_pk, locais.bairro_fk FROM locais) AS locais
			ON locais.local_pk = coordenadas.coordenada_pk
			INNER JOIN (SELECT bairros.bairro_pk, bairros.bairro_nome FROM bairros) AS bairros
			ON bairros.bairro_pk = locais.bairro_fk
			INNER JOIN (SELECT setores.setor_pk, setores.organizacao_fk FROM setores) AS setores
			ON setores.setor_pk = ordens_servicos.setor_fk
			WHERE setores.organizacao_fk = id_org AND bairros.bairro_pk = bairro
			AND historicos_ordens.historico_ordem_tempo >= subdate(NOW(), 7);
		
        END IF;

		SELECT MAX(relatorio.id) FROM relatorio INTO teste;
        
        IF ((SELECT relatorio.domingo FROM relatorio WHERE relatorio.id = teste) IS NULL) THEN
			DELETE FROM relatorio WHERE relatorio.id = teste;
		END IF;

    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_bairros;
    
    SELECT * FROM relatorio;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_procedencias_semana` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_procedencias_semana`(IN id_org VARCHAR(10))
BEGIN

	DECLARE procedencia,z,teste INT;
	DECLARE cursor_procedencias CURSOR FOR 
		SELECT procedencias.procedencia_pk FROM procedencias
		WHERE procedencias.organizacao_fk = id_org;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		id INT AUTO_INCREMENT PRIMARY KEY,
        procedencia_nome VARCHAR(45),
        domingo INT,
        segunda INT,
        terca INT,
        quarta INT,
        quinta INT,
        sexta INT,
        sabado INT,
        total INT
    );

    OPEN cursor_procedencias;

    REPEAT

    	FETCH cursor_procedencias INTO procedencia;
		IF z != 1 THEN
	    	INSERT INTO relatorio (procedencia_nome, domingo, segunda, terca,
	        					   quarta, quinta, sexta, sabado, total)
	    	SELECT
	    	procedencias.procedencia_nome,
	    	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS domingo,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 0,1,0)) AS segunda,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS terça,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS quarta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS quinta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS sexta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS sábado,
            count(historicos_ordens.historico_ordem_tempo)
	    	FROM ordens_servicos INNER JOIN
				(SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
				GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
			ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
			INNER JOIN (SELECT procedencias.procedencia_pk,procedencias.procedencia_nome FROM procedencias) AS procedencias
			ON procedencias.procedencia_pk = ordens_servicos.procedencia_fk
			WHERE procedencias.procedencia_pk = procedencia 
			AND historicos_ordens.historico_ordem_tempo >= subdate(NOW(), 7);
		END IF;
        
        SELECT MAX(relatorio.id) FROM relatorio INTO teste;
        IF ((SELECT relatorio.domingo FROM relatorio WHERE relatorio.id = teste) IS NULL) THEN
			DELETE FROM relatorio WHERE relatorio.id = teste;
		END IF;

    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_procedencias;
    
    SELECT * FROM relatorio;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_procedencia_ano` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_procedencia_ano`(IN id_org VARCHAR(10))
BEGIN

	DECLARE z, teste, ano, procedencia INT;
	DECLARE cursor_procedencias CURSOR FOR 
		SELECT procedencias.procedencia_pk FROM procedencias 
		WHERE procedencias.organizacao_fk = id_org;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
    
    SELECT YEAR(CURDATE()) INTO ano;
    
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		id INT AUTO_INCREMENT PRIMARY KEY,
        procedencia_nome VARCHAR(45),
        janeiro INT,
        fevereiro INT,
        marco INT,
        abril INT,
        maio INT,
        junho INT,
        julho INT,
        agosto INT,
        setembro INT,
        outubro INT,
        novembro INT,
        dezembro INT,
        total INT
    );

    OPEN cursor_procedencias;

    REPEAT

    	FETCH cursor_procedencias INTO procedencia;
		IF z != 1 THEN
	    	INSERT INTO relatorio (procedencia_nome, janeiro, fevereiro, marco,
	        					   abril, maio, junho, julho, agosto, setembro,
	        					   outubro, novembro, dezembro, total)
	    	SELECT 
	    	procedencias.procedencia_nome,
	    	sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS janeiro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS fevereiro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS marco,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS abril,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS maio,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS junho,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 7,1,0)) AS julho,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 8,1,0)) AS agosto,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 9,1,0)) AS setembro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 10,1,0)) AS outubro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 11,1,0)) AS novembro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 12,1,0)) AS dezembro,
            count(historicos_ordens.historico_ordem_tempo)
	    	FROM ordens_servicos 
	    	INNER JOIN (SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
				GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
			ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
			INNER JOIN (SELECT procedencias.procedencia_nome, procedencias.procedencia_pk FROM procedencias) AS procedencias
			ON procedencias.procedencia_pk = ordens_servicos.procedencia_fk
 			WHERE procedencias.procedencia_pk = procedencia
            AND YEAR(historicos_ordens.historico_ordem_tempo) = ano;
		
        END IF;

		SELECT MAX(relatorio.id) FROM relatorio INTO teste;
        
        IF ((SELECT relatorio.janeiro FROM relatorio WHERE relatorio.id = teste) IS NULL) THEN
			DELETE FROM relatorio WHERE relatorio.id = teste;
		END IF;

    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_procedencias;
    
    SELECT * FROM relatorio;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_semana` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_semana`(id_org VARCHAR(10))
BEGIN

	SELECT
	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS domingo,
	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 0,1,0)) AS segunda,
	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS terça,
	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS quarta,
	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS quinta,
	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS sexta,
	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS sábado,
    count(historicos_ordens.historico_ordem_tempo) AS total
	FROM ordens_servicos JOIN 
		(SELECT min(historico_ordem_tempo) as historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
		GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
	ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
	INNER JOIN prioridades ON prioridades.prioridade_pk = ordens_servicos.prioridade_fk
	WHERE prioridades.organizacao_fk = id_org AND
	historicos_ordens.historico_ordem_tempo > SUBDATE(NOW(),7);

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_setores_ano` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_setores_ano`(IN id_org VARCHAR(10))
BEGIN

	DECLARE setor,z,teste,ano INT;
	DECLARE cursor_setores CURSOR FOR 
		SELECT setores.setor_pk FROM setores
		WHERE setores.organizacao_fk = id_org;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
    
    SELECT YEAR(CURDATE()) INTO ano;
    
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		id INT AUTO_INCREMENT PRIMARY KEY,
        setor_nome VARCHAR(50),
        janeiro INT,
        fevereiro INT,
        marco INT,
        abril INT,
        maio INT,
        junho INT,
        julho INT,
        agosto INT,
        setembro INT,
        outubro INT,
        novembro INT,
        dezembro INT,
        total INT
    );

    OPEN cursor_setores;

    REPEAT

    	FETCH cursor_setores INTO setor;
		IF z != 1 THEN
	    	INSERT INTO relatorio (setor_nome, janeiro, fevereiro, marco,
	        					   abril, maio, junho, julho, agosto, setembro,
	        					   outubro, novembro, dezembro, total)
	    	SELECT 
	    	setores.setor_nome,
	    	sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS janeiro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS fevereiro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS marco,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS abril,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS maio,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS junho,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 7,1,0)) AS julho,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 8,1,0)) AS agosto,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 9,1,0)) AS setembro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 10,1,0)) AS outubro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 11,1,0)) AS novembro,
			sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 12,1,0)) AS dezembro,
            count(historicos_ordens.historico_ordem_tempo)
	    	FROM ordens_servicos INNER JOIN
				(SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
				GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
			ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
			INNER JOIN (SELECT setores.setor_pk,setores.setor_nome FROM setores) AS setores
			ON setores.setor_pk = ordens_servicos.setor_fk
			WHERE setores.setor_pk = setor
			AND YEAR(historicos_ordens.historico_ordem_tempo) = ano;
		END IF;

		SELECT MAX(relatorio.id) FROM relatorio INTO teste;
        IF ((SELECT relatorio.janeiro FROM relatorio WHERE relatorio.id = teste) IS NULL) THEN
			DELETE FROM relatorio WHERE relatorio.id = teste;
		END IF;

    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_setores;
    
    SELECT * FROM relatorio;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_setores_semana` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_setores_semana`(IN id_org VARCHAR(10))
BEGIN

	DECLARE setor,z,teste INT;
	DECLARE cursor_setores CURSOR FOR 
		SELECT setores.setor_pk FROM setores
		WHERE setores.organizacao_fk = id_org;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		id INT AUTO_INCREMENT PRIMARY KEY,
        setor_nome VARCHAR(50),
        domingo INT,
        segunda INT,
        terca INT,
        quarta INT,
        quinta INT,
        sexta INT,
        sabado INT,
        total INT
    );

    OPEN cursor_setores;

    REPEAT

    	FETCH cursor_setores INTO setor;
		IF z != 1 THEN
	    	INSERT INTO relatorio (setor_nome, domingo, segunda, terca,
	        					   quarta, quinta, sexta, sabado, total)
	    	SELECT
	    	setores.setor_nome,
	    	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS domingo,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 0,1,0)) AS segunda,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS terça,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS quarta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS quinta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS sexta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS sábado,
            count(historicos_ordens.historico_ordem_tempo)
	    	FROM ordens_servicos INNER JOIN
				(SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
				GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
			ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
			INNER JOIN (SELECT setores.setor_pk,setores.setor_nome FROM setores) AS setores
			ON setores.setor_pk = ordens_servicos.setor_fk
			WHERE setores.setor_pk = setor AND historicos_ordens.historico_ordem_tempo >= subdate(NOW(), 7);
		END IF;
        
        SELECT MAX(relatorio.id) FROM relatorio INTO teste;
        IF ((SELECT relatorio.domingo FROM relatorio WHERE relatorio.id = teste) IS NULL) THEN
			DELETE FROM relatorio WHERE relatorio.id = teste;
		END IF;

    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_setores;
    
    SELECT * FROM relatorio;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_tipos_ano` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_tipos_ano`(IN id_org VARCHAR(10))
BEGIN

	DECLARE tipo_servico,z,teste,ano INT;
	DECLARE cursor_tipos CURSOR FOR 
		SELECT tipos_servicos.tipo_servico_pk FROM tipos_servicos
		INNER JOIN departamentos ON departamentos.departamento_pk = tipos_servicos.departamento_fk
		WHERE departamentos.organizacao_fk = id_org;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
    
	SELECT YEAR(CURDATE()) INTO ano;
    
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		id INT AUTO_INCREMENT PRIMARY KEY,
		tipo_servico_pk INT,
        tipo_servico_nome VARCHAR(200),
        janeiro INT,
        fevereiro INT,
        marco INT,
        abril INT,
        maio INT,
        junho INT,
        julho INT,
        agosto INT,
        setembro INT,
        outubro INT,
        novembro INT,
        dezembro INT,
        total INT
    );

    OPEN cursor_tipos;

    REPEAT

    	FETCH cursor_tipos INTO tipo_servico;
		IF z != 1 THEN
    	INSERT INTO relatorio (tipo_servico_pk, tipo_servico_nome, janeiro, fevereiro, marco,
        					   abril, maio, junho, julho, agosto, setembro,
        					   outubro, novembro, dezembro, total)
    	SELECT 
    	tipos.tipo_servico_pk,
    	tipos.tipo_servico_nome,
    	sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS janeiro,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS fevereiro,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS marco,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS abril,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS maio,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS junho,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 7,1,0)) AS julho,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 8,1,0)) AS agosto,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 9,1,0)) AS setembro,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 10,1,0)) AS outubro,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 11,1,0)) AS novembro,
		sum(IF(EXTRACT(MONTH FROM historicos_ordens.historico_ordem_tempo) = 12,1,0)) AS dezembro,
        count(historicos_ordens.historico_ordem_tempo)
    	FROM ordens_servicos INNER JOIN 
			(SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
			GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
		ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
		INNER JOIN (SELECT servicos.servico_pk, servicos.tipo_servico_fk FROM servicos) AS servs
		ON servs.servico_pk = ordens_servicos.servico_fk
		INNER JOIN (SELECT tipos_servicos.tipo_servico_nome, tipos_servicos.tipo_servico_pk FROM tipos_servicos) AS tipos 
		ON tipos.tipo_servico_pk = servs.tipo_servico_fk
		WHERE tipos.tipo_servico_pk = tipo_servico
        AND YEAR(historicos_ordens.historico_ordem_tempo) = ano;
		END IF;

		SELECT MAX(relatorio.id) FROM relatorio INTO teste;
        IF ((SELECT relatorio.janeiro FROM relatorio WHERE relatorio.id = teste) IS NULL) THEN
			DELETE FROM relatorio WHERE relatorio.id = teste;
		END IF;

    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_tipos;
    
    SELECT * FROM relatorio;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_ordens_tipo_semana` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_ordens_tipo_semana`(IN id_org VARCHAR(10))
BEGIN

	DECLARE tipo_servico,z,teste INT;
	DECLARE cursor_tipos CURSOR FOR 
		SELECT tipos_servicos.tipo_servico_pk FROM tipos_servicos
		INNER JOIN departamentos ON departamentos.departamento_pk = tipos_servicos.departamento_fk
		WHERE departamentos.organizacao_fk = id_org;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		id INT AUTO_INCREMENT PRIMARY KEY,
		tipo_servico_pk INT,
        tipo_servico_nome VARCHAR(200),
        domingo INT,
        segunda INT,
        terca INT,
        quarta INT,
        quinta INT,
        sexta INT,
        sabado INT,
        total INT
    );

    OPEN cursor_tipos;

    REPEAT

    	FETCH cursor_tipos INTO tipo_servico;
		IF z != 1 THEN
	    	INSERT INTO relatorio (tipo_servico_pk, tipo_servico_nome, domingo, segunda, terca,
	        					   quarta, quinta, sexta, sabado, total)
	    	SELECT 
	    	tipos.tipo_servico_pk,
	    	tipos.tipo_servico_nome,
	    	sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 6,1,0)) AS domingo,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 0,1,0)) AS segunda,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 1,1,0)) AS terça,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 2,1,0)) AS quarta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 3,1,0)) AS quinta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 4,1,0)) AS sexta,
			sum(IF(WEEKDAY(historicos_ordens.historico_ordem_tempo) = 5,1,0)) AS sábado,
            count(historicos_ordens.historico_ordem_tempo)
	    	FROM ordens_servicos INNER JOIN 
				(SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens 
				GROUP BY historicos_ordens.ordem_servico_fk) AS historicos_ordens 
			ON historicos_ordens.ordem_servico_fk = ordens_servicos.ordem_servico_pk
			INNER JOIN (SELECT servicos.servico_pk, servicos.tipo_servico_fk FROM servicos) AS servs
			ON servs.servico_pk = ordens_servicos.servico_fk
			INNER JOIN (SELECT tipos_servicos.tipo_servico_nome, tipos_servicos.tipo_servico_pk FROM tipos_servicos) AS tipos 
			ON tipos.tipo_servico_pk = servs.tipo_servico_fk
			WHERE tipos.tipo_servico_pk = tipo_servico AND
			historicos_ordens.historico_ordem_tempo > SUBDATE(NOW(),7);
		END IF;
        
        SELECT MAX(relatorio.id) FROM relatorio INTO teste;
        IF ((SELECT relatorio.domingo FROM relatorio WHERE relatorio.id = teste) IS NULL) THEN
			DELETE FROM relatorio WHERE relatorio.id = teste;
		END IF;

    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_tipos;
    
    SELECT * FROM relatorio;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `get_superusuarios` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `get_superusuarios`()
BEGIN
	
		SELECT * FROM superusuarios;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `insert_super_usuario` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `insert_super_usuario`(IN `in_nome` VARCHAR(60), IN `in_cpf` VARCHAR(14), IN `in_status` INT, IN `in_celular` VARCHAR(15), IN `in_email` VARCHAR(60), IN `in_telefone` VARCHAR(14), IN `in_img_perfil` VARCHAR(300))
BEGIN
       DECLARE id_user, id_img INT;

       -- DECLARE pessoa CURSOR FOR SELECT populacao.pessoa_pk FROM populacao WHERE populacao.pessoa_cpf = in_cpf;
       -- DECLARE imagem CURSOR FOR SELECT imagens_perfil.imagem_pk FROM imagens_perfil WHERE imagens_perfil.imagem_caminho = in_img_perfil;
      
       --  OPEN pessoa;
       --  OPEN imagem;

      IF (in_cpf != "") AND (in_nome != "") AND (in_status = 1)  THEN
        BEGIN
            
            INSERT INTO `populacao`(`pessoa_nome`, `pessoa_cpf`, `pessoas_status`) VALUES (in_nome,in_cpf,in_status);
            SET id_user = LAST_INSERT_ID();

            -- FETCH pessoa INTO id_user;

            IF (in_email != "") AND (id_user != "") THEN
               BEGIN
                    INSERT INTO `contatos`(`contato_cel`, `contato_email`, `contato_tel`, `pessoa_fk`) VALUES (in_celular,in_email,in_telefone,id_user);
                END;
              ELSE SELECT 'Dados de contato incorretos! Não é possível inserir';
            END IF;
            
            IF (in_img_perfil != "") THEN

                INSERT INTO `imagens_perfil`(`imagem_caminho`, `pessoa_fk`) VALUES (in_img_perfil, id_user);
                
            END IF;  
                INSERT INTO `super_usuarios`(`pessoa_fk`, `usuario_status`) VALUES (id_user,in_status);
        END;
        ELSE SELECT 'Dados de pessoa incorretos! Não é possível inserir.';
      END IF;

   -- CLOSE pessoa;
   -- CLOSE imagem;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `relatorio_data` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `relatorio_data`(IN qtd_dias INT,IN situacao_id INT)
BEGIN    

	DECLARE os_pk,z INT;
	DECLARE cursor_os CURSOR FOR 
		SELECT ordens_servicos.ordem_servico_pk FROM ordens_servicos;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;

	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		ordem_servico_pk INT PRIMARY KEY,
        ordem_servico_desc VARCHAR(200),
        prioridade_nome VARCHAR(100),
        situacao_nome VARCHAR(50),
        servico_nome VARCHAR(30),
        setor_nome VARCHAR(50),
        logradouro_nome VARCHAR(128),
        local_num INT,
        bairro_nome VARCHAR(80),
        imagem VARCHAR(300)
    );

    OPEN cursor_os;
    REPEAT
    	FETCH cursor_os INTO os_pk;

    	INSERT IGNORE INTO relatorio(ordem_servico_pk, ordem_servico_desc, prioridade_nome, situacao_nome,
            servico_nome, setor_nome, logradouro_nome, local_num, bairro_nome, imagem) 
    	SELECT historicos_ordens.ordem_servico_fk, ordens_servicos.ordem_servico_desc, prioridades.prioridade_nome, 
            situacoes.situacao_nome, servicos.servico_nome, setores.setor_nome, logradouros.logradouro_nome, 
            locais.local_num, bairros.bairro_nome, imagens_situacoes.imagem_situacao_caminho
		FROM historicos_ordens 
        INNER JOIN ordens_servicos ON ordens_servicos.ordem_servico_pk = historicos_ordens.ordem_servico_fk
        INNER JOIN servicos ON servicos.servico_pk = ordens_servicos.servico_fk
        INNER JOIN prioridades ON ordens_servicos.prioridade_fk = prioridades.prioridade_pk
        INNER JOIN situacoes ON situacoes.situacao_pk = historicos_ordens.situacao_fk
        INNER JOIN coordenadas ON coordenadas.coordenada_pk = ordens_servicos.coordenada_fk
        INNER JOIN locais ON locais.local_pk = coordenadas.local_fk
        INNER JOIN logradouros ON logradouros.logradouro_pk = locais.logradouro_fk
        INNER JOIN bairros ON bairros.bairro_pk = locais.bairro_fk
        INNER JOIN setores ON setores.setor_pk = ordens_servicos.setor_fk
        LEFT JOIN imagens_situacoes ON imagens_situacoes.historico_ordem_fk = historicos_ordens.historico_ordem_pk
		WHERE historicos_ordens.historico_ordem_tempo = 
        (select max(historico_ordem_tempo) FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = os_pk) 
        AND historicos_ordens.historico_ordem_tempo > SUBDATE(NOW(), qtd_dias)
        AND ordem_servico_fk = os_pk AND historicos_ordens.situacao_fk = situacao_id;
 	
     	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_os;

    SELECT * FROM relatorio;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `relatorio_departamento` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `relatorio_departamento`(IN departamento_id INT, IN situacao_id INT)
BEGIN    

	DECLARE os_pk,z INT;
	DECLARE cursor_os CURSOR FOR 
		SELECT ordens_servicos.ordem_servico_pk fROM ordens_servicos 
        INNER JOIN servicos ON servicos.servico_pk = ordens_servicos.servico_fk 
        INNER JOIN tipos_servicos ON tipos_servicos.tipo_servico_pk = servicos.tipo_servico_fk
        INNER JOIN departamentos ON departamentos.departamento_pk = tipos_servicos.departamento_fk
        WHERE departamentos.departamento_pk = departamento_id;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;

	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		ordem_servico_pk INT PRIMARY KEY,
        ordem_servico_desc VARCHAR(200),
        prioridade_nome VARCHAR(100),
        situacao_nome VARCHAR(50),
        servico_nome VARCHAR(30),
        setor_nome VARCHAR(50),
        logradouro_nome VARCHAR(128),
        local_num INT,
        bairro_nome VARCHAR(80),
        imagem VARCHAR(300)
    );

    OPEN cursor_os;
    REPEAT
    	FETCH cursor_os INTO os_pk;

    	INSERT IGNORE INTO relatorio(ordem_servico_pk, ordem_servico_desc, prioridade_nome, situacao_nome,
            servico_nome, setor_nome, logradouro_nome, local_num, bairro_nome, imagem) 
    	SELECT historicos_ordens.ordem_servico_fk, ordens_servicos.ordem_servico_desc, prioridades.prioridade_nome, 
            situacoes.situacao_nome, servicos.servico_nome, setores.setor_nome, logradouros.logradouro_nome, 
            locais.local_num, bairros.bairro_nome, imagens_situacoes.imagem_situacao_caminho
		FROM historicos_ordens 
        INNER JOIN ordens_servicos ON ordens_servicos.ordem_servico_pk = historicos_ordens.ordem_servico_fk
        INNER JOIN servicos ON servicos.servico_pk = ordens_servicos.servico_fk
        INNER JOIN prioridades ON ordens_servicos.prioridade_fk = prioridades.prioridade_pk
        INNER JOIN situacoes ON situacoes.situacao_pk = historicos_ordens.situacao_fk
        INNER JOIN coordenadas ON coordenadas.coordenada_pk = ordens_servicos.coordenada_fk
        INNER JOIN locais ON locais.local_pk = coordenadas.local_fk
        INNER JOIN logradouros ON logradouros.logradouro_pk = locais.logradouro_fk
        INNER JOIN bairros ON bairros.bairro_pk = locais.bairro_fk
        INNER JOIN setores ON setores.setor_pk = ordens_servicos.setor_fk
        LEFT JOIN imagens_situacoes ON imagens_situacoes.historico_ordem_fk = historicos_ordens.historico_ordem_pk
		WHERE historico_ordem_tempo = (select max(historico_ordem_tempo) 
		FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = os_pk) 
        AND ordem_servico_fk = os_pk AND historicos_ordens.situacao_fk = situacao_id;
 	
     	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_os;

    SELECT * FROM relatorio;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `relatorio_servico` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `relatorio_servico`(IN servico_id INT, IN situacao_id INT)
BEGIN    

	DECLARE os_pk,z INT;
	DECLARE cursor_os CURSOR FOR 
		SELECT ordens_servicos.ordem_servico_pk 
		FROM ordens_servicos INNER JOIN servicos 
		ON ordens_servicos.servico_fk = servicos.servico_pk
		WHERE ordens_servicos.servico_fk = servico_id;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;

	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		ordem_servico_pk INT PRIMARY KEY,
        ordem_servico_desc VARCHAR(200),
        prioridade_nome VARCHAR(100),
        situacao_nome VARCHAR(50),
        servico_nome VARCHAR(30),
        setor_nome VARCHAR(50),
        logradouro_nome VARCHAR(128),
        local_num INT,
        bairro_nome VARCHAR(80),
        imagem VARCHAR(300)
    );

    OPEN cursor_os;
    REPEAT
    	FETCH cursor_os INTO os_pk;

    	INSERT IGNORE INTO relatorio(ordem_servico_pk, ordem_servico_desc, prioridade_nome, situacao_nome,
            servico_nome, setor_nome, logradouro_nome, local_num, bairro_nome, imagem) 
    	SELECT historicos_ordens.ordem_servico_fk, ordens_servicos.ordem_servico_desc, prioridades.prioridade_nome, 
            situacoes.situacao_nome, servicos.servico_nome, setores.setor_nome, logradouros.logradouro_nome, 
            locais.local_num, bairros.bairro_nome, imagens_situacoes.imagem_situacao_caminho
		FROM historicos_ordens 
        INNER JOIN ordens_servicos ON ordens_servicos.ordem_servico_pk = historicos_ordens.ordem_servico_fk
        INNER JOIN servicos ON servicos.servico_pk = ordens_servicos.servico_fk
        INNER JOIN prioridades ON ordens_servicos.prioridade_fk = prioridades.prioridade_pk
        INNER JOIN situacoes ON situacoes.situacao_pk = historicos_ordens.situacao_fk
        INNER JOIN coordenadas ON coordenadas.coordenada_pk = ordens_servicos.coordenada_fk
        INNER JOIN locais ON locais.local_pk = coordenadas.local_fk
        INNER JOIN logradouros ON logradouros.logradouro_pk = locais.logradouro_fk
        INNER JOIN bairros ON bairros.bairro_pk = locais.bairro_fk
        INNER JOIN setores ON setores.setor_pk = ordens_servicos.setor_fk
        LEFT JOIN imagens_situacoes ON imagens_situacoes.historico_ordem_fk = historicos_ordens.historico_ordem_pk
		WHERE historico_ordem_tempo = (select max(historico_ordem_tempo) 
		FROM historicos_ordens WHERE historicos_ordens.ordem_servico_fk = os_pk) 
        AND ordem_servico_fk = os_pk AND historicos_ordens.situacao_fk = situacao_id;
 	
     	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_os;

    SELECT * FROM relatorio;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `relatorio_setor` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `relatorio_setor`(IN setor_id INT, IN situacao_id INT)
BEGIN    

	DECLARE os_pk,z INT;
	DECLARE cursor_os CURSOR FOR 
		SELECT ordens_servicos.ordem_servico_pk 
		FROM ordens_servicos INNER JOIN setores 
		ON ordens_servicos.setor_fk = setores.setor_pk
		WHERE ordens_servicos.setor_fk = setor_id;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;

	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
		ordem_servico_pk INT PRIMARY KEY,
        ordem_servico_desc VARCHAR(200),
        prioridade_nome VARCHAR(100),
        situacao_nome VARCHAR(50),
        servico_nome VARCHAR(30),
        setor_nome VARCHAR(50),
        logradouro_nome VARCHAR(128),
        local_num INT,
        bairro_nome VARCHAR(80),
        imagem VARCHAR(300)
    );

    OPEN cursor_os;
    REPEAT
    	FETCH cursor_os INTO os_pk;

    	INSERT IGNORE INTO relatorio(ordem_servico_pk, ordem_servico_desc, prioridade_nome, situacao_nome,
            servico_nome, setor_nome, logradouro_nome, local_num, bairro_nome, imagem) 
    	SELECT historicos_ordens.ordem_servico_fk, ordens_servicos.ordem_servico_desc, prioridades.prioridade_nome, 
            situacoes.situacao_nome, servicos.servico_nome, setores.setor_nome, logradouros.logradouro_nome, 
            locais.local_num, bairros.bairro_nome, imagens_situacoes.imagem_situacao_caminho
		FROM historicos_ordens 
        INNER JOIN ordens_servicos ON ordens_servicos.ordem_servico_pk = historicos_ordens.ordem_servico_fk
        INNER JOIN servicos ON servicos.servico_pk = ordens_servicos.servico_fk
        INNER JOIN prioridades ON ordens_servicos.prioridade_fk = prioridades.prioridade_pk
        INNER JOIN situacoes ON situacoes.situacao_pk = historicos_ordens.situacao_fk
        INNER JOIN coordenadas ON coordenadas.coordenada_pk = ordens_servicos.coordenada_fk
        INNER JOIN locais ON locais.local_pk = coordenadas.local_fk
        INNER JOIN logradouros ON logradouros.logradouro_pk = locais.logradouro_fk
        INNER JOIN bairros ON bairros.bairro_pk = locais.bairro_fk
        INNER JOIN setores ON setores.setor_pk = ordens_servicos.setor_fk
        LEFT JOIN imagens_situacoes on imagens_situacoes.historico_ordem_fk = historicos_ordens.historico_ordem_pk
		WHERE historico_ordem_tempo = (select max(historico_ordem_tempo) 
		FROM historicos_ordens WHERE ordem_servico_fk = os_pk) 
        AND ordem_servico_fk = os_pk AND historicos_ordens.situacao_fk = situacao_id;
 	
     	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_os;

    SELECT * FROM relatorio;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `taxa_prioridade` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`admin_prudenco`@`%` PROCEDURE `taxa_prioridade`(IN id_org VARCHAR(10))
BEGIN

	DECLARE z, teste, ano, prioridade INT;
	DECLARE cursor_prioridades CURSOR FOR 
		SELECT prioridades.prioridade_pk FROM prioridades
		WHERE prioridades.organizacao_fk = id_org;
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET z = 1;
	SET z=0;
    
	DROP TABLE IF EXISTS relatorio;

	CREATE TEMPORARY TABLE relatorio(
        prioridade_nome VARCHAR(45),
        taxa TIME
    );

    OPEN cursor_prioridades;

    REPEAT

    	FETCH cursor_prioridades INTO prioridade;
		IF z != 1 THEN
	    	INSERT INTO relatorio (prioridade_nome, taxa)
	    	SELECT 
	    	prioridades.prioridade_nome,
	    	sec_to_time(avg(time_to_sec(timediff(final.historico_ordem_tempo, inicial.historico_ordem_tempo)))) 
	    	FROM ordens_servicos
			INNER JOIN (SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens WHERE situacao_fk = 22) AS inicial
			ON inicial.ordem_servico_fk =  ordens_servicos.ordem_servico_pk
			INNER JOIN (SELECT historico_ordem_tempo, ordem_servico_fk FROM historicos_ordens WHERE situacao_fk = 32) AS final
			ON final.ordem_servico_fk =  ordens_servicos.ordem_servico_pk
            INNER JOIN (SELECT prioridades.prioridade_nome, prioridades.prioridade_pk FROM prioridades) AS prioridades
            ON prioridades.prioridade_pk = ordens_servicos.prioridade_fk
			WHERE ordens_servicos.prioridade_fk = prioridade;
		
        END IF;


    	UNTIL (z=1)
    END REPEAT;
    CLOSE cursor_prioridades;
    
    SELECT * FROM relatorio;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-04-02  8:22:34
