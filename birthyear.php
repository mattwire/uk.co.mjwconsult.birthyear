<?php

require_once 'birthyear.civix.php';
require_once 'CRM/BirthYear/BirthYear.php';
require_once 'CRM/BirthYear/Utils.php';


/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function birthyear_civicrm_config(&$config) {
  _birthyear_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function birthyear_civicrm_xmlMenu(&$files) {
  _birthyear_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function birthyear_civicrm_install() {
  _birthyear_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function birthyear_civicrm_postInstall() {
  _birthyear_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function birthyear_civicrm_uninstall() {
  _birthyear_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function birthyear_civicrm_enable() {
  _birthyear_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function birthyear_civicrm_disable() {
  _birthyear_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function birthyear_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _birthyear_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function birthyear_civicrm_managed(&$entities) {
  _birthyear_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function birthyear_civicrm_caseTypes(&$caseTypes) {
  _birthyear_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function birthyear_civicrm_angularModules(&$angularModules) {
  _birthyear_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function birthyear_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _birthyear_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_post()
 */
function birthyear_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  CRM_BirthYear::process_post($op, $objectName, $objectId, $objectRef);
}

/**
 * Implements hook_civicrm_custom
 */
function birthyear_civicrm_custom( $op, $groupID, $entityID, &$params ) {
  CRM_BirthYear::process_custom($op, $groupID, $entityID, $params);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function birthyear_civicrm_buildForm($formName, &$form) {
  // hook in the various renderers
  CRM_BirthYear::process_buildForm($formName, $form);
}

/**
 * implement the hook to customize the summary view
 */
function birthyear_civicrm_pageRun( &$page ) {
  if ($page->getVar('_name') == 'CRM_Contact_Page_View_Summary') {
    $script = file_get_contents(CRM_Core_Resources::singleton()->getUrl('uk.co.mjwconsult.birthyear', 'js/summary_view.js'));
    $script = str_replace('EXTENDED_DEMOGRAPHICS', CRM_BirthYear_Utils::getExtendedDemographicsGroupID(), $script);
    CRM_Core_Region::instance('page-header')->add(array(
      'script' => $script,
    ));
  }
}

/**
 * Get the birth year custom field
 */
$_birthyear_custom_field = NULL; // static, global variable
function birthyear_get_custom_field() {
  global $_birthyear_custom_field;
  if ($_birthyear_custom_field === NULL) {
    // load custom field data
    $_birthyear_custom_field = civicrm_api3('CustomField', 'getsingle', array('name' => "birth_year"));
  }
  return $_birthyear_custom_field;
}

