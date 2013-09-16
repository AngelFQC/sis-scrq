SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `ENFERMERA`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ENFERMERA` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(255) NOT NULL ,
  `dni` VARCHAR(8) NOT NULL ,
  `usuario` VARCHAR(255) NOT NULL ,
  `password` VARCHAR(32) NOT NULL ,
  `admin` TINYINT(1)  NOT NULL DEFAULT '0' ,
  `estado` TINYINT(1)  NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `dni` (`dni` ASC) ,
  UNIQUE INDEX `usuario_UNIQUE` (`usuario` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 8
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `SECCION`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `SECCION` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `titulo` VARCHAR(255) NOT NULL ,
  `descripcion` TEXT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 49
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `GRUPO`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `GRUPO` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `descripcion` TEXT NULL DEFAULT NULL ,
  `SECCION_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_GRUPO_SECCION1` (`SECCION_id` ASC) ,
  CONSTRAINT `fk_GRUPO_SECCION1`
    FOREIGN KEY (`SECCION_id` )
    REFERENCES `SECCION` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 97
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `PACIENTE`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `PACIENTE` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `apellido_paterno` VARCHAR(255) NOT NULL ,
  `apellido_materno` VARCHAR(255) NOT NULL ,
  `nombres` VARCHAR(255) NOT NULL ,
  `dni` VARCHAR(8) NOT NULL ,
  `cama` INT(2) NOT NULL ,
  `registro` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `dni_UNIQUE` (`dni` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 33
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `HOJA_VALORACION`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `HOJA_VALORACION` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `turno` CHAR(1) NOT NULL ,
  `fecha` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `ENFERMERA_id` INT(10) UNSIGNED NOT NULL ,
  `PACIENTE_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_HOJA_VALORACION_ENFERMERA1` (`ENFERMERA_id` ASC) ,
  INDEX `fk_HOJA_VALORACION_PACIENTE1` (`PACIENTE_id` ASC) ,
  CONSTRAINT `fk_HOJA_VALORACION_ENFERMERA1`
    FOREIGN KEY (`ENFERMERA_id` )
    REFERENCES `ENFERMERA` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_HOJA_VALORACION_PACIENTE1`
    FOREIGN KEY (`PACIENTE_id` )
    REFERENCES `PACIENTE` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 19
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ITEM`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ITEM` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` TEXT NOT NULL ,
  `GRUPO_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_ITEM_GRUPO1` (`GRUPO_id` ASC) ,
  CONSTRAINT `fk_ITEM_GRUPO1`
    FOREIGN KEY (`GRUPO_id` )
    REFERENCES `GRUPO` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 207
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `OPCION`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `OPCION` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `nombre` VARCHAR(255) NOT NULL ,
  `ITEM_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`, `ITEM_id`) ,
  INDEX `fk_OPCION_ITEM1` (`ITEM_id` ASC) ,
  CONSTRAINT `fk_OPCION_ITEM1`
    FOREIGN KEY (`ITEM_id` )
    REFERENCES `ITEM` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 357
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `RESPUESTA_ABIERTA`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `RESPUESTA_ABIERTA` (
  `HOJA_VALORACION_id` INT(10) UNSIGNED NOT NULL ,
  `ITEM_id` INT(10) UNSIGNED NOT NULL ,
  `respuesta` TEXT NOT NULL ,
  PRIMARY KEY (`HOJA_VALORACION_id`, `ITEM_id`) ,
  INDEX `fk_OPCION_ABIERTA_HOJA_VALORACION1` (`HOJA_VALORACION_id` ASC) ,
  INDEX `fk_OPCION_ABIERTA_ITEM1` (`ITEM_id` ASC) ,
  CONSTRAINT `fk_OPCION_ABIERTA_HOJA_VALORACION1`
    FOREIGN KEY (`HOJA_VALORACION_id` )
    REFERENCES `HOJA_VALORACION` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_OPCION_ABIERTA_ITEM1`
    FOREIGN KEY (`ITEM_id` )
    REFERENCES `ITEM` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `RESPUESTA_CERRADA`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `RESPUESTA_CERRADA` (
  `HOJA_VALORACION_id` INT(10) UNSIGNED NOT NULL ,
  `OPCION_id` INT(10) UNSIGNED NOT NULL ,
  `OPCION_ITEM_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`HOJA_VALORACION_id`, `OPCION_id`, `OPCION_ITEM_id`) ,
  INDEX `fk_RESPUESTA_CERRADA_OPCION1` (`OPCION_id` ASC, `OPCION_ITEM_id` ASC) ,
  CONSTRAINT `fk_OPCION_CERRADA_HOJA_VALORACION1`
    FOREIGN KEY (`HOJA_VALORACION_id` )
    REFERENCES `HOJA_VALORACION` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_RESPUESTA_CERRADA_OPCION1`
    FOREIGN KEY (`OPCION_id` , `OPCION_ITEM_id` )
    REFERENCES `OPCION` (`id` , `ITEM_id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `ENFERMERA`
-- -----------------------------------------------------
START TRANSACTION;

-- Cambiar datos de usuario administrador
INSERT INTO `ENFERMERA` (`id`, `nombre`, `dni`, `usuario`, `password`, `admin`, `estado`) VALUES (NULL, 'Laura Mendoza', '00000000', 'lmendoza', 'a298d32d61d05cb542b7ea70cedcb1d2', 1, 1);


--
-- Volcado de datos para la tabla `seccion`
--

INSERT INTO `seccion` (`id`, `titulo`, `descripcion`) VALUES
(25, 'CogniciÃ³n', NULL),
(26, 'Actividad / Reposo', NULL),
(27, 'Confort', NULL),
(28, 'NutriciÃ³n', NULL),
(29, 'EliminaciÃ³n', NULL),
(36, 'Afrontamiento', NULL),
(47, 'Seguridad / ProtecciÃ³n', NULL),
(48, 'EvaluaciÃ³n', NULL);

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `descripcion`, `SECCION_id`) VALUES
(59, 'Orientado', 25),
(60, 'Estado emocional', 25),
(61, 'Actividad circulatoria', 26),
(62, 'Actividad respiratoria', 26),
(63, 'Movilidad de MMSS e MMII', 26),
(64, 'Fuerza muscular', 26),
(65, 'SueÃ±o', 26),
(66, 'Dolor', 27),
(67, 'Apetito', 28),
(68, 'NaÃºseas, pirosis, vÃ³mitos', 28),
(69, 'Presencia de sonda', 28),
(70, 'Abdomen', 28),
(71, 'HidrataciÃ³n', 28),
(72, 'Edema', 28),
(73, 'ColoraciÃ³n de la piel', 28),
(74, 'DescripciÃ³n AntropomÃ©trica', 28),
(75, 'AlteraciÃ³n de Frecuencia Urinaria', 29),
(82, 'ReacciÃ³n frente a cirugÃ­as, tratamiento, enfermedad, curaciones, cambios o  acontecimientos relevantes', 36),
(93, 'Integridad cutÃ¡nea, estado de la piel y mucosas, UPP.', 47),
(94, 'CVC', 47),
(95, 'Valoracion escala de Norton', 47),
(96, 'Evaluacion', 48);

--
-- Volcado de datos para la tabla `item`
--

INSERT INTO `item` (`id`, `nombre`, `GRUPO_id`) VALUES
(147, '', 59),
(148, '', 60),
(149, 'P.A', 61),
(150, 'F.C.', 61),
(151, 'Pulso', 61),
(152, 'Zona', 61),
(153, '', 61),
(154, 'Injuria inhalatoria', 62),
(155, 'F. respiratoria', 62),
(156, '', 62),
(157, 'Tos', 62),
(158, 'Secreciones', 62),
(159, 'Especificar', 62),
(160, '', 62),
(161, 'Oxigenoterapia', 62),
(162, 'Lts x''', 62),
(163, 'SatO2', 62),
(164, '', 63),
(165, '', 64),
(166, 'Horas de sueÃ±o', 65),
(167, 'Problemas para dormir', 65),
(168, '', 66),
(169, '', 66),
(170, 'Dieta', 67),
(171, 'VÃ­a', 67),
(172, '', 68),
(173, 'Cant.', 68),
(174, '', 69),
(175, 'Tolerancia', 69),
(176, '', 70),
(177, 'Ruidos hidroaÃ©reos', 70),
(178, '', 71),
(179, 'Piel Cabelluda', 71),
(180, '', 72),
(181, 'Zona', 72),
(182, '', 73),
(183, 'Peso', 74),
(184, 'Talla', 74),
(185, 'Diuresis', 75),
(186, 'Aspecto', 75),
(187, 'Color', 75),
(188, 'Sonda', 75),
(189, 'Fecha', 75),
(190, 'NÂ° deposiciones', 75),
(191, 'Aspecto', 75),
(192, 'Color', 75),
(194, 'Escala facial Wong-Baker', 82),
(195, 'Lesiones Termicas', 93),
(196, 'UPP', 93),
(197, 'Zona', 93),
(198, 'Estadio', 93),
(199, '', 94),
(200, 'Condicion Fisica', 95),
(201, 'Estado Mental', 95),
(202, 'Actividad', 95),
(203, 'Movilidad', 95),
(204, 'Incontinencia', 95),
(205, 'Puntaje', 95),
(206, '', 96);

--
-- Volcado de datos para la tabla `opcion`
--

INSERT INTO `opcion` (`id`, `nombre`, `ITEM_id`) VALUES
(239, 'Tiempo', 147),
(240, 'Espacio', 147),
(241, 'Persona', 147),
(242, 'Tranquilo', 148),
(243, 'Introvertido', 148),
(244, 'Negativo', 148),
(245, 'Temeroso', 148),
(246, 'Irritable', 148),
(247, 'Indiferentes', 148),
(248, 'Regular', 153),
(249, 'Irregular', 153),
(250, 'TensiÃ³n baja', 153),
(251, 'TensiÃ³n alta', 153),
(252, 'Si', 154),
(253, 'No', 154),
(254, 'Regular', 156),
(255, 'Irregular', 156),
(256, 'Si', 157),
(257, 'No', 157),
(258, 'Si', 158),
(259, 'No', 158),
(260, 'Sibilancias', 160),
(261, 'Estertores', 160),
(262, 'Si', 161),
(263, 'No', 161),
(264, 'Contractura', 164),
(265, 'Flacidez', 164),
(266, 'ParÃ¡lisis', 164),
(267, 'Conservada', 165),
(268, 'Disminuida', 165),
(269, 'Ninguno', 167),
(270, 'Despierta temprano', 167),
(271, 'Insomnio', 167),
(272, 'Si', 168),
(273, 'No', 168),
(274, 'Leve', 169),
(275, 'Moderado', 169),
(276, 'Severo', 169),
(277, 'Parental', 171),
(278, 'Enteral', 171),
(279, 'Oral', 171),
(280, 'Vomito', 172),
(281, 'NaÃºseas', 172),
(282, 'SNG', 174),
(283, 'SNY', 174),
(284, 'Normal', 176),
(285, 'Digstendido', 176),
(286, 'Doloroso', 176),
(287, 'Globuloso', 176),
(288, 'Aumentados', 177),
(289, 'Disminuidos', 177),
(290, 'Ausentes', 177),
(291, 'Seca', 178),
(292, 'Turgente', 178),
(293, 'Descamada', 178),
(294, 'Ralo', 179),
(295, 'Despigmentado', 179),
(296, 'Ausentes', 179),
(297, 'Si', 180),
(298, 'No', 180),
(299, 'Normal', 182),
(300, 'Rubor', 182),
(301, 'Rash', 182),
(302, 'Cianosis', 182),
(303, 'Equimosis', 182),
(304, 'Necrosis', 182),
(305, 'Foley', 188),
(306, 'Colector', 188),
(307, 'PaÃ±al', 188),
(314, 'No presenta dolor', 194),
(315, 'Duele un poco', 194),
(316, 'Duele un poco mas', 194),
(317, 'Duele mucho mas', 194),
(318, 'Duele casi todo', 194),
(319, 'Dolor inaguantable', 194),
(320, 'Limpias', 195),
(321, 'Infectadas', 195),
(322, 'Contaminadas', 195),
(323, 'Cictrizacion', 195),
(324, 'Prurito', 195),
(325, 'Si', 196),
(326, 'No', 196),
(327, 'I', 198),
(328, 'II', 198),
(329, 'III', 198),
(330, 'IV', 198),
(331, 'Si', 199),
(332, 'No', 199),
(333, 'Buena   4', 200),
(334, 'Regular   3', 200),
(335, 'Pobre   2', 200),
(336, 'Muy mala   1', 200),
(337, 'Orientado   4', 201),
(338, 'ApÃ¡tico   3', 201),
(339, 'Confuso   2', 201),
(340, 'Inconsciente   1', 201),
(341, 'Deambula   4', 202),
(342, 'Deambula con Ayuda   3', 202),
(343, 'Cama / Silla   2', 202),
(344, 'Encamado  1', 202),
(345, 'Conservada   4', 203),
(346, 'Disminuida   3', 203),
(347, 'Muy limitada   2', 203),
(348, 'InmÃ³vil  1', 203),
(349, 'Control   4', 204),
(350, 'Ocasional   3', 204),
(351, 'Urinaria o Fecal   2', 204),
(352, 'Urinaria o Fecal  1', 204),
(353, 'Riesgo muy alto   5 a 9', 205),
(354, 'Riesgo Alto   10 a 12', 205),
(355, 'Riesgo medio   13 a 14', 205),
(356, 'Riesgo minimo o no riesgo  MÃ¡s de 14', 205);

COMMIT;
