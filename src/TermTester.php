<?php
/**
 * @file
 * Contains \Drupal\performance_tester\TermTester.
 */


namespace Drupal\performance_tester;


use Drupal\Component\Uuid\Php;
use Drupal\Core\Entity\EntityInterface;
use Drupal\taxonomy\Entity\Vocabulary;

class TermTester extends TesterBase {

  public function getCreateArray($prefix = '') {

    return [
      'name' => $prefix . ':' . $this->randomString(),
      'vid' => 'tags',
    ];
  }

  public function setEntityUpdate(EntityInterface $entity) {
    $entity->set('name', $entity->label() . ' : updated');
  }


}
