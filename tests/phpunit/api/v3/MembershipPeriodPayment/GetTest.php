<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * MembershipPeriodPayment.Get API Test Case
 * This is a generic test class implemented with PHPUnit.
 * @group headless
 */
class api_v3_MembershipPeriodPayment_GetTest extends \PHPUnit_Framework_TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {

  protected $mpp;
  protected $membershipperiod;
  protected $mpps;
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

    $this->membershipperiod = \CRM_Core_DAO::createTestObject('CRM_Membershipperiod_DAO_MembershipPeriod');

    $this->mpps = \CRM_Core_DAO::createTestObject('CRM_Membershipperiod_DAO_MembershipPeriodPayment',null, 2);

    // $this->mpp = \CRM_Core_DAO::createTestObject('CRM_Membershipperiod_DAO_MembershipPeriodPayment');
  }

  /**
   * The tearDown() method is executed after the test was executed (optional)
   * This can be used for cleanup.
   */
  public function tearDown() {
    parent::tearDown();
  }

  /**
   * Simple example test case.
   *
   * Note how the function name begins with the word "test".
   */
  public function testApiGet() {

    //fetch all
    $result = civicrm_api3('MembershipPeriodPayment', 'Get');
    $this->assertEquals(0, $result['is_error']);
    $this->assertTRUE($result['count'] > 0);


    //fetch by membership_period_id
    $params = array('membership_period_id'=> $this->membershipperiod->id);
    $result = civicrm_api3('MembershipPeriodPayment', 'Get', $params);
    $this->assertEquals(0, $result['is_error']);
    $this->assertEquals($this->membershipperiod->id, $result['values'][0]['membership_period_id']);
  }

}
