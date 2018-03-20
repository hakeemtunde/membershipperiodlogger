<?php
use CRM_Membershipperiod_ExtensionUtil as E;

class CRM_Membershipperiod_BAO_MembershipPeriod extends CRM_Membershipperiod_DAO_MembershipPeriod {

  /**
   * Create a new MembershipPeriod based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Membershipperiod_DAO_MembershipPeriod|NULL
   */
  public static function create($params) {
    $className = 'CRM_Membershipperiod_DAO_MembershipPeriod';
    $entityName = 'MembershipPeriod';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Create membership period base on hook_civicrm_post (Membership:create)
   *
   * @param Membership $objectRef
   *   (reference ) membership object reference.
   */
  public static function createMembershipPeriod($objectRef) {
    $params1 = array();
    $params1['membership_id'] = $objectRef->id;
    $params1['start_date'] = $objectRef->start_date;
    $params1['end_date'] = $objectRef->end_date;
    $params1['period'] = 1;
    $params1['has_payment'] = 0;
    $membershipperiod = self::create($params1);
  }

  /**
   * Create new membership period base on
   * hook_civicrm_postProcess(CRM_Member_Form_MembershipRenewal)
   *
   * @param array $params key-value pairs
   */
  public static function renewalUpdate($params) {

      $ispaymentrecorded = $params['record_contribution'] == 1 ? true : false;
      //fetch current membership info
      $mid = CRM_Utils_Array::value('membership_id', $params);
      $member = new CRM_Member_DAO_Membership();
      $params1 = self::prepareMembershipParam($mid);
      $params1['has_payment'] = $ispaymentrecorded?1:0;

      $mpcurrent = new CRM_Membershipperiod_DAO_MembershipPeriod();
      $mpcurrent->membership_id = $mid;
      $mpcurrent->orderBy('end_date DESC');

      //add new period
      if($mpcurrent->find(TRUE)) {
          //increament period by 1
          $periodcount = $mpcurrent->period + 1;
          $params1['period'] = $periodcount;
      } else {
        //membership has existed before installing plugin
        //assume this is second period moving forward.
        //although we dont keep the previous record
        $params1['period'] = 2;

      }

      $mpcurrent = self::create($params1);

      //if payment was recorded store it
      //might be overkill. since we have the membership payment log
      //but is to keep track of periodic payment status
      if ($ispaymentrecorded) {
        CRM_Membershipperiod_BAO_MembershipPeriodPayment::addRenewalPayment($mpcurrent->id, $mid);
      }

    }

    /**
     * Searc/Fetch Membership Period base on parameters that was passed
     *
     * It extract searhing criteria from $params associative array
     *
     * @param array $param
     *   (reference ) associative key/value
     *
     * @return API_Success
     */
    public static function getMembershipPeriods($params) {
      $select = "SELECT * FROM civicrm_membership_period mp ";

      $where = "";

      if (CRM_Utils_Array::value('id', $params) || CRM_Utils_Array::value('membership_id', $params)) {

        $where .="where ";

        if (CRM_Utils_Array::value('id', $params)) {
          $where .= "mp.id = ". CRM_Utils_Array::value('id', $params);
        }

        if (CRM_Utils_Array::value('membership_id', $params)) {
          $where .= "mp.membership_id = ". CRM_Utils_Array::value('membership_id', $params);
        }

      }

     // orderBy
     //  $orderBy = "ORDER BY period DESC";
      $query = $select . $where;
      $dao = CRM_Core_DAO::executeQuery($query);

      $mp = array();
      while ($dao->fetch()) {

         $mp[] = array(
           'id' => $dao->id,
           'membership_id' => $dao->membership_id,
           'start_date' => $dao->start_date,
           'end_date' => $dao->end_date,
           'period' => $dao->period,
           'has_payment' => $dao->has_payment
         );

      }

     return civicrm_api3_create_success($mp, $params, 'MembershipPeriod', 'get', $dao);
    }

    /**
     * Prepare Membership Period object
     *
     * @param int $mid
     * @return array
     */
    private static function prepareMembershipParam($mid) {
      $member = new CRM_Member_DAO_Membership();
      $member->id = $mid;
      $member->find(TRUE);
      $current_start_date = $member->start_date;
      $current_end_date = $member->end_date;

      //initia parameters
      $params['membership_id'] = $mid;
      $params['start_date'] = $current_start_date;
      $params['end_date'] = $current_end_date;
      $params['has_payment'] = 0;

      return $params;
    }

}
