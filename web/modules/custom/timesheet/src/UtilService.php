<?php

namespace Drupal\timesheet;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManager;

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
    public function createTimesheetNode(array $timesheet_data) {
        kint($timesheet_data);
        exit();
    }
}