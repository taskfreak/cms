<?php

/* config */

$GLOBALS['langModule']['form_to_data'] = array(
	'name'			=> 'Formulaires',
	'description'	=> 'Formulaires &eacute;l&eacute;ctronique',
);

$GLOBALS['langAdminMenuItem']['form_to_data'] = 'Formulaires';

/* email alerts */

$GLOBALS['langEmailAlert']['form_to_data_admin'] = array(
	'subject'		=> 'Nouveau formulaire rempli par un visiteur',
	'description'	=> 'Formulaires: notification au webmaster'
);

$GLOBALS['langEmailAlert']['form_to_data_visitor'] = array(
	'subject'		=> 'Réponse au formulaire en ligne',
	'description'	=> 'Formulaires: r&eacute;ponse automatique au visiteur'
);

/* misc admin translations */

$GLOBALS['langFormToDataOrderList'] = array(
	0		=> 'Date (invers&eacute;)',
	1		=> 'Date (normal)',
	2		=> 'Alphab&eacute;tique'
);

$GLOBALS['langFormToDataForm'] = array (
	'context'			=> 'Context',
	'date'				=> 'Date r&eacute;ception',
	'title'				=> 'Titre',
	'name'				=> 'Nom',
	'form'				=> 'Formulaire',
	'referrer'			=> 'Origine',
	'data'				=> 'Donn&eacute;es',
	'memo'				=> 'Memo',
    'user'              => 'Utilisateur',
	'save'				=> 'Enregistrer',
	'cancel'			=> 'Annuler',
	'reset'				=> 'Remise &agrave; z&eacute;ro',
    'close'             => 'fermer',
    'edit'              => 'modifier',
    'delete'            => 'supprimer',
    'print'				=> 'imprimer',
	'compulsory_legend' => 'Les champs <span class="compulsory">en rouge</span> sont obligatoires.',
);

$GLOBALS['langFormToDataDetails'] = array (
	'tab_data'			=> 'Donn&eacute;es',
	'description_none'	=> 'pas de memo',
	'tab_memo'			=> 'Memo',
	'data_none'			=> 'Formulaire vide',
	'memo_none'			=> 'Memo vide',
	'comments_by'		=> 'par',
	'comments_none'		=> 'aucun commentaire',
	'comments_no_access'	=> 'commentaires invisibles',
	'comments_new'		=> 'ajouter un commentaire',
	'comments_reply'	=> 'r&eacute;pondre',
	'comments_edit'		=> 'modifier',
	'comments_delete'	=> 'supprimer',
	'comments_delete_confirm'	=> 'reellement supprimer ce commentaire?',
	'tab_history'		=> 'historique',
    'history_date'      => 'date',
    'history_user'      => 'utilisateur',
    'history_what'      => 'action'
);

// date support
$GLOBALS['langDateMore'] = array (
	'day'				=> 'jour',
	'days'				=> 'jours',
	'help'				=> 'eg. aujourd\'hui, demain, 12 avril'
);


// error and information messages
$GLOBALS['langFormToDataMessage'] = array (
    'not_found_or_denied'       => 'Donn&eacute;es non trouv&eacute;s ou acc&egrave;s refus&eacute;',
    'denied'                    => 'Acc&egrave;s refus&eacute;!',
    'project_delete'            => 'Delete project',
    'project_delete_confirm'    => 'Really delete this project and its tasks?',
    'project_delete_ok'         => 'Project deleted',
    'project_delete_no'         => 'Project can not be deleted!',
    'task_edit'				    => 'Modifier le memo',
    'task_delete'			    => 'Supprimer cette entr&eacute;e',
    'task_delete_confirm'	    => 'R&eacute;ellement supprimer cet entr&eacute;e?',
	'error_no_title'		    => 'please enter title!',
	'done_deleted'			    => 'task deleted!',
	'done_status'			    => 'task status updated',
	'done_updated'			    => 'task updated!',
	'done_added'			    => 'task created!',
	'done_memo_updated'			=> 'memo updated!',
	'done_comment_added'		=> 'comment added!',
	'done_comment_updated'		=> 'comment updated!',
	'done_comment_deleted'		=> 'comment deleted!',
	'operation_failed'			=> 'operation failed!',
	'purge_all'				    => 'purge (delete old tasks) for all projects',
	'purge_all_confirm'		    => 'really delete old tasks from all projects?',
	'delete_all'			    => 'delete all projects (all tasks)',
	'delete_all_confirm'	    => 'really delete all tasks from all projects?',
	'purge_one'				    => 'purge (delete old tasks)',
	'purge_one_confirm'		    => 'really delete old tasks from this project?',
	'delete_one'			    => 'delete the entire project',
	'delete_one_confirm'	    => 'really delete this project?',
	'no_task_found'			    => 'no submission forms match your criterions',
	'no_project_found'		    => 'no project found',
	'create_task'			    => 'Click here to make an attempt to create one',
	'no_project_found_1'	    => "Dang! Can't find a project!!!",
	'no_project_found_2'	    => 'You probably need to create a task first, you know',
	'close_window'			    => 'close this window',
    'session_expired'           => 'Session has expired',
    'clock_start'				=> 'start timer',
    'clock_stop'				=> 'stop timer',
    'clock_change'				=> 'modify timer',
	'information_saved'			=> 'Information successfully saved',
	'confirm_status_close'		=> 'Really close this task?'
);
