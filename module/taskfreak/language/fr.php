<?php
/****************************************************************************\
* TaskFreak!                                                                 *
* multi user                                                                 *
******************************************************************************
* Version: 0.6.2                                                             *
* Authors: Stan Ozier <taskfreak@gmail.com>                                  *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

$GLOBALS['langModule']['taskfreak'] = array(
	'name'			=> 'TaskFreak',
	'description'	=> 'Gestion de t&acirc;ches',
);

// project status
$GLOBALS['langProjectStatus'] = array(
	0 	=> 'Nouveau',           // 0 is for new project
	10	=> 'Proposition',      // anything between 0 and 40
	20 	=> 'En cours',   // is free to be customized
	40	=> 'Terminé',     // anything 40 and over
	50	=> 'Annulé'      // is for non active projects
);

// project position
$GLOBALS['langProjectPosition'] = array(
	1	=> 'Invité',	// see only, no action
	2	=> 'Officiel',	// add comments
	3	=> 'Membre',	// add tasks, add comments, task status
	4	=> 'Modérateur', // add/edit all tasks, comments, project members and status
	5	=> 'Responsable'     // everything
);

// task (item) status
$GLOBALS['langItemStatus'] = array(
	0	=> 'nouvelle',
	1	=> 'en cours',
	2	=> 'terminée',
	3	=> 'archivée'
);

// contexts

$GLOBALS['langItemContext'] = array (
	1 => 'Tâches',
	2 => 'Réunion',
	3 => 'Document',
	4 => 'Internet',	
	5 => 'Téléphone',
	6 => 'Email',
	7 => 'Personnel',
	8 => 'Autre'
);

$GLOBALS['langItemPriority'] = array (
	1 => 'Urgent!',
	2 => 'Priorité haute',
	3 => 'Priorité modéré',
	4 => 'Priorité normale',	
	5 => 'Priorité basse',
	6 => 'Priorité basse',
	7 => 'Priorité tr&egrave;s basse',
	8 => 'Priorité tr&egrave;s basse',
	9 => 'Pas prioritaire'
);

$GLOBALS['langParams'] = array(
	'jscalendar'	=> 'fr'
);

// === NO MODIFICATION FROM HERE ===

// top menu / navigation
$GLOBALS['langTaskMenu'] = array (
	'task'				=> 'T&acirc;che',
    'print_list'        => 'Imprimer',
	'new_todo'			=> 'Nouvelle t&acirc;che',
	'view'				=> 'Voir',
	'all_projects'		=> 'Toutes les projets',
	'today_tasks'		=> 'T&acirc;ches &agrave; traiter',
	'future_tasks'		=> 'En attente',
	'past_tasks'		=> 'Complet&eacute;es',
    'my_tasks'          => 'Mes t&acirc;ches',
    'all_users_tasks'	=> 'T&acirc;ches de tous',
	'all_tasks'			=> 'Toutes les t&acirc;ches',
	'all_contexts'		=> 'Tous les contextes',
	'all_users' 		=> 'Tous les utilisateurs',
	'reload'			=> 'Recharger',
	'manage'			=> 'G&eacute;rer',
	'projects'			=> 'Projets',
	'users' 			=> 'Utilisateurs',
    'preferences'       => 'Mon profil',
    'settings'          => 'Configuration',
	'login'				=> 'Connexion',
	'logout'			=> 'D&eacute;connexion',
	'warning'			=> 'Attention',
	'warning_install'	=> 'Le r&eacute;pertoire d\'installation est toujours pr&eacute;sent. Il est recommand&eacute; de le supprimer apr&egrave;s usage.'
);

// fields and column labels
$GLOBALS['langTaskForm'] = array (
	'priority'			=> 'Priorit&eacute;',
	'context'			=> 'Contexte',
	'deadline'			=> '&Eacute;ch&eacute;ance',
	'project'			=> 'Projet',
	'tasks'				=> 'T&acirc;ches',
	'title'				=> 'Titre',
	'description'		=> 'Description',
    'user'              => 'En charge',
    'author'			=> 'Auteur',
    'visibility'        => 'Visibilit&eacute;',
    'private'           => 'priv&eacute;',
    'internal'          => 'interne',
    'public'            => 'public',
	'status'			=> 'Etat',
	'create'			=> 'Cr&eacute;er',
	'save'				=> 'Enregistrer',
	'cancel'			=> 'Annuler',
	'reset'				=> 'Annuler modifications',
    'close'             => 'fermer',
    'edit'              => 'modifier',
    'delete'            => 'supprimer',
	'new'				=> 'Nouveau',
	'project_new'		=> 'Nouvelle commisssion?',
	'project_list'		=> 'Voir liste',
	'compulsory_legend' => 'Les champs en <span class="compulsory">rouge</span> sont obligatoires.',
	'list_comments'		=> 'Com.'
);

$GLOBALS['langTaskDetails'] = array (
	'tab_description'	=> 'description',
	'description_none'	=> 'pas de description',
	'tab_comments'		=> 'commentaires',
	'comments_by'		=> 'par',
	'comments_none'		=> 'pas de commentaires',
	'comments_no_access'	=> 'les commentaires sont inaccessibles',
	'comments_new'		=> 'ajouter un premier commentaire',
	'comments_reply'	=> 'répondre',
	'comments_edit'		=> 'modifier',
	'comments_delete'	=> 'supprimer',
	'comments_delete_confirm'	=> 'réellement supprimer ce commentaire?',
	'tab_history'		=> 'historique',
    'history_date'      => 'date',
    'history_user'      => 'utilisateur',
    'history_what'      => 'action'
);

// project related
$GLOBALS['langProject'] = array(
    'project'           => 'Projet',
    'projects'          => 'Projets',
    'name'              => 'Nom',
    'description'       => 'Description',
    'position'          => 'Position',
    'members'           => 'Membres',
    'members_legend'    => 'Participants',
	'status'            => 'Etat',
    'action'            => 'Action',
    'project_history'   => 'Voir l&apos;historique des changements',
    'remove_confirm'    => '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer le projet ?',
    'user_add_legend'   => 'Ajouter un utilisateur &agrave; ce projet',
    'user_add_button'   => 'Ajouter un utilisateur au projet',
	'user_no_project'   => 'Ne participe &agrave; aucune projet',
	'user_added_ok'		=> 'Membre ajout&eacute; au projet',
	'user_added_err'	=> 'Ce membre est d&eacute;j&agrave; associ&eacute; au projet',
	'user_removed_ok'	=> 'Membre retir&eacute; du projet',
	'user_removed_err'	=> 'Ce membre ne peut &ecirc;tre retir&eacute; du projet',
	'user_position_ok'	=> 'Position(s) mis &agrave; jour',
	'project_info'		=> 'D&eacute;tails du projet',
	'history_date'      => 'date',
    'history_user'      => 'utilisateur',
    'history_what'      => 'action',
	'action_save_ok'	=> 'Projet mis &agrave; jour!',
	'action_added_ok'	=> 'Projet cr&eacute;&eacute;!',
	'action_status_ok'	=> 'Projet mis &agrave; jour!'
);


// error and information messages
$arrMess = array (
    'project_delete'            => 'Supprimer la projet',
    'project_delete_confirm'    => '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer cette projet et toutes ses t&acirc;ches ?',
    'project_delete_ok'         => 'Projet supprim&eacute;',
    'project_delete_no'         => 'Impossible de supprimer la projet !',
    'task_edit'				    => 'Modifier cette t&acirc;che',
    'task_delete'			    => 'Supprimer cette t&acirc;che',
    'task_delete_confirm'	    => '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer cette t&acirc;che ?',
	'error_no_title'		    => 'Merci de saisir un titre!',
	'done_deleted'			    => 't&acirc;che supprim&eacute;e!',
	'done_status'			    => '&eacute;tat modifi&eacute;',
	'done_updated'			    => 't&acirc;che modifi&eacute;e!',
	'done_added'			    => 't&acirc;che cr&eacute;&eacute;e !',
	'done_comment_added'		=> 'commentaire ajouté!',
	'done_comment_updated'		=> 'commentaire mis à jour!',
	'done_comment_deleted'		=> 'commentaire supprimé!',
	'operation_failed'			=> 'Echec de l\'operation!',
	'purge_all'				    => 'purger (supprimer les t&acirc;ches termin&eacute;es) pour tous les projets',
	'purge_all_confirm'		    => '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer les t&acirc;ches termin&eacute;es de tous les projets ?',
	'delete_all'			    => 'supprimer tous les projets (toutes les t&acirc;ches)',
	'delete_all_confirm'	    => '&ecirc;tes-vous s&ucirc;r de vouloir supprimer toutes les t&acirc;ches de tous les projets ?',
	'purge_one'				    => 'purger (supprimer les t&acirc;ches termin&eacute;es)',
	'purge_one_confirm'		    => '&Ecirc;tes-vous s&ucirc;r de vouloir supprimer les t&acirc;ches termin&eacute;es de ce projet ?',
	'delete_one'			    => 'supprimer le projet',
	'delete_one_confirm'	    => '&ecirc;tes-vous s&ucirc;r de vouloir supprimer ce projet ?',
	'no_task_found'			    => 'aucune t&acirc;che ne correspond &agrave; ces crit&egrave;res',
	'no_project_found'		    => 'vous n\'&ecirc;tes associ&eacute;(e) &agrave; aucune projet',
	'create_task'			    => 'Cliquez ici pour tenter d\'en cr&eacute;er une',
	'no_project_found_1'	    => "Projet introuvable !!!",
	'no_project_found_2'	    => 'Cr&eacute;er d&apos;abord une t&acirc;che ',
	'information_saved'			=> 'Information sauvegard&eacute;e',
	'clock_start'				=> 'd&eacute;marrer le chrono',
    'clock_stop'				=> 'arr&ecirc;ter le chrono',
    'clock_change'				=> 'modifier la dur&eacute;e',
	'confirm_status_close'		=> 'Vraiment clôturer cette tâche?'
);
$GLOBALS['langMessage'] = $GLOBALS['langMessage']+$arrMess;

$GLOBALS['langRss'] = array (
    'no_task'       => 'Pas de t&acirc; pour aujourd&apos;hui',
    'error_login'   => 'Authentication &eacute;chou&eacute;e'
);
