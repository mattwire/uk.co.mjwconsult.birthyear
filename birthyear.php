<?php

require_once 'birthyear.civix.php';

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
 * @param $op
 * @param $objectName
 * @param $objectId
 * @param $objectRef
 */
function birthyear_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  // fills the custom field with the correct birth year if someone updates CiviCRM's Birth Date field

  if ($objectRef instanceof CRM_Contact_DAO_Contact) {
    // Get contact ID birth date field ($params['entity_id'])
    $contactBirthDate = civicrm_api3('Contact', 'get', array(
      'sequential' => 1,
      'return' => "birth_date",
      'id' => $objectRef->id,
    ));
    if (!empty($contactBirthDate['values'][0]['birth_date'])) {
      // Contact Birth date has a value
      // Get custom birth date value
      // Contact birth date to year
      $contactBirthYear = new DateTime($contactBirthDate['values'][0]['birth_date']);

      $customBirthYear = civicrm_api3('CustomField', 'get', array(
        'sequential' => 1,
        'name' => "birth_year",
      ));
      $birthYearFieldId = $customBirthYear['values'][0]['id'];
      $customValues = civicrm_api3('CustomValue', 'create', array(
        'entity_id' => $objectRef->id,
        'custom_'.$birthYearFieldId => $contactBirthYear->format('Y'),
      ));
    }
  }
}

/**
 * Implements hook_civicrm_custom
 * @param $op
 * @param $groupID
 * @param $entityID
 * @param $params
 */
function birthyear_civicrm_custom( $op, $groupID, $entityID, &$params ) {
  // deletes the values CiviCRM's Birth Date field if someone updates the custom field with a year that is contradictory to the birth date

  foreach ($params as $entity) {
    if ($entity['entity_table'] == 'civicrm_contact') {
      $customBirthYear = civicrm_api3('CustomField', 'get', array(
        'sequential' => 1,
        'name' => "birth_year",
      ));

      // Are we looking at the birth_year field?
      $birthYearField = $customBirthYear['values'][0];
      if (($birthYearField['column_name'] == $entity['column_name'])
        && ($birthYearField['custom_group_id'] == $entity['custom_group_id'])
      ) {
        // birth_year field was written
        // Get value of birth_year field
        $customValues = civicrm_api3('CustomValue', 'get', array(
          'entity_id' => $entity['entity_id'],
        ));
        $birthYear = $customValues['values'][$birthYearField['id']][0];

        // Get contact ID birth date field ($params['entity_id'])
        $contactBirthDate = civicrm_api3('Contact', 'get', array(
          'sequential' => 1,
          'return' => "birth_date",
          'id' => $entity['entity_id'],
        ));
        // Contact birth date to year
        if (!empty($contactBirthDate['values'][0]['birth_date'])) {
          $contactBirthYear = new DateTime($contactBirthDate['values'][0]['birth_date']);
          // Is birth date = birth year? (Match only long format)
          if ($contactBirthYear->format('Y') != $birthYear) {
            //Delete birth_date
            $result = civicrm_api3('Contact', 'create', array(
              'id' => $entity['entity_id'],
              'birth_date' => '',
            ));
          }
        }
      }
    }
  }
}

