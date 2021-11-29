<?php

namespace Drupal\os2web_accordion_paragraph\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining OS2Web Accordion entities.
 *
 * @ingroup os2web_accordion
 */
interface AccordionInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the OS2Web Accordion name.
   *
   * @return string
   *   Name of the OS2Web Accordion.
   */
  public function getName();

  /**
   * Sets the OS2Web Accordion name.
   *
   * @param string $name
   *   The OS2Web Accordion name.
   *
   * @return \Drupal\os2web_accordion\Entity\AccordionInterface
   *   The called OS2Web Accordion entity.
   */
  public function setName($name);

  /**
   * Gets the OS2Web Accordion creation timestamp.
   *
   * @return int
   *   Creation timestamp of the OS2Web Accordion.
   */
  public function getCreatedTime();

  /**
   * Sets the OS2Web Accordion creation timestamp.
   *
   * @param int $timestamp
   *   The OS2Web Accordion creation timestamp.
   *
   * @return \Drupal\os2web_accordion\Entity\AccordionInterface
   *   The called OS2Web Accordion entity.
   */
  public function setCreatedTime($timestamp);

}
