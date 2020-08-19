<?php

namespace Drupal\blog\Plugin\Menu;

use Drupal\Core\Menu\MenuLinkDefault;
use Drupal\Core\Menu\StaticMenuLinkOverridesInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Represents a menu link for "My blog".
 */
class MyBlogLink extends MenuLinkDefault {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  final public function __construct(array $configuration, $plugin_id, $plugin_definition, StaticMenuLinkOverridesInterface $static_override, AccountInterface $current_user) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $static_override);
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
          $configuration,
          $plugin_id,
          $plugin_definition,
          $container->get('menu_link.static.overrides'),
          $container->get('current_user')
      );
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheContexts() {
    return ['user'];
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteName() {
    if ($this->currentUser->isAuthenticated()) {
      return 'view.blog.blog_user_all';
    }
    else {
      return '';
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getRouteParameters() {
    if ($this->currentUser->isAuthenticated()) {
      $uid = $this->currentUser->id();
      return ['arg_0' => $uid];
    }
    return ['arg_0' => 0];
  }

}
