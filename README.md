#Entity View Builder

##Description

The Entity View Builder module provides plugin type for extending entity view builder per entity.

##Requirements
No additional modules are required.

##Installation
Install as you would normally install a contributed Drupal module.

##Configuration

If you need support for other entity types, other than node and paragraph you must do the following:
* Add the custom view builder handler for necessary entity types in entity_view_builder.module file (see node and paragraph examples).
* Create an entity view replacement, that delegates the primary methods to alter plugins, under the namespace Drupal\entity_view_builder\ViewBuilder (see node and paragraph examples).
* In your module, under the namespace of Drupal\your_module\Plugin\ViewBuilder, create a plugin implementation of @EntityViewBuilder according to the annotations (Drupal\entity_view_builder\Annotation\EntityViewBuilder.php).The respective class must extend Drupal\entity_view_builder\EntityViewBuilderBase. In the build function alter the $build array as needed.
