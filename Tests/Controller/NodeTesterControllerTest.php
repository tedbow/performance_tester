<?php

/**
 * @file
 * Contains \Drupal\performance_tester\Tests\NodeTesterController.
 */

namespace Drupal\performance_tester\Tests;

use Drupal\simpletest\WebTestBase;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Provides automated tests for the performance_tester module.
 */
class NodeTesterControllerTest extends WebTestBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var Drupal\Core\Entity\EntityTypeManager
   */
  protected $entity_type_manager;
  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "performance_tester NodeTesterController's controller functionality",
      'description' => 'Test Unit for module performance_tester and controller NodeTesterController.',
      'group' => 'Other',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests performance_tester functionality.
   */
  public function testNodeTesterController() {
    // Check that the basic functions of module performance_tester.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via App Console.');
  }

}
