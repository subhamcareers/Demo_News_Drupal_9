<?php

namespace Drupal\blog;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;

/**
 * Build blog-specific breadcrumb.
 */
class BlogBreadcrumbBuilder implements BreadcrumbBuilderInterface {

  use StringTranslationTrait;

  /**
   * @var \Drupal\blog\BlogListerInterface
   */
  protected $blogLister;

  /**
   * {@inheritdoc}
   */
  public function __construct(BlogListerInterface $blogLister) {
    $this->blogLister = $blogLister;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    if ($route_match->getRouteName() == 'entity.node.canonical') {
      /**
* @var \Drupal\node\NodeInterface $node
*/
      $node = $route_match->getParameter('node');
      return ($node->bundle() == 'blog_post');
    }
    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    /**
* @var \Drupal\node\NodeInterface $node
*/
    $node = $route_match->getParameter('node');
    $breadcrumb = new Breadcrumb();
    $breadcrumb->addCacheContexts(['route']);
    $links[] = Link::createFromRoute($this->t('Home'), '<front>');
    $links[] = Link::fromTextAndUrl($this->t('Blogs'), Url::fromUri('internal:/blog'));
    $title_text = $this->blogLister->userBlogTitle($node->getOwner());
    $blog_url = Url::fromUri('internal:/blog/' . $node->getOwnerId());
    $links[] = Link::fromTextAndUrl($title_text, $blog_url);
    return $breadcrumb->setLinks($links);
  }

}
