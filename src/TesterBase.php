<?php
/**
 * @file
 * Contains \Drupal\performance_tester\TesterBase.
 */


namespace Drupal\performance_tester;


use Drupal\Component\Uuid\Php;
use Drupal\Core\Entity\EntityTypeManagerInterface;

abstract class TesterBase implements TesterInterface{

  protected function randomString() {
    $php = new Php();
    return $php->generate();
  }
}
