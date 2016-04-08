<?php

/**
 * @file
 * Contains \Drupal\performance_tester\NodeTester.
 */

namespace Drupal\performance_tester;

use Drupal\Component\Uuid\Php;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class NodeTesterController.
 *
 * @package Drupal\performance_tester\Controller
 */
class NodeTester extends TesterBase {

  public function getCreateArray($prefix = '') {
    return [
      'title' => $prefix . ':' . $this->randomString(),
      'type' => 'article',
    ];
  }

  public function setEntityUpdate(EntityInterface $entity) {
    $entity->set('title', $entity->label() . ' : updated');
  }



}
