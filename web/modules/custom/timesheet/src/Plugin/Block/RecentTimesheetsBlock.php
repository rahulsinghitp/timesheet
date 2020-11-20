<?php

namespace Drupal\timesheet\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\timesheet\UtilService;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Recent Timesheet' Block.
 *
 * @Block(
 *   id = "recent_timesheet_block",
 *   admin_label = @Translation("Recent Timesheet block"),
 *   category = @Translation("Recent Timesheet"),
 * )
 */
class RecentTimesheetsBlock extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * The node storage.
     *
     * @var \Drupal\node\NodeStorage
     */
    protected $utilService;

    /**
     * {@inheritdoc}
     */
    function __construct(array $configuration, $plugin_id, $plugin_definition, UtilService $util_service) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->utilService = $util_service;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
        // Load the service required to construct this class.
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('timesheet.util_service')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function build() {
        $current_user = \Drupal::currentUser();
        $uid = $current_user->id();
        $employee_nid = $this->utilService->getEmployeeNidByUid($uid);
        $employee_title = $this->utilService->getTitleByNid($employee_nid);
        $timesheet_content = $this->utilService->getEmployeeTimesheet($employee_nid, 3);
        $data = [
            '#theme' => 'recent_timesheet_content',
            '#employee_title' => $employee_title,
            '#timesheet_content' => $timesheet_content,
        ];
        return [
            '#type' => 'markup',
            '#markup' => \Drupal::service('renderer')->renderPlain($data),
            '#cache' => [
                'disabled' => true,
            ],
        ];
    }
}