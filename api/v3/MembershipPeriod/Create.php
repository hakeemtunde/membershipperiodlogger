<?php
use CRM_Membershipperiod_ExtensionUtil as E;

/**
 * MembershipPeriod.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 * @return void
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC/API+Architecture+Standards
 */
function _civicrm_api3_membership_period_Create_spec(&$spec) {
  $spec['membership_id']['api.required'] = 1;
  $spec['start_date']['api.required'] = 1;
  $spec['end_date']['api.required'] = 1;

}

/**
 * MembershipPeriod.Create API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_membership_period_create($params) {
  $mp = $returnvalues = array();
  $membershipperiodBAO = CRM_Membershipperiod_BAO_MembershipPeriod::create($params);
  _civicrm_api3_object_to_array($membershipperiodBAO, $mp[$membershipperiodBAO->id]);
  return civicrm_api3_create_success($mp, $params, 'MembershipPeriod', 'create', $membershipperiodBAO);

}
