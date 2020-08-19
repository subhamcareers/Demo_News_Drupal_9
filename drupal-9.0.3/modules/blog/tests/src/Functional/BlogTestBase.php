<?php

namespace Drupal\Tests\blog\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test base class for blog module.
 *
 * @group blog
 */
abstract class BlogTestBase extends BrowserTestBase {

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
   * @var \Drupal\node\NodeInterface[]
   */
  protected $blogNodes1, $blogNodes2, $articleNodes1, $articleNodes2;

  /**
   * @var \Drupal\user\UserInterface
   */
  protected $blogger1, $blogger2, $bloggerNoEntries;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Add article node type.
    $this->createContentType(
          [
            'type' => 'article',
          ]
      );
    // Create blogger1 user.
    $this->blogger1 = $this->drupalCreateUser(
          [
            'create article content',
            'create blog_post content',
          ]
      );
    // Create blogger2 user.
    $this->blogger2 = $this->drupalCreateUser(
          [
            'create article content',
            'create blog_post content',
          ]
      );
    // Create blogger user with no blog posts.
    $this->bloggerNoEntries = $this->drupalCreateUser(
          [
            'create blog_post content',
          ]
      );
    // Generate blog posts and articles.
    $this->blogNodes1 = [];
    $this->blogNodes2 = [];
    $this->articleNodes1 = [];
    $this->articleNodes2 = [];
    for ($i = 0; $i < 10; $i++) {
      $node = $this->createNode(
            [
              'type' => 'blog_post',
              'title' => $this->randomMachineName(32),
              'uid' => ($i % 2) ? $this->blogger1->id() : $this->blogger2->id(),
            ]
        );
      if ($i % 2) {
        $this->blogNodes1[$node->id()] = $node;
      }
      else {
        $this->blogNodes2[$node->id()] = $node;
      }
    }
    for ($i = 0; $i < 10; $i++) {
      $node = $this->createNode(
            [
              'type' => 'article',
              'title' => $this->randomMachineName(32),
              'uid' => ($i % 2) ? $this->blogger1->id() : $this->blogger2->id(),
            ]
        );
      if ($i % 2) {
        $this->articleNodes1[$node->id()] = $node;
      }
      else {
        $this->articleNodes2[$node->id()] = $node;
      }
    }
  }

}
