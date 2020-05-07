<?php

namespace Drupal\os2web_iframe_paragraph\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'iframe_string' formatter.
 *
 * @FieldFormatter(
 *   id = "iframe_string",
 *   label = @Translation("Rendered iframe"),
 *   field_types = {
 *     "string_long",
 *   }
 * )
 */
class IframeStringFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      // The text value has no text format assigned to it, so the user input
      // should equal the output, including newlines.
      $elements[$delta] = [
        '#type' => 'os2web_iframe_markup',
        '#text' => $item->value,
      ];
    }

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    return $field_definition->getName() == 'field_os2web_iframe_code';
  }

}
