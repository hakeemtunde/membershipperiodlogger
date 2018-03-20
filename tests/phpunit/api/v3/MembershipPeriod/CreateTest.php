<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * MembershipPeriod.Create API Test Case
 * This is a generic test class implemented with PHPUnit.
 * @group headless
 */
class api_v3_MembershipPeriod_CreateTest extends \PHPUnit_Framework_TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {
  protected $contact;
  protected $membership;
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


  }

  /**
   * The tearDown() method is executed after the test was executed (optional)
   * This can be used for cleanup.
   */
  public function tearDown() {
    parent::tearDown();
  }

  /**
   * test create.
   */
  public function testApiCreate() {

     $membership = \CRM_Core_DAO::createTestObject('CRM_Member_DAO_Membership',
     array(
       'start_date' => '2016-01-21',
       'end_date' => '2017-12-21'
     ));

    $params = array(
      'membership_id' => $membership->id,
      'start_date' => '2016-01-21',
      'end_date' => '2017-12-21',
      'period' => 1,
      'has_payment' => 0
    );

    $result = civicrm_api3('MembershipPeriod', 'Create', $params);
    $this->assertEquals($result['count'], 1);
    $this->assertEquals($result['values'][$result['id']]['membership_id'], $membership->id);
    $this->assertEquals(
      CRM_Utils_Date::mysqlToIso($result['values'][$result['id']]['start_date']), '2016-01-21');

  }

}
