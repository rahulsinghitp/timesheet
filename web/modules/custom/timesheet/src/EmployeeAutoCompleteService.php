<?php

namespace Drupal\timesheet;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Entity\Element\EntityAutocomplete;

/**
 * Our hero article service
 */
class EmployeeAutoCompleteService {

    private $entityQuery;
    private $entityManager;

    public function __construct(QueryFactory $entityQuery, EntityManager $entityManager) {
        $this->entityQuery = $entityQuery;
        $this->entityManager = $entityManager;
    }

    /**
     * Article regarding heroes
     */
    public function getEmployeeAutocomplete($input) {
        $results = [];

        // Get the typed string from the URL, if it exists.
        if (!$input) {
            return new JsonResponse($results);
        }
        $employeeList = [];
        $employeeNids = $this->entityQuery->get('node')
                        ->condition('type', 'employee')
                        ->condition('title', $input, 'CONTAINS')
                        ->groupBy('nid')
                        ->sort('title', 'ASC')
                        ->range(0, 10)
                        ->execute();
        $employeeNodes = $this->entityManager->getStorage('node')->loadMultiple($employeeNids);
        foreach ($employeeNodes as $key => $node) {
            switch ($node->isPublished()) {
                case TRUE:
                  $availability = '✅';
                  break;

                case FALSE:
                default:
                  $availability = '🚫';
                  break;
            }
            $label = [
                $node->getTitle(),
                '<small>(' . $node->id() . ')</small>',
                $availability,
            ];
            $results[] = [
                'value' => EntityAutocomplete::getEntityLabels([$node]),
                'label' => implode(' ', $label),
            ];
        }
        return new JsonResponse($results);
    }
}