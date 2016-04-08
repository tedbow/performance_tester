<?php
/**
 * @file
 * Contains \Drupal\performance_tester\Controller\TesterController.
 */


namespace Drupal\performance_tester\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityInterface;
use Drupal\performance_tester\NodeTester;
use Drupal\performance_tester\TermTester;
use Drupal\performance_tester\TesterInterface;
use Drupal\performance_tester\UserTester;

class TesterController extends ControllerBase{

  protected $entity_type;

  /**
   * @var TesterInterface $tester;
   */
  protected $tester;


  /**
   * Generate.
   *
   * @return string
   *   Return Hello string.
   */
  public function generate($entity_type, $count, $prefix = NULL) {
    $this->setEntityType($entity_type);
    $storage = $this->getStorage();
    for ($i = 0; $i < $count; $i++) {
      $entity =$storage->create($this->tester->getCreateArray($prefix));
      $entity->save();
    }

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Created @count @type(s)', [
        '@count' => $count,
        '@type' => $this->entity_type,
      ]),
    ];
  }

  /**
   * View.
   *
   * @return string
   *   Return Hello string.
   */
  public function view($entity_type, $count) {
    $this->setEntityType($entity_type);
    $entities = $this->loadEntities($count);
    $view_builder = $this->entityTypeManager()->getViewBuilder($this->entity_type);
    $rendered = [];
    foreach ($entities as $entity) {
      $rendered[$entity->id()] = $view_builder->view($entity);
    }
    return $rendered;
  }

  protected function getIds($count) {
    $query = $this->getStorage()->getQuery();
    $query->range(0, $count);
    return $query->execute();
  }
  protected function getStorage() {
    return $this->entityTypeManager()->getStorage($this->entity_type);
  }

  /**
   * Read.
   *
   * @return string
   *   Return Hello string.
   */
  public function read($entity_type, $count) {
    $this->setEntityType($entity_type);
    $entities = $this->loadEntities($count);
    $items = [];
    foreach ($entities as $entity) {
      $items[$entity->id()] = $entity->label();
    }

    return [
      '#theme' => 'item_list',
      '#items' => $items,
    ];

  }

  public function update($entity_type, $count) {
    $this->setEntityType($entity_type);
    $entities = $this->loadEntities($count);
    foreach ($entities as $entity) {
      $this->tester->setEntityUpdate($entity);
      $entity->save();
    }
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Updated @count @type(s)', [
        '@count' => $count,
        '@type' => $this->entity_type,
      ]),
    ];

  }

  /**
   * @param $count
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   */
  protected function loadEntities($count) {
    $ids = $this->getIds($count);
    $entities = $this->getStorage()->loadMultiple($ids);
    return $entities;
  }

  protected function setEntityUpdate(EntityInterface $entity) {
  }

  private function setEntityType($entity_type) {
    $this->entity_type = $entity_type;
    switch ($entity_type) {
      case 'node':
        $this->tester = new NodeTester();
        break;
      case 'taxonomy_term':
        $this->tester = new TermTester();
        break;
      case 'user':
        $this->tester = new UserTester();
        break;
    }
  }

}
