<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2009 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
	}


$rule = $rulecollection->getRuleClass();

checkRight($rule->right,"r");

if(!isset($_GET["id"])) $_GET["id"] = "";

$rulecriteria = new RuleCriteria();
$ruleaction = new RuleAction();

if (isset($_POST["delete_criteria"]))
{
	checkRight($rule->right,"w");

	if (count($_POST["item"]))
		foreach ($_POST["item"] as $key => $val)
		{
			$input["id"]=$key;
			$rulecriteria->delete($input);
		}

	// Is a cached Rule ?
	if(method_exists($rule,'deleteCacheByRuleId')){
		$rule->deleteCacheByRuleId($_POST["rules_id"]);
	}

	glpi_header($_SERVER['HTTP_REFERER']);
}
if (isset($_POST["delete_action"]))
{
	checkRight($rule->right,"w");

	if (count($_POST["item"]))
		foreach ($_POST["item"] as $key => $val)
		{
			$input["id"]=$key;
			$ruleaction->delete($input);
		}

	// Is a cached Rule ?
	if(method_exists($rule,'deleteCacheByRuleId')){
		$rule->deleteCacheByRuleId($_POST["rules_id"]);
	}

	glpi_header($_SERVER['HTTP_REFERER']);
}
elseif (isset($_POST["add_criteria"]))
{
	checkRight($rule->right,"w");

	// Is a cached Rule ?
	if(method_exists($rule,'deleteCacheByRuleId')){
		$rule->deleteCacheByRuleId($_POST["rules_id"]);
	}

	$rulecriteria->add($_POST);


	glpi_header($_SERVER['HTTP_REFERER']);
}
elseif (isset($_POST["add_action"]))
{
	checkRight($rule->right,"w");

	// Is a cached Rule ?
	if(method_exists($rule,'deleteCacheByRuleId')){
		$rule->deleteCacheByRuleId($_POST["rules_id"]);
	}

	$ruleaction->add($_POST);

	glpi_header($_SERVER['HTTP_REFERER']);
}
elseif (isset($_POST["update_rule"]))
{
	checkRight($rule->right,"w");

	// Is a cached Rule ?
	if(method_exists($rule,'deleteCacheByRuleId')){
		$rule->deleteCacheByRuleId($_POST["id"]);
	}

	$rule->update($_POST);
	Event::log($_POST['id'], "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][21]);

	glpi_header($_SERVER['HTTP_REFERER']);
} elseif (isset($_POST["add_rule"]))
{
	checkRight($rule->right,"w");

	$newID=$rule->add($_POST);
	Event::log($newID, "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][20]);

	glpi_header($_SERVER['HTTP_REFERER']."?id=$newID");
} elseif (isset($_POST["delete_rule"]))
{
	checkRight($rule->right,"w");
	$rulecollection->deleteRuleOrder($_POST["ranking"]);
	$rule->delete($_POST);
	Event::log($_POST["id"], "rules", 4, "setup", $_SESSION["glpiname"]." ".$LANG['log'][22]);

	// Is a cached Rule ?
	if(method_exists($rule,'deleteCacheByRuleId')){
		$rule->deleteCacheByRuleId($_POST["id"]);
	}

	glpi_header(str_replace('.form','',$_SERVER['PHP_SELF']));
}

commonHeader($LANG['common'][12],$_SERVER['PHP_SELF'],"admin",$rulecollection->menu_type,$rulecollection->menu_option);

$rule->showForm($_SERVER['PHP_SELF'],$_GET["id"]);
if (!empty($_GET["id"])&&$_GET["id"] >0) {
	$rule->showCriteriasList($_SERVER['PHP_SELF'],$_GET["id"]);
	$rule->showActionsList($_SERVER['PHP_SELF'],$_GET["id"]);
}
commonFooter();
?>
