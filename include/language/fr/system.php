<?php
/****************************************************************************\
* TaskFreak!                                                                 *
* multi user                                                                 *
******************************************************************************
* Version: 0.5.1                                                             *
* Authors: Stan Ozier <taskfreak@gmail.com>                                  *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/


// system menu
$GLOBALS['langSystemMenu'] = array (
	'general'   		=> 'General',
	'email_alerts'		=> 'Alertes email',
	'contexts'			=> 'Contextes'
);

$GLOBALS['langSystemStuff'] = array(
	'site_legend'		=> 'Pr&eacute;f&eacute;rences site',
	'site_title'		=> 'Titre du site',
	'site_footer'		=> 'Pied de page',
	'site_offline'		=> 'Mettre le site en maintenance (off-line)',
	'interface_legend'	=> 'Interface utilisateur',
	'interface_lang'	=> 'Langue',
	'interface_date_format'	=> 'Format date',
	'interface_date_eur'	=> 'Europ&eacute;en (jj/mm/aa)',
	'interface_date_usa'	=> 'Am&eacute;ricain (mm/jj/aa)',
	'options_legend'	=> 'Options membres',
	'options_auto_login'	=> 'Permettre aux membres de se connecter automatiquement',
	'options_pass_reminder'	=> 'Permettre aux membres de demander un nouveau mot de passe en cas d\'oubli',
	'options_register'		=> 'Permettre aux visiteurs de s\'inscrire comme membre',
	'options_register_man'	=> 'Validation manuelle par l\'administrateur',
	'options_register_auto'	=> 'Activation des comptes par les visiteurs',
	'settings_saved'	=> 'Pr&eacute;f&eacute;rences syst&egrave;me enregistr&eacute;es'
);

// email description
$GLOBALS['langSystemEmail'] = array(
    'sign_up_new'			=> 'Inscription: nouvelle demande',
    'sign_up_pending'		=> 'Inscription: demande d\'inscription en cours de traitement',
    'sign_up_activation'	=> 'Inscription: activation du compte',
    'sign_up_confirmation'	=> 'Inscription: inscription confirmee',
    'members_pass_reminder'	=> 'Membres: Rappel de mot de passe'
);

// email subjects
$GLOBALS['langSystemEmailSubject'] = array(
	'sign_up_new'			=> 'Nouvelle demande d\'inscription',
	'sign_up_pending'		=> 'Demande d\'inscription en cours de traitement',
	'sign_up_activation'	=> 'Activation de votre compte',
	'sign_up_confirmation'	=> 'Confirmation de votre compte',
	'members_pass_reminder' => 'Votre nouveau mot de passe'
);

// email stuff
$GLOBALS['langSystemEmailStuff'] = array(
	'setup_prefix'	=> 'Pr&eacute;fixe sujet emails',
	'setup_address'	=> 'Adresse par d&eacute;faut',
	'setup_smtp'	=> 'Envoyer les emails par SMTP plut&ocirc;t que par fonction mail() de PHP',
	'setup_server'	=> 'Serveur',
    'from'          => 'Exp.',
    'to'            => 'Dest.',
    'cc'            => 'Copie',
    'dir'           => 'Dir.',
    'dir_in'        => 'IN',
    'dir_out'       => 'OUT',
    'alert'         => 'Message',
    'name'          => 'Nom',
    'email'         => 'Email',
    'subject'       => 'Sujet',
    'body_template' => 'Message',
    'enabled'       => 'Activ&eacute;',
    'enable_label'  => 'Activer message',
    'disabled'      => 'Desactiv&eacute;',
    'disable_label' => 'Desactiver message',
    'link_edit'     => 'Modifier message',
    'check_recipient'	=> 'Veuillez saisir une adresse email valide',
	'check_subject'		=> 'Veuillez saisir le sujet du message',
	'check_injection'	=> 'Tentative de modification des headers d&eacute;tect&eacute;e',
	'check_active'		=> 'L\'alerte email n\'est pas active',
	'send_ok'			=> 'Message envoy&eacute; avec succ&egrave;s',
	'send_not_found'	=> 'Erreur envoi email: param&egrave;tres non sp&eacute;cifi&eacute;s',
	'send_no_address'	=> 'Erreur envoi email: pas d\'adresse email sp&eacute;cifi&eacute;e',
	'send_error'		=> 'Erreur envoi email: erreur syst&egrave;me'
);
