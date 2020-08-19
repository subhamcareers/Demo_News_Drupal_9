<?php

namespace Drupal\insert\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;
use Drupal\image\Entity\ImageStyle;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 */
class RotateController extends ControllerBase {

  /**
   * Rotates an image regenerating image derivatives for every image style and
   * saving the corresponding entity with the updated image dimensions.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function rotate(Request $request) {
    $fid = $request->query->get('fid', NULL);
    $degree = $request->query->get('degree', NULL);
    $nid = $request->query->get('nid', NULL);
    $absolute = \Drupal::config('insert.config')->get('absolute');

    if ($fid === NULL || $degree === NULL || $nid === NULL) {
      return new JsonResponse([]);
    }

    $file = File::load($fid);

    if ($file === NULL) {
      return new JsonResponse([]);
    }

    /** @var \Drupal\Core\Image\ImageInterface $image */
    $image = \Drupal::service('image.factory')->get($file->getFileUri());

    if (!$image->isValid()) {
      return new JsonResponse([]);
    }

    if ($image->rotate(floatval($degree))) {
      $image->save();
    }

    $styleUrls = [
      'image' => $this->convertUrl(file_create_url($image->getSource()), $absolute),
    ];

    /* @var ImageStyle $style */
    foreach (ImageStyle::loadMultiple() as $style) {
      $style->flush($image->getSource());
      $url = $style->buildUrl($image->getSource());
      $styleUrls[$style->getName()] = $this->convertUrl($url, $absolute);
    }

    $revision = NULL;

    // Update dimensions in node.
    $node = Node::load($nid);
    if ($node !== NULL) {

      /** @var \Drupal\Core\Field\FieldDefinitionInterface $definition */
      foreach ($node->getFieldDefinitions() as $field_name => $definition) {
        if ($definition->getType() === 'image') {
          $value = $node->get($field_name)->getValue();
          $found = FALSE;
          foreach ($value as &$item) {
            if ($item['target_id'] === $fid) {
              $width = $item['width'];
              $item['width'] = $item['height'];
              $item['height'] = $width;
              $node->set($field_name, $value, FALSE);
              $node->save();
              $found = TRUE;
              break;
            }
          }
          if ($found) {
            break;
          }
        }
      }

      $revision = $node->getChangedTimeAcrossTranslations();
    }

    return new JsonResponse([
      'revision' => $revision,
      'data' => $styleUrls,
    ]);
  }

  /**
   * @param string $url
   * @param bool $absolute
   * @return string
   */
  protected function convertUrl($url, $absolute) {
    return $absolute || strpos($url, $GLOBALS['base_url']) !== 0
      ? $url
      : base_path() . ltrim(str_replace($GLOBALS['base_url'], '', $url), '/');
  }

}
