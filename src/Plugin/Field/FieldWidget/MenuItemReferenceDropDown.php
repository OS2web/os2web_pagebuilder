<?php

namespace Drupal\os2web_pagebuilder\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Menu\MenuParentFormSelectorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'os2web_menu_item_reference_drop_down' widget.
 *
 * @FieldWidget(
 *   id = "os2web_menu_item_reference_drop_down",
 *   module = "os2web_pagebuilder",
 *   label = @Translation("Menu item reference drop down"),
 *   field_types = {
 *     "os2web_menu_item_reference_field"
 *   }
 * )
 */
class MenuItemReferenceDropDown extends WidgetBase {

  /**
   * The menu parent form selector service..
   *
   * @var \Drupal\Core\Menu\MenuParentFormSelector
   */
  protected $menuParentFormSelector;

  /**
   * Constructs a WidgetBase object.
   *
   * @param string $plugin_id
   *   The plugin_id for the widget.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the widget is associated.
   * @param array $settings
   *   The widget settings.
   * @param array $third_party_settings
   *   Any third party settings.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, MenuParentFormSelectorInterface $menu_parent_form_selector) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);

    $this->menuParentFormSelector = $menu_parent_form_selector;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('menu.parent_form_selector')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => 2,
      'placeholder' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $default_value = 'main:';
    if (isset($items[$delta]->value)) {
      $default_value = $items[$delta]->value;
    }
    $selector = $this->menuParentFormSelector->parentSelectElement($default_value, '', ['main' => 'Main navigation']);
    $element['value'] = $element + $selector;

    return $element;
  }

}
