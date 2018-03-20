<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * MembershipPeriod.Get API Test Case
 * This is a generic test class implemented with PHPUnit.
 * @group headless
 */
class api_v3_MembershipPeriod_GetTest extends \PHPUnit_Framework_TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {

  protected $member;
  protected $membershipperiods;
  protected $membershipperiod;
  /**
   * Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
   * See: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
   */
  public function setUpHeadless() {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  /**
   * The setup() method is executed before the test is executed (optional).
   */
  public function setUp() {
    parent::setUp();

    $this->member = \CRM_Core_DAO::createTestObject('CRM_Member_DAO_Membership');

    $this->membershipperiods = \CRM_Core_DAO::createTestObject('CRM_Membershipperiod_DAO_MembershipPeriod',
    ['membership_id' => $this->member->id, 'period'=>1], 3);

    $this->membershipperiod = \CRM_Core_DAO::createTestObject('CRM_Membershipperiod_DAO_MembershipPeriod',
    ['period'=>2]);
  }

  /**
   * Test getAll records
   */
   public function testApiGet() {

     $result = civicrm_api3('MembershipPeriod', 'get');

     $this->assertTrue($result['is_error'] == 0);
     $this->assertEquals(4, $result['count']);
   }

   /**
    * Test fetch record by id
    */
   public function testApiGetById() {
      $param = array('id' => $this->membershipperiods[1]->id);

      $result = civicrm_api3('MembershipPeriod', 'get',  $param);

      $this->assertTrue($result['is_error'] == 0);
      $this->assertEquals($param['id'], $result['values'][0]['id']);
   }

   /**
    * Test fetch record by membership_id
    */
   public function testApiGetByMembershipId() {
      $param = array('membership_id' => $this->membershipperiod->membership_id);
      $result = civicrm_api3('MembershipPeriod', 'get',  $param);

      $this->assertTrue($result['is_error'] == 0);
      $this->assertEquals($param['membership_id'],
        $result['values'][0]['membership_id']);
   }

   /**
    * The tearDown() method is executed after the test was executed (optional)
    * This can be used for cleanup.
    */
   public function tearDown() {
     parent::tearDown();
   }
}
