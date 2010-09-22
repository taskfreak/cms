<?php

/* this is the dashboard */

define('CMS_ADMIN', true); // yes, we are in admin section
// this will include required javascripts such as mootools core

include '../_include.php';

// initialize CMS
TznCms::init(5, true); // admin page, auto load modules

// set referring page
TznCms::setAdminRef();

// prepare module blocks
$objItemLatestList = new CmsObjectPage();
$objItemLatestList->addWhere($objItemLatestList->gField('pageId').' > 0');
if (@defined('CMS_RECENT_FILTER')) {
	$objItemLatestList->addWhere(CMS_RECENT_FILTER);
}
$objItemLatestList->addOrder('lastChangeDate DESC');
$objItemLatestList->setPagination(15);
$objItemLatestList->loadList();

// load other modules updates
$arrModules = array();
foreach ($GLOBALS['objCms']->modules as $key => $obj) {
	if (!is_object($obj)) {
		continue;
	}
	if (!method_exists($obj, 'adminHome')) {
		continue;
	}
	$obj->adminHome();
	$arrModules[$key] = $obj;
}

// load latest visitors
$objUserLatestLogin = new Member();
$objUserLatestLogin->addOrder('lastLoginDate DESC');
$objUserLatestLogin->setPagination(6);
$objUserLatestLogin->loadList();

// load latest registered
$objUserLatestRegister = new Member();
$objUserLatestRegister->addOrder('creationDate DESC');
$objUserLatestRegister->setPagination(6);
$objUserLatestRegister->loadList();

/* === PREPARE HTML ======================================================== */


/* === HTML ================================================================ */

TznCms::getHeader(true);

?>
	<div id="main">
		<?php
		if ($objItemLatestList->rCount()) {
		?>
		<div class="box full">
			<h2><?php echo $GLOBALS['langAdminDashboard']['latest_changes']; ?></h2>
			<div class="table boxed clickable">
			<?php
			while ($objItem = $objItemLatestList->rNext()) {
			?>
				<div class="row">
					<div class="col c60"><?php
						echo '<a href="'.$objItem->getUrl().'">'.$objItem->page->get('menu').'</a> ';
						echo '<small>'.$objItem->getSummary().'&nbsp;</small>'; 
					?></div>
					<div class="col c15"><a href="<?php 
						echo 'page.php?id='.$objItem->page->id;
					?>"><?php echo $objItem->getType(); ?></a></div>
					<div class="col c10"><?php echo $objItem->author->getShortName(); ?></div>
					<div class="col c15"><?php echo $objItem->getDtm('lastChangeDate', CMS_DATETIME);?></div>
				</div>
			<?php
			}
			?>
			</div>
			<div class="footer">
				...
			</div>
		</div>
		<?php
		}
		
		if (count($arrModules)) {
			foreach ($arrModules as $key => $obj) {
				$obj->view();
			}
		}
		
		?>
		<div class="box half">
			<h2><?php echo $GLOBALS['langAdminDashboard']['latest_visits']; ?></h2>
			<div class="table boxed clickable">
			<?php
			while ($objTmp = $objUserLatestLogin->rNext()) {
			?>
				<div class="row">
					<div class="col c70"><a href="<?php 
						echo TznCms::getUri('admin/member.php?id='.$objTmp->id); ?>"><?php 
						echo $objTmp->getName(); ?></a></div>
					<div class="col c30"><?php echo $objTmp->getDte('lastLoginDate', CMS_DATETIME); ?></div>
				</div>
			<?php
			}
			?>
			</div>
			<div class="footer">
				<a href="<?php echo TznCms::getUri('admin/member.php?userOrder=0'); ?>"><?php echo $GLOBALS['langAdminDashboard']['all_visits']; ?></a>
			</div>
		</div>
		<div class="box half">
			<h2><?php echo $GLOBALS['langAdminDashboard']['latest_register']; ?></h2>
			<div class="table boxed clickable">
			<?php
			while ($objTmp = $objUserLatestRegister->rNext()) {
			?>
				<div class="row">
					<div class="col c70"><a href="<?php 
						echo TznCms::getUri('admin/member.php?id='.$objTmp->id); ?>"><?php 
						echo $objTmp->getName(); ?></a></div>
					<div class="col c30"><?php echo $objTmp->getDte('creationDate', CMS_DATETIME); ?></div>
				</div>
			<?php
			}
			?>
			</div>
			<div class="footer">
				<a href="<?php echo TznCms::getUri('admin/member.php?userOrder=2'); ?>"><?php echo $GLOBALS['langAdminDashboard']['all_register']; ?></a>
			</div>
		</div>
	</div>
<?php

TznCms::getFooter(true);