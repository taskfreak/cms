<?php
/****************************************************************************\
* Tirzen CMS                                                                 *
******************************************************************************
* Version: 0.1                                                               *
* Authors: Stan Ozier <stan@tirzen.com>                                      *
* License:  http://www.gnu.org/licenses/gpl.txt (GPL)                        *
\****************************************************************************/

?>
	<table class="form_info">
		<thead>
			<tr>
				<th>Date Cr&eacute;ation</th>
				<th>Derni&eacute;re modification</th>
				<th>Dernier envoi</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php $this->content->_join->p('creationDate','SHT'); ?></td>
				<td><?php $this->content->p('lastChangeDate','SHT'); ?></td>
				<td><?php $this->content->_join->p('deliveryDate','SHT'); ?></td>
			</tr>
		</tbody>
	</table>
	<p class="ctr">
	<?php
		$urlAjax = CMS_WWW_URI.'ajax.php?module=mailing_list&amp;';
		if ($this->someReady) {
			$urlAjax .= 'id='.$this->someId.'&amp;action=';
	?>
	
		Un pr&eacute;c&eacute;dent envoi &agrave; &eacute;t&eacute; interrompu<br />
		(<?php echo $pSomeReady; ?> destinataires en attente)<br /><br />
		<a href="<?php echo $urlAjax.'sendEmail'; ?>" class="button" rel="ajaxed">Continuer</a>
		<a href="<?php echo $urlAjax.'sendCancel'; ?>" rel="ajaxed">Annuler l'envoi en cours</a>
	<?php
		} else {
			$urlAjax .= 'id='.$this->content->id.'&amp;action=';
	?>
		<a href="<?php echo CMS_WWW_URI.'admin/page.php?id='.$GLOBALS['objPage']->id.'&amp;action=edit&amp;item='.$this->content->id ?>" class="button">Modifier le contenu de la lettre</a>
		<a href="<?php echo $urlAjax.'sendTestInit'; ?>" class="button marge" rel="ajaxed">Envoyer un exemplaire test</a>
		<a href="<?php echo $urlAjax.'sendMassInit'; ?>" class="button marge" rel="ajaxed">Envoyer &agrave; la liste de diffusion</a>
	<?php
		}
	?>
	</p>
	<h3 class="acctog">Version HTML</h3>
	<div class="accinf">
	<?php
		echo $this->content->getNewsletterHtml();
	?>
	</div>
	<h3 class="acctog">Version Texte</h3>
	<div class="accinf">
		<pre><?php
		echo $this->content->getNewsletterText();
		?></pre>
	</div>