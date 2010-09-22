<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 3.0                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

// comments
$GLOBALS['langComment'] = array(
	'comments'			=> 'Commentaires',
	'posted_on'			=> 'Post&eacute; le',
	'posted_by'			=> 'par',
	'none'				=> 'Aucun commentaire pour le moment',
	'edit'				=> 'modifier',
	'delete'			=> 'supprimer',
	'delete_confirm'	=> 'R&eacute;ellement supprimer ce commentaire ?',
	'post'				=> 'Ajouter un commentaire',
	'post_body'			=> 'Commentaire',
	'post_submit'		=> 'Publier votre commentaire'
);

// top menu / left menu
$GLOBALS['langMenu'] = array (
	'administration'	=> 'administration',
	'admin_summary'		=> 'Suivi de l\'activit&eacute;',
	'admin_help'		=> 'Vous trouverez ici le tableau de bord des derni&egrave;res activit&eacute;s survenues sur le site.<br />Pour modifier le contenu du site, dirigiez-vous vers <a href="admin_page_list.php">le plan du site</a>.',
	'admin_pages'		=> 'Plan du site',
	'admin_users'		=> 'Gestion utilisateurs',
	'admin_teams'		=> 'Gestion des groupes',
	'system'			=> 'Configuration',
	'system_emails'		=> 'Alertes email',
	'system_options'	=> 'Modules &amp; options',
	'system_config'		=> 'Pr&eacute;f&eacute;rences Syst&egrave;me',
	'home'				=> 'Accueil',
	'sitemap'			=> 'plan du site',
	'edit'				=> 'modifier cette page',
	'prefs'				=> 'Pref&eacute;rences',
	'pages'				=> 'Pages',
	'logged'			=> 'Connect&eacute; en tant que',
	'exterlinks'		=> 'Liens ext&eacute;rieurs',
	'menu'				=> 'Menu',
	'homepage'			=> 'Page d\'accueil',
	'logout'			=> 'd&eacute;connexion'
);
//login_info.php /  login page info if error
$GLOBALS['langLoginInfo'] = array (
	'notamember'		=> '<b>Si vous n\'&ecirc;tes pas membre</b> mais d&eacute;sirez le venir,',
	'notamember1'		=> 'demander un compte ici',
	'problemlogin'		=> 'Si vous avez des probl&egrave;mes pour vous connecter</b>, cela peut-&ecirc;tre du aux raisons suivantes:',
	'problemlogin1'		=> 'Vous n\&ecirc;tes pas connect&eacute; ou votre session a expir&eacute;',
	'problemlogin2'		=> 'N\'avez-vous pas entr&eacute; un mauvais mot de passe?',
	'problemlogin3'		=> 'Demandez-en un nouveau pas email',
	'problemlogin4'		=> 'Votre navigateur ne supporte pas javascript',
	'problemlogin5'		=> 'Votre navigateur n\'a pas l\'option cookies activ&eacute;e',
	'problemlogin6'		=> 'Vous n\'avez pas suffisament de droit pour acc&eacute;der &agrave; la page demand&eacute;e',
);
//logout.php / logout page
$GLOBALS['langLogout'] = array (
	'nowlogout'			=> 'Vous &ecirc;tes maintenant d&eacute;connect&eacute;. Au revoir.',
	'lastlogin'			=> 'Pr&eacute;c&eacute;dente connexion',
	'from'				=> 'Depuis',
	'backhome'			=> 'Retour &agrave; la page d\'accueil',
	'logagain'			=> 'Se connecter &agrave; nouveau',
);

