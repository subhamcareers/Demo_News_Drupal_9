<?php

namespace Drupal\Tests\blog\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test for empty blogs.
 *
 * @group blog
 */
class EmptyBlogTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'blog',
  ];

  /**
   * @var \Drupal\user\UserInterface
   */
  protected $bloggerNoEntries;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Create blogger user with no blog posts.
    $this->bloggerNoEntries = $this->drupalCreateUser(
          [
            'create blog_post content',
          ]
      );
  }

  /**
   * Test empty blog lists (all blog).
   */
  public function testAllBlogEmptyLists() {
    $this->drupalLogin($this->bloggerNoEntries);
    $this->drupalGet('blog');
    $this->assertText('No blog entries have been created.');
  }

  /**
   * Test empty personal blog.
   */
  public function testEmptyPersonalBlog() {
    $this->drupalLogin($this->bloggerNoEntries);
    $this->drupalGet('blog/' . $this->bloggerNoEntries->id());
    $this->assertText($this->bloggerNoEntries->getDisplayName() . ' has not created any blog entries.');
  }

}
