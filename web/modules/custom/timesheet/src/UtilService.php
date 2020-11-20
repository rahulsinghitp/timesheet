<?php

namespace Drupal\timesheet;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManager;
use Drupal\node\Entity\Node;

/**
 * Our hero article service
 */
class UtilService {

    private $entityQuery;
    private $entityManager;

    public function __construct(QueryFactory $entityQuery, EntityManager $entityManager) {
        $this->entityQuery = $entityQuery;
        $this->entityManager = $entityManager;
    }

    /**
     * Article regarding heroes
     */
    public function getEmployeeNidByUid(int $user_id) {
        if (empty($user_id)) {
            return '';
        }

        $query = $this->entityQuery->get('node')
                ->condition('status', NODE_PUBLISHED)
                ->condition('type', 'employee')
                ->condition('field_user', $user_id);
        $employee_id = $query->execute();

        return current($employee_id);
    }

    /**
     * 
     */
    public function getTitleByNid(int $nid) {
        $node_storage = $this->entityManager->getStorage('node');
        $node = $node_storage->load($nid);
        return $node->get('title')->value;
    }

    /**
     * 
     */
    public function createTimesheetNode(array $timesheet_data, $nid = NULL) {
        $taxonomy_storage = $this->entityManager->getStorage('taxonomy_term');
        $project_term = $taxonomy_storage->load($timesheet_data['project_tid']);
        $timesheet_node_data = [
            'type' => 'timesheet',
            'title' => $timesheet_data['employee_name'] . ' | ' . $project_term->getName() . ' | ' . $timesheet_data['duration'] . ' hrs | ' . $timesheet_data['timesheet_date'],
            'field_duration' => [$timesheet_data['duration']],
            'field_employee' => ['target_id' => $timesheet_data['employee']],
            'field_timesheet_date' => [$timesheet_data['timesheet_date']],
            'field_project' => $timesheet_data['project_tid'],
            'field_project_description' => ['value' => $timesheet_data['description']]
        ];

        if (empty($nid)) {
            $timesheet_node = Node::create($timesheet_node_data);
            $timesheet_node->save();
        }
        else {
            $node_storage = $this->entityManager->getStorage('node');
            $timesheet_node = $node_storage->load($nid);
            $timesheet_node->set('title', $timesheet_data['employee_name'] . ' | ' . $project_term->getName() . ' | ' . $timesheet_data['duration'] . ' hrs | ' . $timesheet_data['timesheet_date']);
            $timesheet_node->set('field_duration', $timesheet_data['duration']);
            $timesheet_node->set('field_timesheet_date', $timesheet_data['timesheet_date']);
            $timesheet_node->set('field_project', $timesheet_data['project_tid']);
            $timesheet_node->set('field_project_description', $timesheet_data['description']);
            $timesheet_node->set('field_employee', $timesheet_data['employee']);
            $timesheet_node->save();
        }

        return $timesheet_node;
    }

    /**
     * 
     */
    public function getEmployeeTimesheet(int $employee_nid, int $count = 5) {
        $query = $this->entityQuery->get('node')
                ->condition('status', NODE_PUBLISHED)
                ->condition('type', 'timesheet')
                ->condition('field_employee', $employee_nid)
                ->range(0, $count)
                ->sort('field_timesheet_date', DESC);
        $timesheet_nids = $query->execute();
        return $timesheet_nids;
    }
}