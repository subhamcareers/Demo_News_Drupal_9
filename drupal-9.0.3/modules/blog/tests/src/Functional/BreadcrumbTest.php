<?php

namespace Drupal\Tests\blog\Functional;

use Drupal\Core\Url;
use Drupal\Tests\block\Traits\BlockCreationTrait;

/**
 * Breadcrumb test for blog module.
 *
 * @group blog
 */
class BreadcrumbTest extends BlogTestBase {
  use BlockCreationTrait;

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
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Add breadcrumb block.
    $this->placeBlock('system_breadcrumb_block', ['region' => 'content', 'id' => 'breadcrumb']);
  }

  /**
   * Blog node type breadcrumb test.
   */
  public function testBlogNodeBreadcrumb() {
    $blog_nid = array_rand($this->blogNodes1);
    $blog_owner = $this->blogNodes1[$blog_nid]->getOwner();
    $this->drupalGet('node/' . $blog_nid);
    $links = $this->getSession()
      ->getPage()
      ->findAll('css', '#block-breadcrumb li a');
    $this->assertEquals(count($links), 3, 'Breadcrumb element number is correctly.');
    [$home, $blogs, $personal_blog] = $links;
    $this->assertTrue(($home->getAttribute('href') === base_path() && $home->getHtml() === 'Home'), 'Home link correctly.');
    $expected_url = Url::fromRoute('view.blog.blog_all')->toString();
    $this->assertTrue(($blogs->getAttribute('href') === $expected_url && $blogs->getHtml() === 'Blogs'), 'Blogs link correctly.');
    $blog_name = $this->container->get('blog.lister')->userBlogTitle($blog_owner);
    $expected_url = Url::fromRoute('view.blog.blog_user_all', ['arg_0' => $blog_owner->id()])->toString();
    $this->assertTrue(($personal_blog->getAttribute('href') === $expected_url && $personal_blog->getHtml() === (string) $blog_name), 'Personal blog link correctly.');
  }

  /**
   * Other node type breadcrumb test.
   */
  public function testOtherNodeBreadcrumb() {
    $article_nid = array_rand($this->articleNodes1);
    $article_owner = $this->articleNodes1[$article_nid]->getOwner();
    $blog_name = $this->container->get('blog.lister')->userBlogTitle($article_owner);
    $this->drupalGet('node/' . $article_nid);
    $links = $this->getSession()
      ->getPage()
      ->findAll('css', '#block-breadcrumb li a');
    $link = array_pop($links);
    $this->assertFalse($link->getHtml() === $blog_name, 'Other node type breadcrumb is correct.');
  }

}
