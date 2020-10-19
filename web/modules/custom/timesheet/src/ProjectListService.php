<?php

namespace Drupal\timesheet;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManager;

/**
 * Our hero article service
 */
class ProjectListService {

    private $entityQuery;
    private $entityManager;

    public function __construct(QueryFactory $entityQuery, EntityManager $entityManager) {
        $this->entityQuery = $entityQuery;
        $this->entityManager = $entityManager;
    }

    /**
     * Article regarding heroes
     */
    public function getProjectList() {
        $projectList = [];
        $projectTids = $this->entityQuery->get('taxonomy_term')->condition('vid', 'projects')->execute();
        $projectTerms = $this->entityManager->getStorage('taxonomy_term')->loadMultiple($projectTids);
        foreach ($projectTerms as $key => $value) {
            $projectList[$value->id()] = $value->getName();
        }
        return $projectList;
    }
}