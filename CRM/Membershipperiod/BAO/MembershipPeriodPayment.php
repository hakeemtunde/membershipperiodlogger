<?php
use CRM_Membershipperiod_ExtensionUtil as E;

class CRM_Membershipperiod_BAO_MembershipPeriodPayment extends CRM_Membershipperiod_DAO_MembershipPeriodPayment {

  /**
   * Create a new MembershipPeriodPayment based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Membershipperiod_DAO_MembershipPeriodPayment|NULL
   */
  public static function create($params) {
    $className = 'CRM_Membershipperiod_DAO_MembershipPeriodPayment';
    $entityName = 'MembershipPeriodPayment';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }

  /**
   * Create a new MembershipPeriodPayment based on Membership Payment
   * civicrm_postProcess hook call
   *
   * @param Membership Period $objectRef
   */
  public static function addPeriodPayment($objectRef) {

    $mpp = new CRM_Membershipperiod_DAO_MembershipPeriodPayment();
    $mpp->contribution_id = $objectRef->contribution_id;

    //payment already added
    if($mpp->find(TRUE)) return;

    $mp = new CRM_Membershipperiod_DAO_MembershipPeriod();
    $mp->membership_id = $objectRef->membership_id;
    $mp->orderBy = 'id DESC';
    if($mp->find(TRUE)) {

      //add new payment
      $params = array();
      $params['contribution_id'] = $objectRef->contribution_id;
      $params['membership_period_id'] = $mp->id;
      self::create($params);
      //update payment status
      CRM_Core_DAO::setFieldValue('CRM_Membershipperiod_DAO_MembershipPeriod',
      $mp->id, 'has_payment', 1);

    }


  }

  /**
   * create membership renewal period payment
   *
   * @param $mpid
   * membership period id
   * @param $mid
   * membership id
   */
  public static function addRenewalPayment($mpid,$mid) {
      $params = array();
      //find most recent membership period
      $mp = new CRM_Member_DAO_MembershipPayment();
      $mp->membership_id = $mid;
      $mp->orderBy('contribution_id DESC');

      //add period payment
      if ($mp->find(TRUE)) {
        $params['contribution_id'] = $mp->contribution_id;
        $params['membership_period_id'] = $mpid;
        self::create($params);

      }
  }

  /**
  * Search/fetch for MemebershipPayment record using the params
  *
   * @param $params
   * (reference) key-value array
   *
   * @return API_Success
   */
  public static function getByMembershipPeriodPayment($params) {

    $select = "SELECT * FROM civicrm_membership_period_payment mpp ";

    $where = "";

    if (CRM_Utils_Array::value('id', $params) || CRM_Utils_Array::value('membership_period_id', $params)) {

      $where .="where ";
      $id  = CRM_Utils_Array::value('id', $params);
      $membershipperiodid = CRM_Utils_Array::value('membership_period_id', $params);

      if ($id) {
        $where .= "mpp.id = ".  CRM_Utils_Type::escape($id, 'Integer');
      }

      if ($membershipperiodid) {
        $where .= "mpp.membership_period_id = ". CRM_Utils_Type::escape($membershipperiodid, 'Integer');
      }
    }

      $query = $select . $where;

      $dao = CRM_Core_DAO::executeQuery($query);

      $mpp = array();
      while ($dao->fetch()) {

         $mpp[] = array(
           'id' => $dao->id,
           'membership_period_id' => $dao->membership_period_id,
           'contribution_id' => $dao->contribution_id
         );
      }

      return civicrm_api3_create_success($mpp, $params, 'MembershipPeriodPayment', 'get', $dao);
  }

}
