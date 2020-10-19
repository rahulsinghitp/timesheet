<?php

namespace Drupal\timesheet\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\Xss;

// use Symfony\

/**
 * Defines a route controller for watches autocomplete form elements.
 */
class EmployeeAutoCompleteController extends ControllerBase {

  /**
   * The node storage.
   *
   * @var \Drupal\node\NodeStorage
   */
  protected $nodeStorage;

  /**
   * {@inheritdoc}
   */

  /**
   * {@inheritdoc}
   */

  /**
   * Handler for autocomplete request.
   */
  public function handleAutocomplete(Request $request) {
      $input = $request->query->get('q');
      return \Drupal::service('timesheet.employee_autocomplete')->getEmployeeAutocomplete($input);
  }
}