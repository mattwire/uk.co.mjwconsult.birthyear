<?php

class CRM_BirthYear_Utils {

  protected static $extended_demographics_group = NULL;

  /**
   * Get the ID of the "Extended Demographics" custom group
   *
   * @return int ID
   */
  public static function getExtendedDemographicsGroupID() {
    $extended_demographics_group = self::getExtendedDemographicsGroup();
    if ($extended_demographics_group) {
      return $extended_demographics_group['id'];
    }
    else {
      return 0;
    }
  }

  /**
   * Get the "Extended Demographics" custom group
   *
   * @return array group entity
   */
  public static function getExtendedDemographicsGroup() {
    if (self::$extended_demographics_group == NULL) {
      self::$extended_demographics_group = civicrm_api3('CustomGroup', 'getsingle', ['name' => 'additional_demographics']);
    }
    return self::$extended_demographics_group;
  }

}