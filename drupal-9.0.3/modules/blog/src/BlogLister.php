<?php

namespace Drupal\blog;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\user\UserInterface;

/**
 * Defines a blog lister.
 */
class BlogLister implements BlogListerInterface {

  /**
   * Config Factory Service Object.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The current user account.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * Constructs a BlogLister object.
   */
  public function __construct(AccountInterface $account, ConfigFactoryInterface $config_factory) {
    $this->account = $account;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   *
   * @param \Drupal\user\UserInterface $user
   *   User object.
   *
   * @return string
   *   Title string
   */
  public function userBlogTitle(UserInterface $user) {
    return new TranslatableMarkup("@username's blog", ['@username' => $user->getDisplayName()]);
  }

}
