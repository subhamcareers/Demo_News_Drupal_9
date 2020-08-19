<?php

namespace Drupal\bootstrap_layout_builder\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Configure Bootstrap Layout Builder settings.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * The entity field manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManagerInterface
   */
  protected $entityFieldManager;

  /**
   * The entity type bundle info.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * Constructs a SettingsForm object.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entity_field_manager
   *   The entity field manager.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle info.
   */
  public function __construct(EntityFieldManagerInterface $entity_field_manager, EntityTypeBundleInfoInterface $entity_type_bundle_info) {
    $this->entityFieldManager = $entity_field_manager;
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_field.manager'),
      $container->get('entity_type.bundle.info')
    );
  }

  /**
   * Config settings.
   *
   * @var string
   */
  const SETTINGS = 'bootstrap_layout_builder.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bootstrap_layout_builder_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);

    $form['hide_section_settings'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide "Advanced Settings"'),
      '#description' => $this->t('<img src="/' . drupal_get_path('module', 'bootstrap_layout_builder') . '/images/drupal-ui/toggle-advanced-settings.png" alt="Toggle Advanced Settings Tab Visibility" title="Toggle Advanced Settings Tab Visibility">'),
      '#default_value' => $config->get('hide_section_settings'),
    ];

    // Background image media bundle.
    $media_bundles = [];
    $media_bundles_info = $this->entityTypeBundleInfo->getBundleInfo('media');
    // Ignore if match any of the following names.
    $disabled_bundles = ['audio', 'audio_file', 'instagram', 'tweet', 'document', 'remote_video'];
    foreach ($media_bundles_info as $key => $bundle) {
      if (!in_array($key, $disabled_bundles)) {
        $media_bundles[$key] = $bundle['label'] . ' (' . $key . ')';
      }
    }

    $form['background_image_bundle'] = [
      '#type' => 'select',
      '#title' => $this->t('Image background media bundle'),
      '#options' => $media_bundles,
      '#description' => $this->t('Image background media entity bundle.'),
      '#default_value' => $config->get('background_image.bundle'),
      '#ajax' => [
        'callback' => [$this, 'getFields'],
        'event' => 'change',
        'method' => 'html',
        'wrapper' => 'media_image_bundle_fields',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ],
    ];

    $form['background_image_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Image background media field'),
      '#options' => $this->getFieldsByBundle($config->get('background_image.bundle')),
      '#description' => $this->t('Image background media entity field.'),
      '#default_value' => $config->get('background_image.field'),
      '#attributes' => ['id' => 'media_image_bundle_fields'],
      '#validated' => TRUE,
    ];

    $form['background_local_video_bundle'] = [
      '#type' => 'select',
      '#title' => $this->t('Local video background media bundle'),
      '#options' => $media_bundles,
      '#description' => $this->t('Background for local video media entity bundle.'),
      '#default_value' => $config->get('background_local_video.bundle'),
      '#ajax' => [
        'callback' => [$this, 'getFields'],
        'event' => 'change',
        'method' => 'html',
        'wrapper' => 'media_local_video_bundle_fields',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ],
    ];

    $form['background_local_video_field'] = [
      '#type' => 'select',
      '#title' => $this->t('Local video background media field'),
      '#options' => $this->getFieldsByBundle($config->get('background_local_video.bundle')),
      '#description' => $this->t('Local video background media entity field.'),
      '#default_value' => $config->get('background_local_video.field'),
      '#attributes' => ['id' => 'media_local_video_bundle_fields'],
      '#validated' => TRUE,
    ];

    $form['style'] = [
      '#type' => 'details',
      '#title' => $this->t('Style'),
      '#open' => TRUE,
    ];

    $form['style']['background_colors'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('background_colors'),
      '#title' => $this->t('Background colors (classes)'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the background.</p>'),
      '#cols' => 60,
      '#rows' => 5,
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldsByBundle($bundle) {
    $field_map = $this->entityFieldManager->getFieldMap();
    $media_field_map = $field_map['media'];
    $fields = [];
    foreach ($media_field_map as $field_name => $field_info) {
      if (
        in_array($bundle, $field_info['bundles']) &&
        in_array($field_info['type'], ['image', 'file']) &&
        $field_name !== 'thumbnail'
      ) {
        $fields[$field_name] = $field_name;
      }
    }
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getFields(array &$element, FormStateInterface $form_state) {
    $triggering_element = $form_state->getTriggeringElement();
    $value = $triggering_element['#value'];
    $wrapper_id = $triggering_element["#ajax"]["wrapper"];
    $rendered_field = '';
    foreach ($this->getFieldsByBundle($value) as $field_name => $field_value) {
      $rendered_field .= '<option value="' . $field_name . '">' . $field_value . '</option>';
    }
    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand('#' . $wrapper_id, $rendered_field));
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->configFactory->getEditable(static::SETTINGS)
      ->set('hide_section_settings', $form_state->getValue('hide_section_settings'))
      ->set('background_image.bundle', $form_state->getValue('background_image_bundle'))
      ->set('background_image.field', $form_state->getValue('background_image_field'))
      ->set('background_local_video.bundle', $form_state->getValue('background_local_video_bundle'))
      ->set('background_local_video.field', $form_state->getValue('background_local_video_field'))
      ->set('background_colors', $form_state->getValue('background_colors'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
