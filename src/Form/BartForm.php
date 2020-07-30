<?php

namespace Drupal\bart\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Implements the  form controller.
 *
 * @see \Drupal\Core\Form\FormBase
 */
class BartForm extends FormBase {

    /**
     * Build the BART form.
     *
     * A build form method constructs an array that defines how markup and
     * other form elements are included in an HTML form.
     *
     * @param array $form
     *   Default form array structure.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Object containing current form state.
     *
     * @return array
     *   The render array defining the elements of the form.
     */
    protected $bartClient;

    public function buildForm(array $form, FormStateInterface $form_state) {
        $bart_client = \Drupal::service('bart_client');
        $bart_stations = $bart_client->returnStations();
        //dpm($bart_stations);
        $form['description'] = [
            '#type' => 'item',
            '#markup' => $this->t('View the schedule for the BART stations.'),
        ];

        $form['station'] = [
            '#type' => 'select',
            '#title' => $this
                    ->t('Select a station'),
            //'#options' => $bart_stations,
            '#options' => $bart_stations,
        ];

        // Group submit handlers in an actions element with a key of "actions" so
        // that it gets styled correctly, and so that other modules may add actions
        // to the form. This is not required, but is convention.
        $form['actions'] = [
            '#type' => 'actions',
        ];

        // Add a submit button that handles the submission of the form.
        /*    $form['actions']['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t('Submit'),
          ];
         * */

        $form['actions'] = [
            '#type' => 'button',
            '#value' => $this->t('Show schedule'),
            '#ajax' => [
                'callback' => '::getResults',
            ],
        ];
        $form['results'] = [
            '#type' => 'markup',
            '#markup' => '<div class="results"></div>'
        ];
        return $form;
    }

    /**
     * Getter method for Form ID.
     *
     * The form ID is used in implementations of hook_form_alter() to allow other
     * modules to alter the render array built by this form controller. It must be
     * unique site wide. It normally starts with the providing module's name.
     *
     * @return string
     *   The unique ID of the form defined by this class.
     */
    public function getFormId() {
        return 'bart_form';
    }

    /**
     * Implements form validation.
     *
     * The validateForm method is the default method called to validate input on
     * a form.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Object describing the current state of the form.
     */
    /*
      public function validateForm(array &$form, FormStateInterface $form_state) {
      $title = $form_state->getValue('title');
      if (strlen($title) < 5) {
      // Set an error for the form element with a key of "title".
      $form_state->setErrorByName('title', $this->t('The title must be at least 5 characters long.'));
      }
      }
     */
    /**
     * Implements a form submit handler.
     *
     * The submitForm method is the default method called for any submit elements.
     *
     * @param array $form
     *   The render array of the currently built form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   Object describing the current state of the form.
     */

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        //not necessary with AJAX form
        //$this->messenger()->addStatus();
    }

    public function getResults(array $form, FormStateInterface $form_state) {
        $station = $form_state->getValue('station');

        $bart_client = \Drupal::service('bart_client');
        $bart_routes_data = $bart_client->getRoutes($station);
        $bart_routes = $bart_client->showRoutes($bart_routes_data);

        $response = new AjaxResponse();
        $response->addCommand(
                new HtmlCommand(
                        '.results',
                        $bart_routes),
        );
        return $response;
    }

}
