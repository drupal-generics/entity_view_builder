<?php

namespace Drupal\entity_view_builder;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Discovery\YamlDiscovery;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Class EntityViewBuilderConfigManager.
 *
 * @package Drupal\entity_view_builder
 */
class EntityViewBuilderConfigManager {

  /**
   * Yaml Discovery object.
   *
   * @var \Drupal\Core\Discovery\YamlDiscovery;
   */
  public static $yamlDiscovery;

  /**
   * Object that manages modules.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * Custom cache implementation.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * {@inheritdoc}
   */
  public function __construct(ModuleHandlerInterface $moduleHandler, CacheBackendInterface $cache) {
    $this->moduleHandler = $moduleHandler;
    $this->cache = $cache;
  }

  /**
   * Retrieves all the overrides of view builders from .delegation_config files.
   *
   * @return array
   *   The overrides of view builders from .delegation_config files.
   */
  public function getConfiguration() {

    if(!self::$yamlDiscovery) {
      self::$yamlDiscovery = new YamlDiscovery('delegation_config', $this->moduleHandler->getModuleDirectories());
    }

    $list = self::$yamlDiscovery->findAll();
    $this->sanitizeList($list);
    $cachedList = $this->cache->get('entity_view_builder.list');
    // If the data is not cached, introduce the list in cache.
    if(!$cachedList) {
      // Cache the list of discovered entity view builder overrides.
      $this->cache->set('entity_view_builder.list', $list);

      return $list;
    }
    else {
      return $cachedList->data;
    }

  }

  /**
   * Removes the configurations that don't have the required parameters.
   *
   * @param $list
   * List of Entity View Builders configurations.
   */
  private function sanitizeList(&$list) {
    foreach ($list as &$module) {
      $module = array_filter($module, function (&$element) {
        return isset($element['entity_type_id']) && isset($element['class']) && isset($element['priority']);
      });
    }
  }

}
