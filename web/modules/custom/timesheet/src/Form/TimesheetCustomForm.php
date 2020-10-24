<?php

namespace Drupal\timesheet\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\timesheet\UtilService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Element\EntityAutocomplete;

/**
 * Custom Form for creating Timesheet entry
 */
class TimesheetCustomForm extends FormBase {

    /**
     * The node storage.
     *
     * @var \Drupal\node\NodeStorage
     */
    protected $utilService;
    
    /**
     * {@inheritdoc}
     */
    function __construct(UtilService $util_service) {
        $this->utilService = $util_service;
    }

    public static function create(ContainerInterface $container) {
        return new static(
        // Load the service required to construct this class.
          $container->get('timesheet.util_service')
        );
    }


    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return "timesheet_entry_form";
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $current_user = \Drupal::currentUser();
        $roles = $current_user->getRoles();
        $uid = $current_user->id();
        $employee_nid = $this->utilService->getEmployeeNidByUid($uid);
        $employee_title = $this->utilService->getTitleByNid($employee_nid);
        $employee_default_value = $employee_title . ' (' . $employee_nid . ')';
        $form['timesheet_date'] = [
            '#type' => 'date',
            '#title' => $this->t('Timesheet Date'),
            '#description' => $this->t('Timesheet Date'),
            '#title_display' => 'invisible',
            '#required' => true,
        ];
        $form['duration'] = [
            '#type' => 'number',
            '#title' => $this->t('Duration'),
            '#description' => $this->t('Duration'),
            '#step' => 0.1,
            '#attributes' => ['placeholder' => 'Duration'],
            '#title_display' => 'invisible'
        ];
        $form['project'] = [
            '#type' => 'select',
            '#title' => $this->t('Project'),
            '#description' => $this->t('Project'),
            '#required' => true,
            '#attributes' => ['placeholder' => 'Project'],
            '#options' => \Drupal::service('timesheet.projects_list')->getProjectList(),
        ];
        $form['employee'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Employee'),
            '#description' => $this->t('Employee'),
            '#required' => true,
            '#autocomplete_route_name' => 'timesheet.autocomplete.employee',
            '#disabled' => (in_array('administrator', $roles) || in_array('timesheet admin', $roles)) ? false : true,
            '#default_value' => $employee_default_value,
        ];
        $form['description'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Description'),
            '#description' => $this->t('Description'),
            '#attributes' => ['placeholder' => 'Description'],
            '#title_display' => 'invisible',
            '#required' => true,
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Add Entry'),
        ];

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $descrption = $form_state->getValue('description');
        $employee = $form_state->getValue('employee');
        $project_tid = $form_state->getValue('project');
        $duration = $form_state->getValue('duration');
        $timesheet_date = $form_state->getValue('timesheet_date');
        $employee_nid = substr($employee, strpos($employee, '('), strpos($employee, '('));
        $timesheet_data = [
            'description' => $descrption,
            'employee' => str_replace(['(', ')'], ['', ''], $employee_nid),
            'project_tid' => $project_tid,
            'duration' => $duration,
            'timesheet_date' => $timesheet_date,
        ];
        $node = $this->utilService->createTimesheetNode($timesheet_data);
        // $winner = rand(1, 2);
        // drupal_set_message('The winner is ' . $form_state->getValue('rival_' . $winner));
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        // if (empty($form_state->getValue('rival_1'))) {
        //     $form_state->setErrorByName('rival_1', $this->t('Please specify rival one.'));
        // }
    }
}