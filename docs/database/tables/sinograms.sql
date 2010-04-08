--
-- Table structure for table `sinograms`
--
-- TODO for Allan
--

CREATE TABLE IF NOT EXISTS `sinograms` (
  `id` int(11) NOT NULL,
  `utf` varchar(8) collate utf8_unicode_ci NOT NULL COMMENT 'code utf8 du caractere',
  `glyph` char(10) collate utf8_unicode_ci NOT NULL COMMENT 'caract�re en lui m�me',
  `strokes` tinyint(3) unsigned default NULL COMMENT 'nombre de trait composant le caract�re',
  `english` text collate utf8_unicode_ci COMMENT 'traduction du caract�re',
  `chin-trad` char(10) collate utf8_unicode_ci default NULL COMMENT '�quivalent traditionelle du caract�re',
  `chin-simpl` char(10) collate utf8_unicode_ci default NULL COMMENT '�quivalent simplifi� du caract�re',
  `chin-pinyin` varchar(255) character set latin1 default NULL COMMENT 'pinyin (chinois) du caract�re',
  `jap-on` varchar(255) character set latin1 default NULL COMMENT 'prononciation On du caract�re',
  `jap-kun` varchar(255) character set latin1 default NULL COMMENT 'prononciation Kun du caract�re',
  `frequency` double NOT NULL default '0' COMMENT 'frequence du caract�re',
  `checked` tinyint(1) NOT NULL,
  `subcharacterslist` varchar(255) collate utf8_unicode_ci default NULL,
  `usedByList` varchar(255) collate utf8_unicode_ci default NULL,
  KEY `glyph_index` (`glyph`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;