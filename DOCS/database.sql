DROP TABLE IF EXISTS `cms_blog`;
CREATE TABLE `cms_blog` (
  `blogId` mediumint(8) unsigned NOT NULL default '0',
  `postDate` date NOT NULL default '0000-00-00',
  `title` varchar(255) NOT NULL default '',
  `eventStart` date NOT NULL default '0000-00-00',
  `eventStop` date NOT NULL default '0000-00-00',
  `summary` text NOT NULL,
  `sticky` tinyint(1) unsigned NOT NULL default '0',
  `publish` tinyint(1) unsigned NOT NULL default '0',
  `private` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`blogId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cms_blog` VALUES(19, '2009-03-26', 'Test premier article', '9999-00-00', '9999-00-00', '', 0, 1, 1);

DROP TABLE IF EXISTS `cms_contact`;
CREATE TABLE `cms_contact` (
  `contactId` mediumint(8) unsigned NOT NULL,
  `pos` smallint(5) unsigned NOT NULL,
  `photo` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`contactId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_content`;
CREATE TABLE `cms_content` (
  `contentId` int(10) unsigned NOT NULL auto_increment,
  `pageId` mediumint(8) unsigned NOT NULL default '0',
  `handle` varchar(60) NOT NULL default '',
  `shortcut` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `options` text NOT NULL,
  `authorId` mediumint(8) unsigned NOT NULL default '0',
  `lastChangeDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`contentId`),
  KEY `pageId` (`pageId`),
  KEY `handle` (`handle`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cms_content` VALUES(10, 11, '', '', '<h1>\r\n	TaskFreak! CMS</h1>\r\n<p>\r\n	TFCMS est la solution id&eacute;ale&nbsp;pour les associations et entreprises qui souhaitent&nbsp;cr&eacute;er un site internet &eacute;volutif et interactif :</p>\r\n<ul>\r\n	<li>\r\n		Optimisez votre pr&eacute;sence sur&nbsp;internet en cr&eacute;ant facilement autant de pages d&#39;information que vous le souhaitez</li>\r\n	<li>\r\n		Faites vivre votre communaut&eacute; &agrave; travers&nbsp;votre actualit&eacute; et laissez les visiteurs commenter vos articles</li>\r\n	<li>\r\n		Am&eacute;liorez&nbsp;votre communication interne&nbsp;gr&acirc;ce &agrave;&nbsp;un solide&nbsp;outil de gestion de t&acirc;ches</li>\r\n</ul>\r\n<h2>\r\n	Qu&#39;est-ce qu&#39;un CMS ?</h2>\r\n<p>\r\n	Un CMS (Content Management System) est un outil de gestion de contenu. Il permet&nbsp;au propri&eacute;taire du site de cr&eacute;er, modifier, supprimer&nbsp;autant de page d&#39;information qu&#39;il le&nbsp;souhaite.</p>\r\n<p>\r\n	TaskFreak CMS inclu&nbsp;&eacute;galement&nbsp;plusieurs modules pour vos besoins sp&eacute;cifiques :</p>\r\n<ul>\r\n	<li>\r\n		Blog&nbsp;: Toute votre&nbsp;actualit&eacute;, sous forme d&#39;articles ou&nbsp;de calendrier d&#39;&eacute;v&eacute;nements</li>\r\n	<li>\r\n		Galerie photo : cr&eacute;ez votre album photos</li>\r\n	<li>\r\n		Catalogue de produits : vendez&nbsp;vos produits en ligne</li>\r\n	<li>\r\n		Trombinoscope : affichez la liste de vos contacts ou collaborateurs</li>\r\n	<li>\r\n		Formulaire contact : cr&eacute;ez vos formulaires pour am&eacute;liorer la communication avec les visiteurs de votre site</li>\r\n	<li>\r\n		Newsletter : g&eacute;rer une liste de diffusion et envoyez&nbsp;des lettres d&#39;informations par e-mail</li>\r\n</ul>\r\n<p>\r\n	TaskFreak CMS est&nbsp;un syst&egrave;me qui &eacute;volue&nbsp;continuellement, ce ne sont ici que quelques exemples de modules disponibles.</p>\r\n<h2>\r\n	Outil de gestion de projet</h2>\r\n<p>\r\n	Il existe d&eacute;j&agrave; de nombreux&nbsp;syst&egrave;mes de gestion de contenu, mais la particularit&eacute; de TaskFreak CMS est d&#39;int&eacute;grer l&#39;outil de gestion de t&acirc;ches TaskFreak!</p>\r\n<p>\r\n	TaskFreak! a &eacute;t&eacute; t&eacute;l&eacute;charg&eacute; plus de 15000 fois depuis sa cr&eacute;ation en 2005. Une grande communaut&eacute; de d&eacute;veloppeurs&nbsp;soutiennent et d&eacute;veloppent ce projet. Il s&#39;agit donc&nbsp;d&#39;un outil abouti permettant aux&nbsp;&eacute;quipes de travail d&#39;optimiser&nbsp;l&#39;organisation de leur activit&eacute;.</p>\r\n', 'a:5:{s:13:"option_layout";s:10:"1_top_left";s:18:"option_sitemap_inc";i:0;s:18:"option_sitemap_idx";i:1;s:15:"option_blog_inc";i:0;s:15:"option_blog_tag";s:0:"";}', 1, '2010-09-22 21:43:18');
INSERT INTO `cms_content` VALUES(169, 50, '', '', '<h1>\r\n	Actualit&eacute;s</h1>\r\n', 'a:9:{s:13:"option_layout";s:0:"";s:19:"option_date_in_list";i:1;s:21:"option_author_in_list";i:0;s:20:"option_intro_in_item";i:0;s:19:"option_date_in_item";i:1;s:21:"option_author_in_item";i:0;s:17:"option_order_type";i:0;s:16:"option_page_only";i:0;s:17:"option_pagination";i:10;}', 1, '2010-09-16 14:25:48');
INSERT INTO `cms_content` VALUES(167, 48, '', '', '<h1>\r\n	Outil de gestion de t&acirc;ches</h1>\r\n<p>\r\n	TaskFreak! est un outil open source d&eacute;velopp&eacute; depuis 2005. Largement utilis&eacute; par les particuliers et professionnels, c&#39;est&nbsp;un outil simple d&#39;utilisation permettant de plannifier et de suivre l&#39;&eacute;volution de projets sans se perdre dans les m&eacute;andres d&#39;un syst&egrave;me&nbsp;de gestion de projet lourd et complexe.</p>\r\n<p>\r\n	TaskFreak! est &eacute;galement&nbsp;d&eacute;j&agrave;&nbsp;utilis&eacute; par des&nbsp;comit&eacute;s&nbsp;d&#39;administration&nbsp;pour g&eacute;rer le quotidien de leur entreprise&nbsp;ou association. C&#39;est un outil id&eacute;al pour am&eacute;liorer la communication interne au sein de votre groupe de travail.</p>\r\n<h2>\r\n	D&eacute;monstration live</h2>\r\n<p>\r\n	Pour acc&eacute;der &agrave; la d&eacute;monstration de TaskFreak!, lui-m&ecirc;me&nbsp;int&eacute;gr&eacute; &agrave; ce&nbsp;site, identifiez-vous avec les codes&nbsp;suivants :</p>\r\n<ul>\r\n	<li>\r\n		utilisateur : john</li>\r\n	<li>\r\n		mot de passe : john</li>\r\n</ul>\r\n<p>\r\n	Vous serez&nbsp;alors&nbsp;identifi&eacute;&nbsp;comme&nbsp;simple membre : vous ne pourrez&nbsp;pas modifier la configuration, ni&nbsp;cr&eacute;er de nouveau&nbsp;projet.</p>\r\n<h2>\r\n	Pr&eacute;sentation et copies d&#39;&eacute;crans</h2>\r\n<p>\r\n	Ci-dessous quelques copies d&#39;&eacute;cran permettant d&#39;avoir un aper&ccedil;u des fonctionnalit&eacute;s de l&#39;outil.</p>\r\n', 'a:7:{s:13:"option_layout";s:0:"";s:11:"option_mode";i:0;s:16:"option_page_size";i:0;s:14:"option_img_wdh";i:640;s:14:"option_img_hgt";i:480;s:14:"option_thb_wdh";i:200;s:14:"option_thb_hgt";i:150;}', 1, '2010-09-17 10:15:44');
INSERT INTO `cms_content` VALUES(168, 49, '', '', '<h1>\r\n	Page r&eacute;serv&eacute;e aux membres</h1>\r\n<p>\r\n	Cette&nbsp;page n&#39;est accessible qu&#39;aux membres&nbsp;du site.</p>\r\n', 'a:5:{s:13:"option_layout";s:0:"";s:18:"option_sitemap_inc";i:0;s:18:"option_sitemap_idx";i:1;s:15:"option_blog_inc";i:0;s:15:"option_blog_tag";s:0:"";}', 1, '2010-09-22 21:46:19');
INSERT INTO `cms_content` VALUES(173, 51, '', '', '<h1>\r\n	Systeme de gestion de contenu (CMS)</h1>\r\n<p>\r\n	Le&nbsp;but&nbsp;d&#39;un&nbsp;CMS&nbsp;est simple&nbsp;: permettre au propri&eacute;taire du site internet de g&eacute;rer le contenu de son site sans connaissances techniques avanc&eacute;es. C&#39;est gr&acirc;ce&nbsp;a une interface d&#39;administration simple et intuitive que TaskFreak! CMS&nbsp;atteind cet&nbsp;objectif.</p>\r\n<h3>\r\n	Ce site est lui-m&ecirc;me&nbsp;bas&eacute; sur&nbsp;TaskFreak! CMS : Testez-le !</h3>\r\n<p>\r\n	<span class="Apple-style-span" style="white-space: nowrap;">Testez imm&eacute;diatement</span>&nbsp;quelques unes des&nbsp;fonctions du site en vous identifiant en tant que&nbsp;<em>mod&eacute;rateur</em>&nbsp;gr&acirc;ce aux&nbsp;codes suivants :</p>\r\n<ul>\r\n	<li>\r\n		utilisateur : lilly</li>\r\n	<li>\r\n		mot de passe lilly</li>\r\n</ul>\r\n<p>\r\n	Vous aurez alors acc&egrave;s &agrave; l&#39;interface d&#39;administration.<br />\r\n	Mais pour&nbsp;ne pas alt&eacute;rer tout le contenu du site,&nbsp;votre acc&egrave;s est restreint :&nbsp;vous n&#39;avez pas tous les droits d&#39;acc&egrave;s et de modification.</p>\r\n<h2>\r\n	Pr&eacute;sentation et fonctionnalit&eacute;s du syst&egrave;me</h2>\r\n<p>\r\n	Pour une pr&eacute;sentation plus compl&egrave;te du syst&egrave;me, obtenez&nbsp;plus d&#39;information sur&nbsp;ses fonctionnalit&eacute;s en cliquant&nbsp;sur chacune des copies d&#39;&eacute;cran ci-dessous.</p>\r\n', 'a:7:{s:13:"option_layout";s:0:"";s:11:"option_mode";i:0;s:16:"option_page_size";i:0;s:14:"option_img_wdh";i:640;s:14:"option_img_hgt";i:480;s:14:"option_thb_wdh";i:200;s:14:"option_thb_hgt";i:150;}', 1, '2010-09-16 20:14:26');
INSERT INTO `cms_content` VALUES(176, 50, 'blog', 'premier-article', '<p>\r\n	<span class="Apple-style-span" style="white-space: nowrap;">Editer cet article</span></p>\r\n', 'a:4:{s:13:"option_layout";s:11:"3_top_right";s:21:"option_comments_allow";i:0;s:23:"option_comments_private";i:0;s:15:"option_is_event";i:0;}', 1, '2010-09-22 21:44:26');
INSERT INTO `cms_content` VALUES(174, 52, 'confirm', '', '', 'a:1:{s:13:"option_layout";s:0:"";}', 1, '2010-09-16 18:57:52');
INSERT INTO `cms_content` VALUES(175, 52, '', '', '<h1>\r\n	Demande de devis</h1>\r\n<p>\r\n	TaskFreak! CMS est une&nbsp;solution open source, c&#39;est-&agrave;-dire&nbsp;en t&eacute;l&eacute;chargement&nbsp;gratuit.</p>\r\n<p>\r\n	M&ecirc;me si la mise&nbsp;&agrave; jour et l&#39;utilisation&nbsp;d&#39;un site bas&eacute; sur&nbsp;TaskFreak! CMS&nbsp;est chose ais&eacute;e, la cr&eacute;ation&nbsp;du site en lui-m&ecirc;me (design, int&eacute;gration, conseils, optimisation) n&eacute;cessite&nbsp;n&eacute;anmoins de solides connaissances techniques. La soci&eacute;t&eacute; TIRZEN&nbsp;SARL propose ses services <span class="Apple-style-span" style="white-space: nowrap;">pour vous accompagner dans la r&eacute;alisation de votre projet</span>.</p>\r\n<p>\r\n	Visitez&nbsp;le site de <a href="http://www.tirzen.com">TIRZEN&nbsp;SARL</a> pour plus d&#39;information sur la soci&eacute;t&eacute;, ses services, ses r&eacute;f&eacute;rences...<br />\r\n	... et demandez&nbsp;un&nbsp;devis&nbsp;personnalis&eacute; pour votre projet.</p>\r\n', 'a:11:{s:13:"option_layout";s:0:"";s:11:"option_form";s:10:"contact_fr";s:14:"option_captcha";i:1;s:18:"option_alert_admin";i:1;s:21:"option_alert_contacts";s:0:"";s:20:"option_alert_visitor";i:1;s:18:"option_alert_email";s:0:"";s:17:"option_alert_full";i:0;s:15:"option_two_cols";i:0;s:15:"option_ref_list";s:0:"";s:18:"option_ref_default";s:0:"";}', 1, '2010-09-16 18:57:52');

DROP TABLE IF EXISTS `cms_contentDoc`;
CREATE TABLE `cms_contentDoc` (
  `contentDocId` int(10) unsigned NOT NULL auto_increment,
  `contentId` mediumint(8) unsigned NOT NULL default '0',
  `postDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(200) NOT NULL default '',
  `filename` varchar(120) NOT NULL default '',
  `filetype` varchar(30) NOT NULL default '',
  `filesize` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`contentDocId`),
  KEY `pageId` (`contentId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_contentImg`;
CREATE TABLE `cms_contentImg` (
  `contentImgId` int(10) unsigned NOT NULL auto_increment,
  `contentId` mediumint(8) unsigned NOT NULL default '0',
  `postDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(200) NOT NULL default '',
  `filename` varchar(120) NOT NULL default '',
  `filetype` varchar(30) NOT NULL default '',
  `filesize` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`contentImgId`),
  KEY `pageId` (`contentId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_country`;
CREATE TABLE `cms_country` (
  `countryId` varchar(2) NOT NULL default '',
  `name` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`countryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cms_country` VALUES('AF', 'Afghanistan');
INSERT INTO `cms_country` VALUES('AL', 'Albania');
INSERT INTO `cms_country` VALUES('DZ', 'Algeria');
INSERT INTO `cms_country` VALUES('AS', 'American samoa');
INSERT INTO `cms_country` VALUES('AD', 'Andorra');
INSERT INTO `cms_country` VALUES('AO', 'Angola');
INSERT INTO `cms_country` VALUES('AI', 'Anguilla');
INSERT INTO `cms_country` VALUES('AQ', 'Antarctica');
INSERT INTO `cms_country` VALUES('AG', 'Antigua and barbuda');
INSERT INTO `cms_country` VALUES('AR', 'Argentina');
INSERT INTO `cms_country` VALUES('AM', 'Armenia');
INSERT INTO `cms_country` VALUES('AW', 'Aruba');
INSERT INTO `cms_country` VALUES('AU', 'Australia');
INSERT INTO `cms_country` VALUES('AT', 'Austria');
INSERT INTO `cms_country` VALUES('AZ', 'Azerbaijan');
INSERT INTO `cms_country` VALUES('BS', 'Bahamas');
INSERT INTO `cms_country` VALUES('BH', 'Bahrain');
INSERT INTO `cms_country` VALUES('BD', 'Bangladesh');
INSERT INTO `cms_country` VALUES('BB', 'Barbados');
INSERT INTO `cms_country` VALUES('BY', 'Belarus');
INSERT INTO `cms_country` VALUES('BE', 'Belgium');
INSERT INTO `cms_country` VALUES('BZ', 'Belize');
INSERT INTO `cms_country` VALUES('BJ', 'Benin');
INSERT INTO `cms_country` VALUES('BM', 'Bermuda');
INSERT INTO `cms_country` VALUES('BT', 'Bhutan');
INSERT INTO `cms_country` VALUES('BO', 'Bolivia');
INSERT INTO `cms_country` VALUES('BA', 'Bosnia and herzegovina');
INSERT INTO `cms_country` VALUES('BW', 'Botswana');
INSERT INTO `cms_country` VALUES('BV', 'Bouvet island');
INSERT INTO `cms_country` VALUES('BR', 'Brazil');
INSERT INTO `cms_country` VALUES('IO', 'British indian ocean territory');
INSERT INTO `cms_country` VALUES('BN', 'Brunei darussalam');
INSERT INTO `cms_country` VALUES('BG', 'Bulgaria');
INSERT INTO `cms_country` VALUES('BF', 'Burkina faso');
INSERT INTO `cms_country` VALUES('BI', 'Burundi');
INSERT INTO `cms_country` VALUES('KH', 'Cambodia');
INSERT INTO `cms_country` VALUES('CM', 'Cameroon');
INSERT INTO `cms_country` VALUES('CA', 'Canada');
INSERT INTO `cms_country` VALUES('CV', 'Cape verde');
INSERT INTO `cms_country` VALUES('KY', 'Cayman islands');
INSERT INTO `cms_country` VALUES('CF', 'Central african republic');
INSERT INTO `cms_country` VALUES('TD', 'Chad');
INSERT INTO `cms_country` VALUES('CL', 'Chile');
INSERT INTO `cms_country` VALUES('CN', 'China');
INSERT INTO `cms_country` VALUES('CX', 'Christmas island');
INSERT INTO `cms_country` VALUES('CC', 'Cocos (keeling) islands');
INSERT INTO `cms_country` VALUES('CO', 'Colombia');
INSERT INTO `cms_country` VALUES('KM', 'Comoros');
INSERT INTO `cms_country` VALUES('CG', 'Congo');
INSERT INTO `cms_country` VALUES('CD', 'Congo');
INSERT INTO `cms_country` VALUES('CK', 'Cook islands');
INSERT INTO `cms_country` VALUES('CR', 'Costa rica');
INSERT INTO `cms_country` VALUES('CI', 'Cote d''ivoire');
INSERT INTO `cms_country` VALUES('HR', 'Croatia');
INSERT INTO `cms_country` VALUES('CU', 'Cuba');
INSERT INTO `cms_country` VALUES('CY', 'Cyprus');
INSERT INTO `cms_country` VALUES('CZ', 'Czech republic');
INSERT INTO `cms_country` VALUES('DK', 'Denmark');
INSERT INTO `cms_country` VALUES('DJ', 'Djibouti');
INSERT INTO `cms_country` VALUES('DM', 'Dominica');
INSERT INTO `cms_country` VALUES('DO', 'Dominican republic');
INSERT INTO `cms_country` VALUES('TP', 'East timor');
INSERT INTO `cms_country` VALUES('EC', 'Ecuador');
INSERT INTO `cms_country` VALUES('EG', 'Egypt');
INSERT INTO `cms_country` VALUES('SV', 'El salvador');
INSERT INTO `cms_country` VALUES('GQ', 'Equatorial guinea');
INSERT INTO `cms_country` VALUES('ER', 'Eritrea');
INSERT INTO `cms_country` VALUES('EE', 'Estonia');
INSERT INTO `cms_country` VALUES('ET', 'Ethiopia');
INSERT INTO `cms_country` VALUES('FK', 'Falkland islands (malvinas)');
INSERT INTO `cms_country` VALUES('FO', 'Faroe islands');
INSERT INTO `cms_country` VALUES('FJ', 'Fiji');
INSERT INTO `cms_country` VALUES('FI', 'Finland');
INSERT INTO `cms_country` VALUES('FR', 'France');
INSERT INTO `cms_country` VALUES('GF', 'French guiana');
INSERT INTO `cms_country` VALUES('PF', 'French polynesia');
INSERT INTO `cms_country` VALUES('TF', 'French southern territories');
INSERT INTO `cms_country` VALUES('GA', 'Gabon');
INSERT INTO `cms_country` VALUES('GM', 'Gambia');
INSERT INTO `cms_country` VALUES('GE', 'Georgia');
INSERT INTO `cms_country` VALUES('DE', 'Germany');
INSERT INTO `cms_country` VALUES('GH', 'Ghana');
INSERT INTO `cms_country` VALUES('GI', 'Gibraltar');
INSERT INTO `cms_country` VALUES('GR', 'Greece');
INSERT INTO `cms_country` VALUES('GL', 'Greenland');
INSERT INTO `cms_country` VALUES('GD', 'Grenada');
INSERT INTO `cms_country` VALUES('GP', 'Guadeloupe');
INSERT INTO `cms_country` VALUES('GU', 'Guam');
INSERT INTO `cms_country` VALUES('GT', 'Guatemala');
INSERT INTO `cms_country` VALUES('GN', 'Guinea');
INSERT INTO `cms_country` VALUES('GW', 'Guinea-bissau');
INSERT INTO `cms_country` VALUES('GY', 'Guyana');
INSERT INTO `cms_country` VALUES('HT', 'Haiti');
INSERT INTO `cms_country` VALUES('HM', 'Heard and mcdonald islands');
INSERT INTO `cms_country` VALUES('VA', 'Holy see (vatican city state)');
INSERT INTO `cms_country` VALUES('HN', 'Honduras');
INSERT INTO `cms_country` VALUES('HK', 'Hong kong');
INSERT INTO `cms_country` VALUES('HU', 'Hungary');
INSERT INTO `cms_country` VALUES('IS', 'Iceland');
INSERT INTO `cms_country` VALUES('IN', 'India');
INSERT INTO `cms_country` VALUES('ID', 'Indonesia');
INSERT INTO `cms_country` VALUES('IR', 'Iran, islamic republic of');
INSERT INTO `cms_country` VALUES('IQ', 'Iraq');
INSERT INTO `cms_country` VALUES('IE', 'Ireland');
INSERT INTO `cms_country` VALUES('IL', 'Israel');
INSERT INTO `cms_country` VALUES('IT', 'Italy');
INSERT INTO `cms_country` VALUES('JM', 'Jamaica');
INSERT INTO `cms_country` VALUES('JP', 'Japan');
INSERT INTO `cms_country` VALUES('JO', 'Jordan');
INSERT INTO `cms_country` VALUES('KZ', 'Kazakstan');
INSERT INTO `cms_country` VALUES('KE', 'Kenya');
INSERT INTO `cms_country` VALUES('KI', 'Kiribati');
INSERT INTO `cms_country` VALUES('KP', 'Korea, democratic');
INSERT INTO `cms_country` VALUES('KR', 'Korea, republic of');
INSERT INTO `cms_country` VALUES('KW', 'Kuwait');
INSERT INTO `cms_country` VALUES('KG', 'Kyrgyzstan');
INSERT INTO `cms_country` VALUES('LA', 'Laos');
INSERT INTO `cms_country` VALUES('LV', 'Latvia');
INSERT INTO `cms_country` VALUES('LB', 'Lebanon');
INSERT INTO `cms_country` VALUES('LS', 'Lesotho');
INSERT INTO `cms_country` VALUES('LR', 'Liberia');
INSERT INTO `cms_country` VALUES('LY', 'Libyan arab jamahiriya');
INSERT INTO `cms_country` VALUES('LI', 'Liechtenstein');
INSERT INTO `cms_country` VALUES('LT', 'Lithuania');
INSERT INTO `cms_country` VALUES('LU', 'Luxembourg');
INSERT INTO `cms_country` VALUES('MO', 'Macau');
INSERT INTO `cms_country` VALUES('MK', 'Macedonia');
INSERT INTO `cms_country` VALUES('MG', 'Madagascar');
INSERT INTO `cms_country` VALUES('MW', 'Malawi');
INSERT INTO `cms_country` VALUES('MY', 'Malaysia');
INSERT INTO `cms_country` VALUES('MV', 'Maldives');
INSERT INTO `cms_country` VALUES('ML', 'Mali');
INSERT INTO `cms_country` VALUES('MT', 'Malta');
INSERT INTO `cms_country` VALUES('MH', 'Marshall islands');
INSERT INTO `cms_country` VALUES('MQ', 'Martinique');
INSERT INTO `cms_country` VALUES('MR', 'Mauritania');
INSERT INTO `cms_country` VALUES('MU', 'Mauritius');
INSERT INTO `cms_country` VALUES('YT', 'Mayotte');
INSERT INTO `cms_country` VALUES('MX', 'Mexico');
INSERT INTO `cms_country` VALUES('FM', 'Micronesia');
INSERT INTO `cms_country` VALUES('MD', 'Moldova, republic of');
INSERT INTO `cms_country` VALUES('MC', 'Monaco');
INSERT INTO `cms_country` VALUES('MN', 'Mongolia');
INSERT INTO `cms_country` VALUES('MS', 'Montserrat');
INSERT INTO `cms_country` VALUES('MA', 'Morocco');
INSERT INTO `cms_country` VALUES('MZ', 'Mozambique');
INSERT INTO `cms_country` VALUES('MM', 'Myanmar');
INSERT INTO `cms_country` VALUES('NA', 'Namibia');
INSERT INTO `cms_country` VALUES('NR', 'Nauru');
INSERT INTO `cms_country` VALUES('NP', 'Nepal');
INSERT INTO `cms_country` VALUES('NL', 'Netherlands');
INSERT INTO `cms_country` VALUES('AN', 'Netherlands antilles');
INSERT INTO `cms_country` VALUES('NC', 'New caledonia');
INSERT INTO `cms_country` VALUES('NZ', 'New zealand');
INSERT INTO `cms_country` VALUES('NI', 'Nicaragua');
INSERT INTO `cms_country` VALUES('NE', 'Niger');
INSERT INTO `cms_country` VALUES('NG', 'Nigeria');
INSERT INTO `cms_country` VALUES('NU', 'Niue');
INSERT INTO `cms_country` VALUES('NF', 'Norfolk island');
INSERT INTO `cms_country` VALUES('MP', 'Northern mariana islands');
INSERT INTO `cms_country` VALUES('NO', 'Norway');
INSERT INTO `cms_country` VALUES('OM', 'Oman');
INSERT INTO `cms_country` VALUES('PK', 'Pakistan');
INSERT INTO `cms_country` VALUES('PW', 'Palau');
INSERT INTO `cms_country` VALUES('PS', 'Palestinian territory');
INSERT INTO `cms_country` VALUES('PA', 'Panama');
INSERT INTO `cms_country` VALUES('PG', 'Papua new guinea');
INSERT INTO `cms_country` VALUES('PY', 'Paraguay');
INSERT INTO `cms_country` VALUES('PE', 'Peru');
INSERT INTO `cms_country` VALUES('PH', 'Philippines');
INSERT INTO `cms_country` VALUES('PN', 'Pitcairn');
INSERT INTO `cms_country` VALUES('PL', 'Poland');
INSERT INTO `cms_country` VALUES('PT', 'Portugal');
INSERT INTO `cms_country` VALUES('PR', 'Puerto rico');
INSERT INTO `cms_country` VALUES('QA', 'Qatar');
INSERT INTO `cms_country` VALUES('RE', 'Reunion');
INSERT INTO `cms_country` VALUES('RO', 'Romania');
INSERT INTO `cms_country` VALUES('RU', 'Russian federation');
INSERT INTO `cms_country` VALUES('RW', 'Rwanda');
INSERT INTO `cms_country` VALUES('SH', 'Saint helena');
INSERT INTO `cms_country` VALUES('KN', 'Saint kitts and nevis');
INSERT INTO `cms_country` VALUES('LC', 'Saint lucia');
INSERT INTO `cms_country` VALUES('PM', 'Saint pierre and miquelon');
INSERT INTO `cms_country` VALUES('VC', 'Saint vincent and the grenadines');
INSERT INTO `cms_country` VALUES('WS', 'Samoa');
INSERT INTO `cms_country` VALUES('SM', 'San marino');
INSERT INTO `cms_country` VALUES('ST', 'Sao tome and principe');
INSERT INTO `cms_country` VALUES('SA', 'Saudi arabia');
INSERT INTO `cms_country` VALUES('SN', 'Senegal');
INSERT INTO `cms_country` VALUES('SC', 'Seychelles');
INSERT INTO `cms_country` VALUES('SL', 'Sierra leone');
INSERT INTO `cms_country` VALUES('SG', 'Singapore');
INSERT INTO `cms_country` VALUES('SK', 'Slovakia');
INSERT INTO `cms_country` VALUES('SI', 'Slovenia');
INSERT INTO `cms_country` VALUES('SB', 'Solomon islands');
INSERT INTO `cms_country` VALUES('SO', 'Somalia');
INSERT INTO `cms_country` VALUES('ZA', 'South africa');
INSERT INTO `cms_country` VALUES('GS', 'South georgia');
INSERT INTO `cms_country` VALUES('ES', 'Spain');
INSERT INTO `cms_country` VALUES('LK', 'Sri lanka');
INSERT INTO `cms_country` VALUES('SD', 'Sudan');
INSERT INTO `cms_country` VALUES('SR', 'Suriname');
INSERT INTO `cms_country` VALUES('SJ', 'Svalbard and jan mayen');
INSERT INTO `cms_country` VALUES('SZ', 'Swaziland');
INSERT INTO `cms_country` VALUES('SE', 'Sweden');
INSERT INTO `cms_country` VALUES('CH', 'Switzerland');
INSERT INTO `cms_country` VALUES('SY', 'Syrian arab republic');
INSERT INTO `cms_country` VALUES('TW', 'Taiwan, province of china');
INSERT INTO `cms_country` VALUES('TJ', 'Tajikistan');
INSERT INTO `cms_country` VALUES('TZ', 'Tanzania, united republic of');
INSERT INTO `cms_country` VALUES('TH', 'Thailand');
INSERT INTO `cms_country` VALUES('TG', 'Togo');
INSERT INTO `cms_country` VALUES('TK', 'Tokelau');
INSERT INTO `cms_country` VALUES('TO', 'Tonga');
INSERT INTO `cms_country` VALUES('TT', 'Trinidad and tobago');
INSERT INTO `cms_country` VALUES('TN', 'Tunisia');
INSERT INTO `cms_country` VALUES('TR', 'Turkey');
INSERT INTO `cms_country` VALUES('TM', 'Turkmenistan');
INSERT INTO `cms_country` VALUES('TC', 'Turks and caicos islands');
INSERT INTO `cms_country` VALUES('TV', 'Tuvalu');
INSERT INTO `cms_country` VALUES('UG', 'Uganda');
INSERT INTO `cms_country` VALUES('UA', 'Ukraine');
INSERT INTO `cms_country` VALUES('AE', 'United arab emirates');
INSERT INTO `cms_country` VALUES('GB', 'United kingdom');
INSERT INTO `cms_country` VALUES('US', 'United states');
INSERT INTO `cms_country` VALUES('UM', 'United states minor islands');
INSERT INTO `cms_country` VALUES('UY', 'Uruguay');
INSERT INTO `cms_country` VALUES('UZ', 'Uzbekistan');
INSERT INTO `cms_country` VALUES('VU', 'Vanuatu');
INSERT INTO `cms_country` VALUES('VE', 'Venezuela');
INSERT INTO `cms_country` VALUES('VN', 'Viet nam');
INSERT INTO `cms_country` VALUES('VG', 'Virgin islands, british');
INSERT INTO `cms_country` VALUES('VI', 'Virgin islands, u.s.');
INSERT INTO `cms_country` VALUES('WF', 'Wallis and futuna');
INSERT INTO `cms_country` VALUES('EH', 'Western sahara');
INSERT INTO `cms_country` VALUES('YE', 'Yemen');
INSERT INTO `cms_country` VALUES('YU', 'Yugoslavia');
INSERT INTO `cms_country` VALUES('ZM', 'Zambia');
INSERT INTO `cms_country` VALUES('ZW', 'Zimbabwe');

DROP TABLE IF EXISTS `cms_emailMessage`;
CREATE TABLE `cms_emailMessage` (
  `emailMessageId` smallint(5) unsigned NOT NULL auto_increment,
  `direction` tinyint(1) unsigned NOT NULL default '1',
  `description` varchar(255) NOT NULL default '',
  `recipientName` varchar(255) NOT NULL default '',
  `recipientAddress` varchar(255) NOT NULL default '',
  `recipientCc` varchar(255) NOT NULL default '',
  `body` text NOT NULL,
  `html` tinyint(1) unsigned NOT NULL default '0',
  `active` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`emailMessageId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cms_emailMessage` VALUES(10, 1, 'sign_up_new', '', '', '', 'Une nouvelle demande de création de compte a été effectuée :\r\n\r\n{data}\r\n', 0, 1);
INSERT INTO `cms_emailMessage` VALUES(12, 0, 'sign_up_pending', '', '', '', 'Bonjour {objMember->getName},\r\n\r\nNous avons bien reçu votre demande de création de compte sur notre site.\r\n\r\nInutile de nous rappeler, nous vous contacterons dès que votre compte sera activé.\r\n\r\n{data}\r\n', 0, 1);
INSERT INTO `cms_emailMessage` VALUES(14, 0, 'sign_up_activation', '', '', '', 'Hello {objEditItem->getName},\r\n\r\nNous avons bien reçu votre demande de création de compte.\r\n\r\nVous devez maintenant confirmer votre adresse email afin de compléter le processus de validation du compte. Pour se faire, merci de cliquer sur le lien ci-dessous :\r\n\r\n{data}\r\n\r\n', 0, 1);
INSERT INTO `cms_emailMessage` VALUES(16, 0, 'sign_up_confirmation', '', '', '', 'Votre compte vient d''être activé !\r\n\r\nVous pouvez désormais vous connecter en utilisant vos codes d''accès :\r\n\r\n{data}\r\n\r\n', 0, 1);
INSERT INTO `cms_emailMessage` VALUES(20, 0, 'members_pass_reminder', '', '', '', 'Suite à votre demande, veuillez trouver ci-dessous votre nouveau mot de passe :\r\n\r\n{data}\r\n', 0, 1);
INSERT INTO `cms_emailMessage` VALUES(21, 1, 'form_to_data_admin', '', '', '', '{data}', 0, 1);
INSERT INTO `cms_emailMessage` VALUES(22, 0, 'form_to_data_visitor', '', '', '', 'Bonjour {data},\r\n\r\nCeci est une réponse automatique confirmant que nous avons bien reçu votre message.\r\n\r\nCordialement.\r\n', 0, 1);

DROP TABLE IF EXISTS `cms_facebook`;
CREATE TABLE `cms_facebook` (
  `facebookId` mediumint(8) unsigned NOT NULL,
  `pos` smallint(5) unsigned NOT NULL,
  `photo` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `note` text NOT NULL,
  PRIMARY KEY  (`facebookId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_formData`;
CREATE TABLE `cms_formData` (
  `formDataId` mediumint(8) unsigned NOT NULL default '0',
  `postDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `name` varchar(255) NOT NULL default '',
  `email` varchar(150) NOT NULL,
  `memo` text NOT NULL,
  `referrer` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`formDataId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_highlight`;
CREATE TABLE `cms_highlight` (
  `highlightId` mediumint(8) unsigned NOT NULL,
  `postDate` datetime NOT NULL,
  `rankpos` smallint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `linkurl` varchar(255) NOT NULL,
  `imgfile` varchar(155) NOT NULL,
  PRIMARY KEY  (`highlightId`),
  KEY `rankpos` (`rankpos`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_item`;
CREATE TABLE `cms_item` (
  `itemId` int(10) unsigned NOT NULL auto_increment,
  `projectId` mediumint(8) unsigned NOT NULL default '0',
  `itemParentId` int(10) unsigned NOT NULL default '0',
  `priority` tinyint(3) unsigned NOT NULL default '0',
  `context` varchar(64) NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `creationDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `startDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `deadlineDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `completionDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `expectedDuration` smallint(5) unsigned NOT NULL default '0',
  `actualDuration` smallint(5) unsigned NOT NULL default '0',
  `showInCalendar` tinyint(1) unsigned NOT NULL default '0',
  `showPrivate` tinyint(1) unsigned NOT NULL default '0',
  `memberId` mediumint(8) unsigned NOT NULL default '0',
  `authorId` mediumint(8) unsigned NOT NULL default '0',
  `lastChangeDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastChangeAuthorId` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`itemId`),
  KEY `projectId` (`projectId`),
  KEY `memberId` (`memberId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_itemAlert`;
CREATE TABLE `cms_itemAlert` (
  `itemId` int(10) unsigned NOT NULL default '0',
  `code` tinyint(3) unsigned NOT NULL default '0',
  `params` text NOT NULL,
  `status` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemId`,`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_itemComment`;
CREATE TABLE `cms_itemComment` (
  `itemCommentId` bigint(20) unsigned NOT NULL auto_increment,
  `itemId` int(10) unsigned NOT NULL default '0',
  `memberId` mediumint(8) unsigned NOT NULL default '0',
  `postDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `body` text NOT NULL,
  `lastChangeDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`itemCommentId`),
  KEY `itemId` (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_itemContext`;
CREATE TABLE `cms_itemContext` (
  `itemContextId` smallint(5) unsigned NOT NULL auto_increment,
  `translations` text NOT NULL,
  `color` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`itemContextId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_itemFile`;
CREATE TABLE `cms_itemFile` (
  `itemFileId` bigint(20) unsigned NOT NULL auto_increment,
  `itemId` int(10) unsigned NOT NULL default '0',
  `memberId` mediumint(8) unsigned NOT NULL default '0',
  `fileTitle` varchar(200) NOT NULL default '',
  `filename` varchar(127) NOT NULL default '',
  `filetype` varchar(30) NOT NULL default '',
  `filesize` bigint(20) NOT NULL default '0',
  `postDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastChangeDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `fileTags` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`itemFileId`),
  KEY `itemId` (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_itemStatus`;
CREATE TABLE `cms_itemStatus` (
  `itemStatusId` bigint(20) unsigned NOT NULL auto_increment,
  `itemId` int(10) unsigned NOT NULL default '0',
  `statusDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `statusKey` tinyint(3) unsigned NOT NULL default '0',
  `memberId` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itemStatusId`),
  KEY `itemId` (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_mapLocation`;
CREATE TABLE `cms_mapLocation` (
  `mapLocationId` mediumint(8) unsigned NOT NULL default '0',
  `rankpos` smallint(5) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `location` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`mapLocationId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_member`;
CREATE TABLE `cms_member` (
  `memberId` mediumint(8) unsigned NOT NULL auto_increment,
  `email` varchar(120) NOT NULL default '',
  `title` varchar(20) NOT NULL default '',
  `firstName` varchar(50) NOT NULL default '',
  `middleName` varchar(50) NOT NULL default '',
  `lastName` varchar(50) NOT NULL default '',
  `companyName` varchar(80) NOT NULL default '',
  `nickName` varchar(63) NOT NULL,
  `avatar` varchar(127) NOT NULL,
  `address` tinytext NOT NULL,
  `zipCode` varchar(20) NOT NULL default '',
  `city` varchar(60) NOT NULL default '',
  `stateCode` varchar(2) NOT NULL default '',
  `countryId` varchar(2) NOT NULL default '',
  `cmsLanguage` varchar(2) NOT NULL default 'en',
  `username` varchar(20) NOT NULL default '',
  `password` varchar(60) NOT NULL default '',
  `salt` varchar(8) NOT NULL default '',
  `autoLogin` tinyint(1) NOT NULL default '0',
  `timeZone` smallint(6) NOT NULL default '0',
  `expirationDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastLoginDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastLoginAddress` varchar(60) NOT NULL default '',
  `creationDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastChangeDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `visits` mediumint(8) unsigned NOT NULL default '0',
  `badAccess` tinyint(3) unsigned NOT NULL default '0',
  `level` tinyint(3) unsigned NOT NULL default '0',
  `activation` varchar(16) NOT NULL default '',
  `authorId` mediumint(8) unsigned NOT NULL default '0',
  `enabled` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`memberId`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cms_member` VALUES(1, 'modifier@addresse-email.com', '', 'Task', '', 'Freak', '', 'Admin', '', '', '', '', '', 'FR', 'fr', 'admin', '', '12345678', 0, 7200, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '127.0.0.1', '2010-09-15 12:00:00', '0000-00-00 00:00:00', 0, 0, 4, '', 0, 1);

DROP TABLE IF EXISTS `cms_memberNewsletter`;
CREATE TABLE `cms_memberNewsletter` (
  `memberNewsletterId` mediumint(8) unsigned NOT NULL default '0',
  `subscribeDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `subscribeEnable` tinyint(1) unsigned NOT NULL default '0',
  `subscribeHtml` tinyint(1) unsigned NOT NULL default '0',
  `newsletterId` mediumint(8) unsigned NOT NULL default '0',
  `newsletterDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`memberNewsletterId`),
  KEY `subscribeEnable` (`subscribeEnable`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_memberProject`;
CREATE TABLE `cms_memberProject` (
  `memberId` mediumint(8) unsigned NOT NULL default '0',
  `projectId` mediumint(8) unsigned NOT NULL default '0',
  `position` tinyint(2) unsigned NOT NULL default '0',
  PRIMARY KEY  (`memberId`,`projectId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cms_memberProject` VALUES(1, 9, 5);

DROP TABLE IF EXISTS `cms_memberTeam`;
CREATE TABLE `cms_memberTeam` (
  `memberId` mediumint(8) unsigned NOT NULL default '0',
  `teamId` mediumint(8) unsigned NOT NULL default '0',
  `position` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`memberId`,`teamId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_moduleOption`;
CREATE TABLE `cms_moduleOption` (
  `module` varchar(120) NOT NULL default '',
  `field` varchar(120) NOT NULL default '',
  `value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`module`,`field`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_newsletter`;
CREATE TABLE `cms_newsletter` (
  `newsletterId` mediumint(8) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `creationDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `deliveryDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`newsletterId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_newsletterContent`;
CREATE TABLE `cms_newsletterContent` (
  `newsletterId` mediumint(8) unsigned NOT NULL default '0',
  `moduleName` varchar(127) NOT NULL default '',
  `moduleId` int(10) unsigned NOT NULL default '0',
  `position` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`newsletterId`,`moduleName`,`moduleId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_page`;
CREATE TABLE `cms_page` (
  `pageId` mediumint(8) unsigned NOT NULL auto_increment,
  `position` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `menu` varchar(80) NOT NULL default '',
  `shortcut` varchar(120) NOT NULL default '',
  `template` varchar(80) NOT NULL default 'default',
  `display` tinyint(1) unsigned NOT NULL default '0',
  `private` tinyint(1) unsigned NOT NULL default '0',
  `showInMenu` tinyint(1) unsigned NOT NULL default '0',
  `protected` varchar(5) NOT NULL default '00000',
  `keyword` tinytext NOT NULL,
  `description` tinytext NOT NULL,
  `encoding` varchar(20) NOT NULL default '',
  `lastChangeDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `module` varchar(120) NOT NULL default 'basic',
  PRIMARY KEY  (`pageId`),
  KEY `position` (`position`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cms_page` VALUES(1, '001000000000000', 'Version franÃ§aise', 'Version franÃ§aise', 'fr', 'default', 1, 0, 1, '00000', '', '', 'UTF-8', '2010-09-18 07:38:43', '');
INSERT INTO `cms_page` VALUES(11, '001001000000000', 'Accueil', 'Accueil', 'home', 'default', 1, 0, 1, '00001', '', '', 'UTF-8', '2010-09-16 08:47:54', 'content_fck');
INSERT INTO `cms_page` VALUES(49, '001004000000000', 'Page membres', 'Page membres', 'protected-content', 'default', 1, 1, 1, '00000', '', '', 'UTF-8', '2010-09-22 21:45:47', 'content_fck');
INSERT INTO `cms_page` VALUES(50, '001002000000000', 'ActualitÃ©s', 'ActualitÃ©s', 'news', 'default', 1, 0, 1, '00000', '', '', 'UTF-8', '2010-09-18 07:38:57', 'blog');
INSERT INTO `cms_page` VALUES(51, '001003000000000', 'Gestion de contenu', 'Gestion de contenu', 'content-management', 'default', 1, 0, 1, '', '', '', 'UTF-8', '2010-09-16 19:45:26', 'picture_gallery');
INSERT INTO `cms_page` VALUES(52, '001005000000000', 'Contact', 'Contact', 'contact', 'default', 1, 0, 1, '', '', '', 'UTF-8', '2010-09-16 15:43:06', 'form_to_data');
INSERT INTO `cms_page` VALUES(47, '002000000000000', 'TaskFreak', 'TaskFreak', 'taskfreak', 'default', 1, 0, 0, '00001', '', '', 'UTF-8', '2010-09-14 20:52:46', 'taskfreak');

DROP TABLE IF EXISTS `cms_pageTeam`;
CREATE TABLE `cms_pageTeam` (
  `teamId` mediumint(8) unsigned NOT NULL default '0',
  `pageId` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`teamId`,`pageId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_pictureGallery`;
CREATE TABLE `cms_pictureGallery` (
  `pictureGalleryId` mediumint(8) unsigned NOT NULL,
  `postDate` datetime NOT NULL,
  `rankpos` smallint(8) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `imgfile` varchar(155) NOT NULL,
  PRIMARY KEY  (`pictureGalleryId`),
  KEY `rankpos` (`rankpos`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cms_project`;
CREATE TABLE `cms_project` (
  `projectId` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(120) NOT NULL default '',
  `description` text NOT NULL,
  PRIMARY KEY  (`projectId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cms_project` VALUES(9, 'Projet test', '');

DROP TABLE IF EXISTS `cms_projectStatus`;
CREATE TABLE `cms_projectStatus` (
  `projectStatusId` int(10) unsigned NOT NULL auto_increment,
  `projectId` mediumint(10) unsigned NOT NULL default '0',
  `statusDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `statusKey` tinyint(3) unsigned NOT NULL default '0',
  `memberId` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`projectStatusId`),
  KEY `projectId` (`projectId`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `cms_projectStatus` VALUES(1, 9, '2010-09-17 09:14:08', 0, 1);

DROP TABLE IF EXISTS `cms_setting`;
CREATE TABLE `cms_setting` (
  `settingKey` varchar(64) NOT NULL default '',
  `settingValue` text NOT NULL,
  PRIMARY KEY  (`settingKey`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `cms_setting` VALUES('cms_version', '1.0');
INSERT INTO `cms_setting` VALUES('auto_login', '1');
INSERT INTO `cms_setting` VALUES('password_reminder', '1');
INSERT INTO `cms_setting` VALUES('registration', '1');
INSERT INTO `cms_setting` VALUES('default_language', 'fr');
INSERT INTO `cms_setting` VALUES('default_country', 'FR');
INSERT INTO `cms_setting` VALUES('default_template', 'default');
INSERT INTO `cms_setting` VALUES('site_title', 'TaskFreak CMS v1.0');
INSERT INTO `cms_setting` VALUES('default_page', '1');
INSERT INTO `cms_setting` VALUES('site_footer', 'Taskfreak! CMS v1.0 - Open Source project licensed under GPL');
INSERT INTO `cms_setting` VALUES('module_content', '1');
INSERT INTO `cms_setting` VALUES('default_email', 'modifier@addresse-email.com');
INSERT INTO `cms_setting` VALUES('maintenance', '');
INSERT INTO `cms_setting` VALUES('maintenance_message', 'test message de maintenance');
INSERT INTO `cms_setting` VALUES('email_prefix', 'TFCMS:');
INSERT INTO `cms_setting` VALUES('registration_footer', '<p><small>Les informations recueillies sont n&eacute;cessaires pour votre adh&eacute;sion.\r\nElles font l''objet d''un traitement informatique et sont destin&eacute;es à nous uniquement. En application des articles 39 et suivants de la loi du 6 janvier 1978 modifi&eacute;e, vous b&eacute;n&eacute;ficiez d''un droit d''acc&egrave;s et de rectification aux informations qui vous concernent.\r\nSi vous souhaitez exercer ce droit et obtenir communication des informations vous concernant, veuillez nous contacter en utilisant le <a href="contact.html">formulaire de contact</a>.</small></p>');
INSERT INTO `cms_setting` VALUES('date_us_format', '');
INSERT INTO `cms_setting` VALUES('submit_close', 'Save & Close');
INSERT INTO `cms_setting` VALUES('website_url', 'http://cms.taskfreak.com');
INSERT INTO `cms_setting` VALUES('website_name', 'cms.taskfreak.com');
INSERT INTO `cms_setting` VALUES('module_html', '1');
INSERT INTO `cms_setting` VALUES('email_smtp', '');
INSERT INTO `cms_setting` VALUES('module_facebook', '1');
INSERT INTO `cms_setting` VALUES('save', '1');
INSERT INTO `cms_setting` VALUES('saveclose', '2');
INSERT INTO `cms_setting` VALUES('module_blog', '1');
INSERT INTO `cms_setting` VALUES('module_form_to_data', '1');
INSERT INTO `cms_setting` VALUES('module_map', '');
INSERT INTO `cms_setting` VALUES('module_content_fck', '1');
INSERT INTO `cms_setting` VALUES('module_picture_gallery', '1');
INSERT INTO `cms_setting` VALUES('module_taskfreak', '3');
INSERT INTO `cms_setting` VALUES('module_content_multi', '1');
INSERT INTO `cms_setting` VALUES('module_contact', '1');

DROP TABLE IF EXISTS `cms_team`;
CREATE TABLE `cms_team` (
  `teamId` mediumint(8) unsigned NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `description` text NOT NULL,
  `visibility` tinyint(3) unsigned NOT NULL default '0',
  `enabled` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`teamId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

