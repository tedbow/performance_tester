<?php

/**
 * @file
 * Contains \Drupal\performance_tester\Command\RevisionsCommand.
 */

namespace Drupal\performance_tester\Command;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Command\Command;
use Drupal\Console\Style\DrupalStyle;

/**
 * Class RevisionsCommand.
 *
 * @package Drupal\performance_tester
 */
class RevisionsCommand extends Command {

  /**
   * @var EntityStorageInterface
   */
  protected $storage;

  /**
   * @var EntityTypeManagerInterface
   */
  protected $manager;
  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('performance_tester:revisions')
      ->setDescription($this->trans('command.performance_tester.revisions.description'))
      ->addArgument('entity_type', InputArgument::REQUIRED, $this->trans('command.performance_tester.revisions.arguments.name'))
      ->addOption('count', NULL, InputOption::VALUE_OPTIONAL, $this->trans('command.performance_tester.revisions.options.yell'), '10');
  }




  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {


    $io = new DrupalStyle($input, $output);

    $entity_type = $input->getArgument('entity_type');
    /** @var EntityTypeManagerInterface $manager */
    $this->manager = \Drupal::service('entity_type.manager');
    $this->storage = $this->manager->getStorage($entity_type);

    $count = $input->getOption('count');
    $results = $this->createAllRevisions($entity_type, $count);


    $io->info("Revision created form $entity_type $count");
  }

  protected function createAllRevisions($entity_type_id, $count) {

    $query = $this->storage->getQuery();
    $ids = $query->execute();
    if ($entity_type_id != 'user') {
      $entity_type = $this->manager->getDefinition($entity_type_id);
      $label_key = $entity_type->getKey('label');
    }
    else {
      // why doesn't label key work for user?
      $label_key = 'name';
    }

    foreach ($ids as $id) {
      $this->createRevisions($entity_type_id, $id, $count, $label_key);
    }

  }

  private function createRevisions($entity_type_id, $id, $count, $label_key) {
    /** @var ContentEntityInterface $entity */
    $entity = $this->storage->load($id);
    $orig_label = $entity->label();
    if (!($entity_type_id == 'user' && $id == 1)) {
      for ($c = 0; $c < $count; $c++) {
        $label = $orig_label . ':' . $c;
        $entity->set($label_key, $label);

        $entity->setNewRevision();
        $entity->save();
      }
    }
    else {
      for ($c = 0; $c < $count; $c++) {
        // donot change user 1 name or sign in script will not work
        $entity->setNewRevision();
        $entity->save();
      }
    }

  }

}
