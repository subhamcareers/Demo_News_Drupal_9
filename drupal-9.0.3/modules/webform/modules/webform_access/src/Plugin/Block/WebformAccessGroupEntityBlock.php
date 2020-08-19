<?php

namespace Drupal\webform_access\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'webform_access_group_entity' block.
 *
 * @Block(
 *   id = "webform_access_group_entity",
 *   admin_label = @Translation("Webform access group entities"),
 *   category = @Translation("Webform access")
 * )
 */
class WebformAccessGroupEntityBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * The webform access group storage.
   *
   * @var \Drupal\webform_access\WebformAccessGroupStorageInterface
   */
  protected $webformAccessGroupStorage;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = new static($configuration, $plugin_id, $plugin_definition);
    $instance->currentUser = $container->get('current_user');
    $instance->webformAccessGroupStorage = $container->get('entity_type.manager')->getStorage('webform_access_group');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    /** @var \Drupal\node\NodeInterface[] $nodes */
    $nodes = $this->webformAccessGroupStorage->getUserEntities($this->currentUser, 'node');
    if (empty($nodes)) {
      return NULL;
    }

    $items = [];
    foreach ($nodes as $node) {
      if ($node->access()) {
        $items[] = $node->toLink()->toRenderable();
      }
    }
    if (empty($items)) {
      return NULL;
    }

    return [
      '#theme' => 'item_list',
      '#items' => $items,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    // @todo Setup cache tags and context .
    return 0;
  }

}