//footer
$GLOBALS['langFooter'] = array (
	'homepage'			=> 'Retour page d\'accueil'
);
//admin.php / content in administration section
$GLOBALS['langAdmin'] = array (
	'sitemap'			=> 'Carte du site',
	'help'				=> 'Aide',
	'help_legend'		=> 'Aide &amp; L&eacute;gende',
	'addsection'		=> 'ajoutez une nouvelle section (top niveau)',
	'section'			=> 'section',
	'sectionactive'		=> 'section active',
	'sectionhidden'		=> 'section non publi&eacute;e',
	'page'				=> 'page',
	'pageactive'		=> 'page active',
	'pagenomenu'		=> 'page cach&eacute;e',
	'pagehidden'		=> 'page non publi&eacute;e',
	'view'				=> 'voir',
	'edit'				=> 'modifier',
	'setup'				=> 'ent&ecirc;te',
	'up'				=> 'monter',
	'add'				=> 'ajouter',
	'install'			=> 'installer',
	'delete'			=> 'effacer',
	'remove'			=> 'retirer',
	'confirmdel'		=> 'D&eacute;sirez-vous vraiment effacer cette page et son contenu?',
	'create_page'		=> 'Nouvelle page',
	'page_title'		=> 'Nouvelle page',
	'menu_title'		=> 'Nouvelle page',
	'add_image'			=> 'Ajouter une image',
	'pic_gallery'		=> 'Gallerie images',
	'date'				=> 'Date',
	'pic_title'			=> 'Titre de l\'image',
	'pic'				=> 'Image',
	'desc'				=> 'Description',
	'publish'			=> 'publier l\'image',
	'add_img_to'		=> 'Ajouter une image &agrave',
	'del_confirm'		=> 'Souhaitez-vous r&eacute;ellement supprimer cet &eacute;l&eacute;ment?',
	'del_confirm_user'	=> 'Souhaitez-vous r&eacute;ellement suprimer cet utilisateur?',
	'help_module_opts'	=> 'Modifiez ici les options g&eacute;n&eacute;rales des modules'
);
// system
$GLOBALS['langSystem'] = array(
	'email_legend'			=> 'Alertes email',
	'email_list_help_on'	=> 'Alerte activ&eacute;e',
	'email_list_help_off'	=> 'Alerte desactiv&eacute;e',
	'email_list_help_in'	=> 'Email entrant',
	'email_list_help_out'	=> 'Email sortant',
);
//admin_module.php / content in Modules & Options section
$GLOBALS['langModule'] = array (
	'list'				=> 'Liste des modules',
	'options'			=> 'options',
	'saveoptions'		=> 'Enregistrer les options',
);
//admin_page_content.php / content in edit page
$GLOBALS['langEditContent'] = array (
	'contentup'			=> 'Contenu mis &agrave; jour!',
	'pageup'			=> 'Page mise &agrave; jour!',
	'page_no_change'	=> 'Pas de changements &agrave; enregistrer',
	'page_published'	=> 'Page publi&eacute;e avec succ&egrave;s',
	'pageprotect'		=> 'Cette page est prot&eacute;g&eacute;e'
);
//admin_page_hearder.php/ content in the setup page
$GLOBALS['langSetHeaders'] = array (
	'newpage'			=> 'Nouvelle page',
	'duplicate'			=> 'Dupliquer',
	'duplicate_confirm'	=> 'Confirmer la duplication de cette page ainsi que de toutes ses sous pages...',
	'headersup'			=> 'ent&ecirc;tes de page mis &agrave; jour!',
	'errornotfound'		=> 'ERROR:Page introuvable',
	'basic'				=> 'Informations de Base',
	'title'				=> 'Titre',
	'menu'				=> 'Menu',
	'status'			=> 'Etat',
	'parent'			=> 'Parent',
	'description'		=> 'Description',
	'publish'			=> 'contenu publi&eacute;',
	'publish_auto'		=> 'publier automatiquement',
	'display'			=> 'Visibilit&eacute;',
	'display_ok'		=> 'contenu publi&eacute;',
	'display_public'	=> 'contenu publique (visible par tout visiteur)',
	'display_protected'	=> 'contenu prot&eacute;g&eacute; (visible par les membres uniquement)',
	'display_private'	=> 'contenu priv&eacute; (visible par les membres associ&eacute;s uniquement)',
	'display_menu'		=> 'appara&icirc;t dans le menu de navigation',
	'advanced'			=> 'Pr&eacute;f&eacute;rences avanc&eacute;es',
	'showhide'			=> 'Montrer/Cacher',
	'shortcut'			=> 'Raccourci',
	'template'			=> 'Mod&egrave;le',
	'module'			=> 'Module',
	'keywords'			=> 'Mots cl&eacute;s',
	'charset'			=> 'Charset',
	'protection'		=> 'Protection',
	'nocontent'			=> 'Contenu v&eacute;rouill&eacute;',
	'nosetup'			=> 'Pr&eacute;f&eacute;rences v&eacute;rouill&eacute;es',
	'noadd'				=> 'Pas de sous pages',
	'nomove'			=> 'D&eacute;placement non autoris&eacute;',
	'nodelete'			=> 'Suppression impossible',
	'apply_to_children'	=> 'Modifier la description de toutes les pages filles',
	'status_published'	=> 'Publi&eacute;e',
	'status_unpublished'=> 'Non publi&eacute;e',
	'status_public'		=> 'publique',
	'status_protected'	=> 'prot&eacute;g&eacute;e',
	'status_private'	=> 'priv&eacute;e'
);
//admin-user.php / users administration section
$GLOBALS['langUserAdmin'] = array (
	'noaccess'			=> 'acc&egrave;s refus&eacute;',
	'users'				=> 'Utilisateurs',
	'newuser'			=> 'cr&eacute;er utilisateur',
	'newregister'		=> 'Demande d\'acc&egrave;s membre',
	'administrator'		=> 'administrateur',
	'moderator'			=> 'moderateur',
	'member'			=> 'membre',
	'guest'				=> 'invit&eacute;',
	'contact'			=> 'contact (pas d\'acc&egrave;s)',
	'search_name'		=> 'Saisir un nom',
	'order_creation'	=> 'Droits, Cr&eacute;ation',
	'order_rights'		=> 'Droits, Nom',
	'order_visit'		=> 'Derni&egrave;re visite',
	'info_creation'		=> 'Compte cr&eacute;&eacute; le',
	'info_login'		=> 'Derni&egrave;re connexion le',
	'info_visits'		=> 'Nombre total de visites',
	'info_failed'		=> 'Tentatives d\'acc&egrave;s'
);
//admin_user_edit.php / user edit section
$GLOBALS['langUserContent'] = array (
	'infosaved'			=> 'Donn&eacute;es utilisateur mise &agrave; jour',
	'infocreated'		=> 'Utilisateur cr&eacute;&eacute; avec succ&egrave;s',
	'errorform'			=> 'ERROR:Il y a des erreurs dans ce fomulaire<br />Donn&eacute;es non enregistr&eacute;es!',
);

