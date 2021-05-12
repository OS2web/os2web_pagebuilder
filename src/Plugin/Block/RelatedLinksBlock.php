<?php

namespace Drupal\os2web_pagebuilder\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\os2web_pagebuilder\Form\SettingsForm;

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
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
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

        $referenceMode = \Drupal::config(SettingsForm::$configName)->get('os2web_related_links_block_reference_mode');
        $relatedTermsIds = [];
        switch ($referenceMode) {
          case 'section_keyword':
            $relatedTermsIds[] = $sectionTermId;
            break;
          case 'sections_parents_keyword':
            // Getting top level parent.
            $ancestors = \Drupal::service('entity_type.manager')->getStorage('taxonomy_term')->loadAllParents($sectionTermId);
            $topParent = array_pop($ancestors);

            // Getting sibling terms ID.
            $siblingTerms = \Drupal::entityTypeManager()
              ->getStorage('taxonomy_term')
              ->loadTree('os2web_sektion', $topParent->id());
            foreach ($siblingTerms as $term) {
              $relatedTermsIds[] = $term->tid;
            }
            break;
        }

        // Finding all nodes related with founnd siblings.
        if (!empty($relatedTermsIds)) {
          $query->condition('field_os2web_page_section', $relatedTermsIds, 'IN');
        }
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
