<?php

namespace Drupal\entity_view_builder;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Plugin manager for the node view alter plugins.
 *
 * @package Drupal\entity_view_builder
 */
class EntityViewBuilderManager extends DefaultPluginManager {

  /**
   * {@inheritdoc}
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/ViewBuilder', $namespaces, $module_handler, 'Drupal\entity_view_builder\EntityViewBuilderBase', 'Drupal\entity_view_builder\Annotation\EntityViewBuilder');
    $this->setCacheBackend($cache_backend, 'entity_view_builder_delegates');
  }

  /**
   * Get view builder alters for the provided bundle.
   *
   * @param string $bundle
   *   The content type of the node.
   * @param string $view_mode
   *   The view mode of the entity.
   *
   * @return \Drupal\entity_view_builder\EntityViewBuilderBase[]
   *   Form alters for the given bundle.
   */
  public function getAlters($bundle, $view_mode) {
    $bundleFormAlters = [];

    // Get the alter definitions for the given bundle.
    foreach ($this->getDefinitions() as $id => $definition) {

      if (empty($definition['viewMode']) || $definition['viewMode'] == $view_mode || is_array($definition['viewMode']) && in_array($view_mode, $definition['viewMode'])) {
        if (is_array($definition['bundle']) && in_array($bundle, $definition['bundle']) || $definition['bundle'] == $bundle) {
          $bundleFormAlters[$id] = $definition;
        }
      }
    }

    // Sort the definitions after priority.
    uasort($bundleFormAlters, function ($a, $b) {
      if ($a['priority'] == $b['priority']) {
        return 0;
      }

      return ($a['priority'] < $b['priority']) ? -1 : 1;
    });

    // Create the alter plugins.
    foreach ($bundleFormAlters as $id => &$alter) {
      $alter = $this->createInstance($id);
    }

    return $bundleFormAlters;
  }

}
