<?php

namespace Drupal\Tests\os2web_pagebuilder\Functional;

/**
 * Create a node and test node edit functionality.
 *
 * @group node
 */
class Os2webPageFormTest extends Os2webPageTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A normal logged in user.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $webUser;

  /**
   * A user with permission to bypass content access checks.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorageInterface
   */
  protected $nodeStorage;

  /**
   * Modules to enable.
   *
   * @var string[]
   */
  public static $modules = ['node'];

  protected function setUp() {
    parent::setUp();

    $this->webUser = $this->drupalCreateUser(['edit own page content', 'create page content']);
    $this->adminUser = $this->drupalCreateUser(['bypass node access', 'administer nodes']);
    $this->nodeStorage = $this->container->get('entity_type.manager')->getStorage('node');
  }

  /**
   * Tests node add form.
   */
  public function testNodeAddForm() {
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('node/add/os2web_page');
    $result = $this->xpath('//input[@name="title[0][value]"]');
    $this->assertTrue(!empty($result), 'The Indholdside node has no title field.');

  }

}
