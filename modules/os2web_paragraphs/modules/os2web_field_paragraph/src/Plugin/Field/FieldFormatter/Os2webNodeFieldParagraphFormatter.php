<?php

namespace Drupal\os2web_field_paragraph\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\FieldConfigInterface;
use Drupal\node\NodeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'os2web_node_field_paragraph_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "os2web_node_field_paragraph_formatter",
 *   label = @Translation("Os2web node field paragraph formatter"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class Os2webNodeFieldParagraphFormatter extends FormatterBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->entityTypeManager = $container->get('entity_type.manager');
    $instance->entityFieldManager = $container->get('entity_field.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    return [
      // Implement settings form.
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $node = $items->getParent()->getEntity();
    if ($node->getEntityTypeId() == 'paragraph') {
      $node = $node->getParentEntity();
    }

    if (!($node instanceof NodeInterface)) {
      return $elements;
    }

    $viewBuilder = $this->entityTypeManager->getViewBuilder('node');
    foreach ($items as $delta => $item) {
      if (!$node->hasField($item->value)) {
        continue;
      }
      /** @var FieldConfigInterface $fieldDefinition */
      $fieldDefinition = $node->get($item->value)->getFieldDefinition();
      if (!$fieldDefinition->getThirdPartySetting('os2web_field_paragraph', 'enabled')) {
        continue;
      }

      $elements[$delta] = $viewBuilder->viewField($node->get($item->value), 'full');
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    // This format is supposed to be used only for configurable fields;
    if (! ($field_definition instanceof FieldConfig)) {
      return FALSE;
    }

    // This formatter is only available for field_os2web_field_field_name field.
    return $field_definition->getName() == 'field_os2web_field_field_name';
  }

}
