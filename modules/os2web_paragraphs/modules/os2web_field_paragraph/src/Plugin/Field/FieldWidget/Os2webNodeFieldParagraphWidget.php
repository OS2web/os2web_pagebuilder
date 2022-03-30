<?php

namespace Drupal\os2web_field_paragraph\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\FieldConfigInterface;
use Drupal\node\NodeForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'os2web_node_field_paragraph_widget' widget.
 *
 * @FieldWidget(
 *   id = "os2web_node_field_paragraph_widget",
 *   module = "os2web_files_paragraph",
 *   label = @Translation("Os2web node field paragraph widget"),
 *   field_types = {
 *     "string"
 *   }
 * )
 */
class Os2webNodeFieldParagraphWidget extends WidgetBase {

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
    $instance->entityFieldManager = $container->get('entity_field.manager');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $buildInfo = $form_state->getBuildInfo();
    $options = [
      '' =>  $this->t('None'),
    ];
    if ($buildInfo['callback_object'] instanceof NodeForm) {
      $node = $buildInfo['callback_object']->getEntity();
      $allowed_fields = array_filter($this->entityFieldManager->getFieldDefinitions('node', $node->bundle()), function($field_config) {
        return ($field_config instanceof FieldConfig) && $field_config->getThirdPartySetting('os2web_field_paragraph', 'enabled');
      });
      /** @var FieldConfigInterface $field_config */
      foreach ($allowed_fields as $field_config) {
        $options[$field_config->getName()] = $field_config->label();
      }
    }

    $element['value'] = $element + [
        '#type' => 'select',
        '#options' => $options,
        '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
    ];

    return $element;
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
