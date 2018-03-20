<?php
use CRM_Membershipperiod_ExtensionUtil as E;



/**
 * MembershipPeriodPayment.Get API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_membership_period_payment_Get($params) {

  return CRM_Membershipperiod_BAO_MembershipPeriodPayment::getByMembershipPeriodPayment($params);

}