//Submit label
$GLOBALS['langSubmit'] = array (
	'close'				=> 'Fermer',
	'closenosave'		=> 'Fermer sans modifier',
	'closeconfirm'		=> 'Abandonner les modifications?',
	'backtoprevious'	=> 'Retour &agrave; la page pr&eacute;c&eacute;dente',
	'create'			=> 'Cr&eacute;er',
	'save'				=> 'Enregistrer',
	'publish'			=> 'Enregistrer &amp; Publier',
	'saveclose'			=> 'Enregistrer &amp; Fermer',
	'savecontinue'		=> 'Enregistrer &amp; Continuer',
	'saveaddmore'		=> 'Enregistrer &amp; Cr&eacute;er nouveau',
	'cancel'			=> 'Annuler',
	'update'			=> 'Mettre &agrave; jour',
	'updatecontinue'	=> 'Mettre &agrave; jour &amp; Continuer',
	'delete'			=> 'Supprimer'
);
//error.php / error page
$GLOBALS['langError'] = array (
	'errorpagetitle'	=> 'TZN CMS erreur',
	'houston'			=> 'Kourou, on a un probl&egrave;me...!',
	'checkinstall'		=> 'cliquer ici pour v&eacute;rifier l\'installation',
	'nofolder'			=> '(et le r&eacute;pertoire d\'installation est introuvable)',
	'backtry'			=> 'cliquez ici pour revenir et r&eacute;essayer',
	'tryagain'			=> 'cliquez ici pour r&eacute;essayer',
	'homeback'			=> 'cliquer ici pour revenir &agrave; la page d\'accueil',
	'upwebsite'			=> 'Nous mettons &agrave; jour notre site',
	'bebacksoon'		=> ' nous revenons prochainement',
	'pagereserved'		=> 'Page reserv&eacute;e aux membres',
	'pleaselogin'		=> 'Connectez-vous',
	'cominsoon'			=> 'Bient&ocirc;t...',
	'loginfailed'		=> 'Echec de connexion: ',
	'usercannotenable'	=> 'Impossible de donner acc&egrave;s (nom utilisateur non pr&eacute;cis&eacute;)'
);
//form_to_mail module / public_content.php
$GLOBALS['langForm_content'] = array (
	'sent'				=> 'message envoy&eacute;!',
	'error_sent'		=> 'erreur durant l\'envoi du message',
);
//html_content & straigth content module / admin_options.php
$GLOBALS['langHTML'] = array (
	'height'			=> 'Hauteur de la zone de texte',
);
// fields and column labels
$GLOBALS['langForm'] = array (
	'label'				=> 'Label',
	'name'				=> 'Nom',
	'type'				=> 'Type',
	'compulsory'		=> 'Obligatoire',
	'addfield'			=> 'ajouter un nouveau champ',
	'language'			=> 'Langage',
	'options'			=> 'Options',
	'introduction'		=> 'Introduction',
	'email_subject'		=> 'Sujet Email',
	'send_to'			=> 'Envoy&eacute; &agrave;',
	'copy'				=> 'Copi&eacute; &agrave;',
	'submit_button'		=> 'Submit button',
	'response'			=> 'R&eacute;ponse',
	'footer'			=> 'Pied de page',
	'secu_img'			=> 'Image de s&eacute;curit&eacute;',
	'enable_contact'	=> 'Activer le formulaire de contact (envoie r&eacute;el des emails)',
	'style'				=> 'Style',
	'large'				=> 'large',
	'medium'			=> 'medium',
	'small'				=> 'small',
	'tiny'				=> 'tiny',
	'same_line'			=> 'M&ecirc;me ligne que la pr&eacute;c&eacute;dente',
	'text'				=> 'Texte',
	'choice'			=> 'Choix',
	'other'				=> 'Autre',
	'single_line'		=> 'ligne simple',
	'default_value'		=> 'Valeur par d&eacute;faut',
	'multi_line'		=> 'Plusieurs lignes',
	'rows'				=> 'lignes',
	'valid_data'		=> 'Validation des donn&eacute;es',
	'alphanumeric'		=> 'alphanumeric',
	'integer'			=> 'integer',
	'decimal'			=> 'decimal',
	'email'				=> 'E-mail',
	'checkbox'			=> 'Choix muliples (checkbox)',
	'radio'				=> 'Choix unique (radio)',
	'select'			=> 'Menu d&eacute;roulant (select)',
	'choice'			=> 'Choix',
	'hidden'			=> 'Champ cach&eacute;',
	'value'				=> 'Valeur',
	'yes'				=> 'Oui',
	'no'				=> 'Non',
	'formnot'			=> 'Formulaire non cr&eacute;&eacute;',
	'wait'				=> 'Attendez svp...',
	'disable'			=> 'Formulaire temporairement inaccessible',
	'compulsory_legend' 		=> 'Les champs marqu&eacute;s d\'une asterisque sont obligatoires.',
	'security_label'			=> 'Code anti&nbsp;spam',
	'security_image'			=> 'Veuillez saisir les caract&egrave;res apparaissant dans l\'image',
	'security_image_error' 		=> 'Mauvais code de s&eacute;curit&eacute;, veuillez essayer de nouveau (votre navigateur doit accepter les cookies)'
);

