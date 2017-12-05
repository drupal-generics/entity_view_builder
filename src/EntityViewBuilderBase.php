<?php

namespace Drupal\entity_view_builder;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Plugin\PluginBase;

/**
 * Base class for the entity view builder alter plugin.
 *
 * @package Drupal\entity_view_builder
 */
abstract class EntityViewBuilderBase extends PluginBase {

  /**
   * The entity to process.
   *
   * @var \Drupal\Core\Entity\EntityInterface
   */
  protected $entity;

  /**
   * Set the view builder entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity.
   */
  public function setEntity(EntityInterface $entity) {
    $this->entity = $entity;
  }

  /**
   * Get the entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   The entity.
   */
  public function getEntity() {
    return $this->entity;
  }

  /**
   * The build that will be altered.
   *
   * @param array $build
   *   The build array.
   */
  abstract public function build(array &$build);

}
