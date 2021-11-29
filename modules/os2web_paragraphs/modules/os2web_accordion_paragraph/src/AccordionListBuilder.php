<?php

namespace Drupal\os2web_accordion_paragraph;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of OS2Web Accordion entities.
 *
 * @ingroup os2web_accordion_paragraph
 */
class AccordionListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('OS2Web Accordion ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\os2web_spotbox\Entity\ReusableAccordion $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.os2web_accordion.edit_form',
      ['os2web_accordion' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
