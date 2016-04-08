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
    $manager = \Drupal::service('entity_type.manager');
    $this->storage = $manager->getStorage($entity_type);

    $count = $input->getOption('count');
    $results = $this->createAllRevisions($entity_type, $count);


    $io->info("Revision created form $entity_type $count");
  }

  protected function createAllRevisions($entity_type, $count) {

    $query = $this->storage->getQuery();
    $ids = $query->execute();
    foreach ($ids as $id) {
      $this->createRevisions($entity_type, $id, $count);
    }

  }

  private function createRevisions($entity_type, $id, $count) {
    /** @var ContentEntityInterface $entity */
    $entity = $this->storage->load($id);
   for ($c = 0; $c < $count; $c++) {
     $label = $entity->label();
     $parts = explode(':', $label);
     $label = $parts[0] . ':' . $c;
     if ($entity_type == 'user') {
       $label_key = 'name';
     }
     else {
       $label_key = $entity->getEntityType()->getKey('label');
     }
     $entity->set($label_key, $label);
     $entity->save();
   }

  }

}
