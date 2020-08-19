<?php

namespace Drupal\blog\Controller;

use Drupal\blog\BlogListerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\user\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller routines for blog.
 */
class BlogController implements ContainerInjectionInterface {

  /**
   * The blog lister.
   *
   * @var \Drupal\blog\BlogListerInterface
   */
  protected $blogLister;

  /**
   * Constructs a BlogController object.
   *
   * @param \Drupal\blog\BlogListerInterface $blogLister
   *   The blog lister.
   */
  final public function __construct(BlogListerInterface $blogLister) {
    $this->blogLister = $blogLister;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
          $container->get('blog.lister')
      );
  }

  /**
   * Returns a title for user blog pages.
   *
   * @param \Drupal\user\UserInterface $user
   *
   * @return string
   *   A title string for a user blog page.
   */
  public function userBlogTitle(UserInterface $user) {
    return $this->blogLister->userBlogTitle($user);
  }

}
