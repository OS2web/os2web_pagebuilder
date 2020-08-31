<?php

namespace Drupal\os2web_pagebuilder\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;

/**
 * Provides a 'OS2Web Related links' block.
 *
 * @Block(
 *   id = "os2web_pagebuilder_related_links",
 *   admin_label = @Translation("OS2Web Related links")
 * )
 */
class RelatedLinksBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block = NULL;

    /** @var \Drupal\node\NodeInterface $node */
    $node = \Drupal::routeMatch()->getParameter('node');

    if ($node && $node->bundle() == 'os2web_page') {
      if (!$node->field_os2web_page_related_hide->value) {
        $nodes = $this->getRelatedNodes($node);

        if (!empty($nodes)) {
          $block = [
            '#markup' => $this->getMarkup($nodes),
          ];
        }
      }
    }

    return $block;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * Gets a list of related nodes connected by the same Keyword and Section.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Original node.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Array of related nodes.
   *
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  private function getRelatedNodes(NodeInterface $node) {
    if ($fieldKeyword = $node->get('field_os2web_page_keyword')->getValue()) {
      $keywordTermIds = array_column($fieldKeyword, 'target_id');

      $query = \Drupal::entityQuery('node')
        ->condition('status', 1)
        ->condition('type', 'os2web_page')
        ->condition('field_os2web_page_keyword', $keywordTermIds, 'IN')
        ->condition('nid', $node->id(), '!=')
        ->sort('created', 'ASC')
        ->range(0, 5);

      if ($fieldSection = $node->get('field_os2web_page_section')->first()) {
        $sectionTermId = $fieldSection->getValue()['target_id'];
        $query->condition('field_os2web_page_section', $sectionTermId);
      }

      $nids = $query->execute();

      if ($nids) {
        return Node::loadMultiple($nids);
      }
    }

    return [];
  }

  /**
   * Make block links markup.
   *
   * @param array $related_nodes
   *   Array of nodes.
   *
   * @return string
   *   Rendered HTML.
   *
   * @throws \Drupal\Core\Entity\EntityMalformedException
   */
  private function getMarkup(array $related_nodes) {
    $output = '<ul class="related-links">';

    /** @var \Drupal\node\NodeInterface $node */
    foreach ($related_nodes as $node) {
      $output .= '<li>';
      $output .= $node->toLink()->toString();
      $output .= '</li>';
    }
    $output .= '</ul>';

    return $output;
  }

}