$GLOBALS['langTaskDetails'] = array (
    'history_date'      => 'date',
    'history_user'      => 'utilisateur',
    'history_what'      => 'action'

);
// project related
$GLOBALS['langTeam'] = array(
    'team'           	=> 'Groupe',
    'teams'				=> 'Groupes',
    'teams_legend'     	=> 'Groupes d\'utilisateurs',
    'tab_info'			=> 'Info groupe',
    'tab_members'		=> 'Membres',
    'tab_pages'			=> 'Pages',
    'name'				=> 'Nom',
    'description'       => 'Description',
    'position'          => 'Position',
    'members'           => 'membre(s)',
    'members_legend'    => 'Membres du groupe',
    'pages_legend'		=> 'Pages du groupe',
    'status'            => 'Status',
    'action'            => 'Action',
    'new_team'			=> 'cr&eacute;er un nouveau groupe',
    'project_history'   => 'View status change history',
    'remove_confirm'    => 'really remove from the project?',
    'new_member'		=> 'Ajouter un membre',
    'search_member'		=> 'Chercher un membre',
    'new_page'			=> 'Associer une page',
    'page'				=> 'Page',
    'user_no_team'		=> 'Associ&eacute; &agrave; aucun projet',
    'visibility'		=> 'Acc&egrave;s',
    'visi_public'		=> 'Publique',
    'visi_protected'	=> 'Prot&eacute;g&eacute;',
	'visi_private'		=> 'Priv&eacute;',
	'visi_disabled'		=> 'D&eacute;sactiv&eacute;',
	'enabled_label'		=> 'Activer le groupe',
	'help_no_edit'		=> 'Informations g&eacute;n&eacute;rales du groupe'
);
$GLOBALS['langTeamContent'] = array (
	'infosaved'			=> 'Informations groupe mise &agrave; jour',
	'infocreated'		=> 'Groupe cr&eacute;&eacute; avec succ&egrave;s',
	'errorform'			=> 'ERROR:Il y a des erreurs dans ce fomulaire<br />Donn&eacute;es non enregistr&eacute;es!',
);
// user related
$GLOBALS['langUser'] = array(
    'information'       => 'Informations personnelles',
    'user'              => 'Utilisateur',
    'name'              => 'Nom',
    'title'             => 'Titre',
    'first_name'        => 'Pr&eacute;nom',
    'middle_name'       => '',
    'last_name'         => 'Nom',
    'company'			=> 'Soci&eacute;t&eacute;',
    'address'           => 'Addresse',
    'location'          => 'Lieu',
    'city'              => 'Ville',
    'state'             => 'Etat',
    'zip_code'			=> 'Code postal',
	'country'           => 'Pays',
	'language'			=> 'Langue',
	'phone'				=> 'T&eacute;l&eacute;phone',
	'mobile'			=> 'Tel. Portable',
	'fax'				=> 'Fax',
    'email'             => 'Email',
    'email_address'		=> 'Adresse Email',
    'position'          => 'Niveau',
    'last_login'        => 'Derni&egrave;re connexion',
    'action'            => 'Action',
    'delete_confirm'    => 'Vous voulez vraiment supprimer cet utilisateur?',
    'enable_confirm'    => 'Vous voulez vraiment activer ce compte?',
    'disable_confirm'   => 'Vous voulez vraiment d&eacute;sactiver ce compte?',
    'account'           => 'Param&egrave;tres du compte',
    'account_legend'    => 'Choisissez un nom d\'utilisateur et un mot de passe',
    'username'          => 'Nom d\'utilisateur',
    'password'          => 'mot de passe',
    'password_confirm'  => '(confirmation)',
    'auto_login'        => 'se connecter automatiquement depuis cet ordinateur',
    'password_legend'   => 'Entrer un mot de passe (et confirmer) seulement si vous d&eacute;sirez en changer.',
    'password_reminder'	=> 'mot de passe oublié?',
    'password_intro'	=> "<strong>Si vous &ecirc;tes d&eacute;j&agrave; membre</strong> mais avez oubli&eacute; vos codes d'acc&egrave;s, veuillez remplir et envoyer le fomulaire suivant:",
    'password_footer'	=> 'Un message &eacute;l&eacute;ctronique (e-mail) contenant vos codes vous sera alors envoy&eacute;',
    'password_email'	=> 'V&eacute;rifiez vos emails',
    'password_reminded'	=> 'Un message &eacute;l&eacute;ctronique contenant vos codes d\'acc&egrave;s<br />vient de vous &ecirc;tre envoy&eacute;',
    'back_to_home'		=> 'retour page d\'accueil',
    'back_login_again'	=> 's\'identifier de nouveau',
    'back_to_login'		=> 'retour au formulaire d\'identification',
    'teams_legend'		=> 'Liste des groupes associ&eacute;s au membre',
    'enabled_label'     => 'Le compte est actif!',
    'nickname'			=> 'Pseudonyme',
   	'nickname_legend'	=> 'Le pseudonyme vous permet de cacher votre r&eacute;elle identit&eacute;.'
);	

