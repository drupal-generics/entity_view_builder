<?php

namespace Drupal\entity_view_builder\ViewBuilder;

use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Theme\Registry;
use Drupal\paragraphs\ParagraphInterface;
use Drupal\paragraphs\ParagraphViewBuilder as OriginalViewBuilder;
use Drupal\entity_view_builder\EntityViewBuilderManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Node form replacement that delegates the primary methods to alter plugins.
 *
 * @package Drupal\entity_view_builder\Form
 */
class ParagraphViewBuilder extends OriginalViewBuilder {

  /**
   * The entity view builder plugin manager to get the alter implementations.
   *
   * @var \Drupal\entity_view_builder\EntityViewBuilderManager
   */
  protected $entityViewBuilderManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityTypeInterface $entity_type, EntityManagerInterface $entity_manager, LanguageManagerInterface $language_manager, Registry $theme_registry = NULL, EntityViewBuilderManager $builderDelegateManager) {
    parent::__construct($entity_type, $entity_manager, $language_manager, $theme_registry);
    $this->entityViewBuilderManager = $builderDelegateManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity.manager'),
      $container->get('language_manager'),
      $container->get('theme.registry'),
      $container->get('plugin.manager.entity_view_builder')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build) {
    $build = parent::build($build);
    $this->delegateViewDisplayMethod('build', $build);
    return $build;
  }

  /**
   * Calls method on all entity view builder alter plugins.
   *
   * @param string $method
   *   One of: build, more could be added.
   * @param array $build
   *   The render array.
   */
  protected function delegateViewDisplayMethod($method, array &$build) {
    if (isset($build['#paragraph']) && $build['#paragraph'] instanceof ParagraphInterface) {
      $paragraph = $build['#paragraph'];
      $view_mode = $build['#view_mode'];
      $view_builder_plugins = $this->entityViewBuilderManager->getAlters($paragraph->bundle(), $view_mode);

      foreach ($view_builder_plugins as $view_builder_plugin) {
        $view_builder_plugin->setEntity($paragraph);
        $view_builder_plugin->$method($build);
      }
    }
  }

}
