<?php
/**
 * @file
 * Contains \Drupal\performance_tester\UserTester.
 */


namespace Drupal\performance_tester;


use Drupal\Core\Entity\EntityInterface;

class UserTester extends TesterBase{
  public function getCreateArray($prefix = '') {
    return [
      'name' => $prefix . ':' . $this->randomString(),
      'status' => 1,
    ];
  }

  public function setEntityUpdate(EntityInterface $entity) {
    $entity->set('name', $entity->label() . ' : updated');
  }
}
