<?php

namespace Drupal\os2web_menu_links_paragraph\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\EntityReferenceFieldItemListInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldFormatter\EntityReferenceEntityFormatter;
use Drupal\menu_link_content\Plugin\Menu\MenuLinkContent;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'entity reference rendered entity' formatter.
 *
 * @FieldFormatter(
 *   id = "node_menu_image_view",
 *   label = @Translation("Node menu image view"),
 *   description = @Translation("Display the referenced entity image or menu item referenced node image."),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class NodeMenuImageFormatter extends EntityReferenceEntityFormatter {

  /**
   * The menu link manager.
   *
   * @var \Drupal\Core\Menu\MenuLinkManagerInterface
   */
  protected $menuLinkManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->menuLinkManager = $container->get('plugin.manager.menu.link');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $entity = $items->getEntity();
    if (empty($entity->field_os2web_icon->referencedEntities())) {
      /** @var MenuLinkContent $menuLink*/
      $menuLink = $this->menuLinkManager->createInstance('menu_link_content:' . $entity->uuid());
      $params = $menuLink->getRouteParameters();
      $node = isset($params['node']) ? Node::load($params['node']) : NULL;
      if (!empty($node) && $node->bundle() == 'os2web_page') {
        if ($node->hasField('field_os2web_page_paragraph_bann')
          && $node->field_os2web_page_paragraph_bann instanceof EntityReferenceFieldItemListInterface
          && $bannerParagraph = $node->field_os2web_page_paragraph_bann->referencedEntities()) {
          $paragraph = $bannerParagraph[0];
          $elements[0]['image'] = $paragraph->field_os2web_banner_image->view([
            'label' => 'hidden',
            'type' => 'image',
            'settings' => [
              'image_style' => 'paragraph_background_image_thumb'
            ],
          ]);
        }
        else if ($node->hasField('field_os2web_page_primaryimage')
          && $node->field_os2web_page_primaryimage instanceof EntityReferenceFieldItemListInterface
          && $node->field_os2web_page_primaryimage->referencedEntities()){
          $elements[0]['image'] = $node->field_os2web_page_primaryimage->view([
            'label' => 'hidden',
            'type' => 'image',
            'settings' => [
              'image_style' => 'os2web_normal_thumb'
            ],
          ]);
        }
      }
    } else {
      $elements[0]['#prefix'] = '<div class="menu-icon-wrapper">';
      $elements[0]['#suffix'] = '</div>';
    }
    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    // This formatter is only available for menu_link_content entity of main
    // bundle with available field_os2web_icon as entity reference type.
    return $field_definition->id() == 'menu_link_content.main.field_os2web_icon' && $field_definition->getType() == 'entity_reference';
  }

}
