<?php

namespace Drupal\timesheet\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Element\EntityAutocomplete;

/**
 * Custom Form for creating Timesheet entry
 */
class TimesheetCustomForm extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return "create_timesheet_form";
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['duration'] = [
            '#type' => 'number',
            '#title' => $this->t('Duration'),
            '#step' => 0.0001,
            '#attributes' => ['placeholder' => 'Duration'],
            '#title_display' => 'invisible'

        ];
        $form['description'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Description'),
            '#attributes' => ['placeholder' => 'Description'],
            '#title_display' => 'invisible'
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