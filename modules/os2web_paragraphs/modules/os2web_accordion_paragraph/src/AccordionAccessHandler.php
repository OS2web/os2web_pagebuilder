<?php

namespace Drupal\os2web_accordion_paragraph;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the OS2Web Accordion entity.
 *
 * @see \Drupal\os2web_accordion_paragraph\Entity\Accordion.
 */
class AccordionAccessHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\os2web_accordion_paragraph\Entity\AccordionInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished os2web accordion entities');
        }

        return AccessResult::allowedIfHasPermission($account, 'view published os2web accordion entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit os2web accordion entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete os2web accordion entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add os2web accordion entities');
  }

}
