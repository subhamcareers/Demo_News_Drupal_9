<?php

namespace Drupal\Tests\blog\Functional;

use Drupal\Tests\block\Functional\AssertBlockAppearsTrait;

/**
 * Test blog functionality.
 *
 * @group blog
 */
class BasicBlogTest extends BlogTestBase {
  use AssertBlockAppearsTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'block',
    'blog',
  ];

  /**
   * @var \Drupal\user\UserInterface
   */
  protected $regularUser;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Create regular user.
    $this->regularUser = $this->drupalCreateUser(['create article content']);
  }

  /**
   * Test personal blog title.
   */
  public function testPersonalBlogTitle() {
    $this->drupalLogin($this->regularUser);
    $this->drupalGet('blog/' . $this->blogger1->id());
    $this->assertResponse(200);
    $this->assertTitle($this->blogger1->getDisplayName() . "'s blog | Drupal");
  }

  /**
   * View the blog of a user with no blog entries as another user.
   */
  public function testBlogPageNoEntries() {
    $this->drupalLogin($this->regularUser);
    $this->drupalGet('blog/' . $this->bloggerNoEntries->id());
    $this->assertResponse(200);
    $this->assertTitle($this->bloggerNoEntries->getDisplayName() . "'s blog | Drupal");
    $this->assertText($this->bloggerNoEntries->getDisplayName() . ' has not created any blog entries.');
  }

  /**
   * View blog block.
   */
  public function testBlogBlock() {
    // Place the recent blog posts block.
    $blog_block = $this->drupalPlaceBlock('blog_blockblock-views-block-blog-blog-block');
    // Verify the blog block was displayed.
    $this->drupalGet('<front>');
    $this->assertBlockAppears($blog_block);
  }

}
