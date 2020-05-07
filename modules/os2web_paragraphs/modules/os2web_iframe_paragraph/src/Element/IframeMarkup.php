<?php

namespace Drupal\os2web_iframe_paragraph\Element;

use Drupal\Core\Render\Element\RenderElement;
use Drupal\filter\Render\FilteredMarkup;

/**
 * Provides a processed os2web_iframe_markup render element.
 *
 * @RenderElement("os2web_iframe_markup")
 */
class IframeMarkup extends RenderElement {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    return [
      '#text' => '',
      '#pre_render' => [
        [$class, 'preRenderText'],
      ],
    ];
  }

  /**
   * Pre-render callback: Renders a processed os2web_iframe_markup element into #markup.
   *
   * @param array $element
   *   - #text: containing the text to be filtered
   *
   * @return array
   *   The passed-in element with the filtered text in '#markup'.
   *
   * @ingroup sanitization
   */
  public static function preRenderText($element) {
    $text = $element['#text'];

    // Convert all Windows and Mac newlines to a single newline, so filters only
    // need to deal with one possibility.
    $text = str_replace(["\r\n", "\r"], "\n", $text);

    // Allow only iframe tag to render.
    $text = strip_tags($text, '<iframe>');

    // Filtering and sanitizing have been done in
    // \Drupal\filter\Plugin\FilterInterface. $text is not guaranteed to be
    // safe, but it has been passed through the filter system and checked with
    // a text format, so it must be printed as is. (See the note about security
    // in the method documentation above.)
    $element['#markup'] = FilteredMarkup::create($text);
    return $element;
  }

  /**
   * Wraps a logger channel.
   *
   * @param string $channel
   *   The name of the channel.
   *
   * @return \Psr\Log\LoggerInterface
   *   The logger for this channel.
   */
  protected static function logger($channel) {
    return \Drupal::logger($channel);
  }

  /**
   * Wraps the config factory.
   *
   * @return \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected static function configFactory() {
    return \Drupal::configFactory();
  }

}
