<?php

namespace Drupal\timesheet;

use Drupal\Core\Url;
use Drupal\timesheet\UtilService;

/**
 * Class DefaultService.
 *
 * @package Drupal\timesheet
 */
class TimesheetTwigExtension extends \Twig_Extension {
/**
   * {@inheritdoc}
   * This function must return the name of the extension. It must be unique.
   */
  public function getName() {
    return 'block_display';
  }

  /**
   * In this function we can declare the extension function
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('getContentPath', array($this, 'getContentPath'), array('is_safe' => array('html')))
    );
  }

  /**
   * The php function to load a given block
   */
  public function getContentPath($nid) {
    $url = Url::fromRoute('entity.node.canonical', ['node' => $nid])->toString();
    $title = \Drupal::service('timesheet.util_service')->getTitleByNid($nid);
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
  }
}