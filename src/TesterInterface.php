<?php
/**
 * Author: Ted Bowman
 * Date: 3/21/16
 * Time: 8:50 AM
 */

namespace Drupal\performance_tester;


use Drupal\Core\Entity\EntityInterface;

interface TesterInterface {

  public function getCreateArray($prefix = '');

  public function setEntityUpdate(EntityInterface $entity);
}