// buttons
$GLOBALS['langButton'] = array(
	'send'				=> 'Envoyer',
	'submit'			=> 'Soumettre la demande',
	'cancel'			=> 'Annuler',
    'add'               => 'Cr&eacute;er',
	'add_account'		=> 'Cr&eacute;er le compte',
    'save'              => 'Enregistrer',
    'update'            => 'Enregistrer les modifications',
    'reset'             => 'Recommencer',
    'back'              => 'Retour &agrave; la liste',
    'ignore'            => 'Annuler les modifications',
    'back'              => 'Retour &agrave; la liste',
    'search'			=> 'Rechercher',
    'associate'			=> 'Associer'
);


// error and information messages
$GLOBALS['langMessage'] = array (
	'not_found'					=> 'Donn&eacute;e introuvable',
    'not_found_or_denied'       => 'Donn&eacute;e introuvable ou acc&egrave;s non autoris&eacute;e',
    'denied'                    => 'Acc&egrave;s non autoris&eacute;!',
    'search_no_result'			=> 'Recherche infructueuse',
    'data_updated'				=> 'Donn&eacute;es mises &agrave; jour',
    'data_deleted'				=> 'Donn&eacute;es effac&eacute;es!',
    'data_created'				=> 'Donn&eacute;es cr&eacute;es',
	'close_window'			    => 'fermer la fen&ecirc;tre',
    'session_expired'           => 'Votre session &agrave; expir&eacute;'
);

$GLOBALS['langRss'] = array (
    'no_task'       => 'No task for today',
    'error_login'   => 'Authentication failed'
);

