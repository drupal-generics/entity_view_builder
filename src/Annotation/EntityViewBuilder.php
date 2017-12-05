<?php

namespace Drupal\entity_view_builder\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Annotation for EntityViewBuilder plugins.
 *
 * @Annotation
 */
class EntityViewBuilder extends Plugin {

  /**
   * The bundle of the entity which to alter.
   *
   * @var string
   */
  public $bundle;

  /**
   * The view mode of the entity to preprocess.
   *
   * @var string
   */
  public $viewMode = NULL;

  /**
   * The priority of this alter.
   *
   * @var int
   */
  public $priority = 1;

}
