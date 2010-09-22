<?php

$GLOBALS['langModule']['blog'] = array(
	'name'			=> 'Blog',
	'description'	=> 'Publication d\'articles',
);

$GLOBALS['langAdminMenuItem']['blog'] = 'Blog';

/*
$GLOBALS['langEmailAlert']['whatever_code'] = array(
	'subject'		=> '...',
	'description'	=> '...'
);
*/

$GLOBALS['langBlog'] = array (
	'posted_on'				=> 'Post&eacute; le',
	'posted_by'				=> 'par',
	'comments_section'		=> 'Commentaires',
	'comments_single'		=> 'commentaire',
	'comments_plural'		=> 'commentaires',
	'comments_none'			=> 'Aucun commentaire pour le moment',
	'comments_post'			=> 'Ajouter un nouveau commentaire',
	'comments_post_name'	=> 'Votre nom',
	'comments_post_email'	=> 'Votre email',
	'comments_post_body'	=> 'Votre commentaire',
	'comments_post_submit'	=> 'Soumettre le commentaire'
);	
$GLOBALS['langBlogAdminForm'] = array (
	'publishon'		=> 'Publi&eacute; le',
	'title'			=> 'Titre',
	'summary'		=> 'R&eacute;sum&eacute;',
	'publishart'	=> 'article publi&eacute',
	'allowcomment'	=> 'autoriser les commentaires',
	'membersonly'	=> 'post&eacute;s par les membres uniquement',
	'article'		=> 'Article',
	'sticky'        => 'Sticky',
	'sticky_help'   => 'Afficher cet article en haut de liste',
	'event_label'	=> 'Ev&eacute;nement',
	'event_is_event'	=> 'Cet article d&eacute;crit un &eacute;v&eacute;nement &agrave; publier dans l\'agenda',
	'event_date_begin'	=> 'D&eacute;but',
	'event_date_end'	=> 'Fin'
);
$GLOBALS['langBlogAdminList'] = array (
	'sectiontitle'	=> 'Entr&eacute;es dans le Blog',
	'new'			=> 'nouvelle entr&eacute;e',
	'publishedart'	=> 'article publi&eacute;',
	'notpublish'	=> 'non publi&eacute;',
	'edit'			=> '&eacute;diter',
	'delete'		=> 'effacer',
	'options'		=> 'Options',
	'showhide'		=> 'Montrer/Cacher',
	'language'		=> 'Langage',
	'introduction'	=> 'Introduction'
);
//admin options for blog module in Module & Options
$GLOBALS['langBlogOptions'] = array (
	'allow'			=> 'Autoriser les commentaires par d&eacute;faut',
	'membersonly'	=> 'R&eacute;serv&eacute; aux membres',
	'secuimg'		=> 'Image de s&eacute;curit&eacute;',
);