<?php

namespace Drupal\bart\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\bart\Form\BartForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Block of BART data.
 *
 * @Block(
 *   id = "bart_block",
 *   admin_label = @Translation("BART Data")
 * )
 */
class Bart extends BlockBase implements ContainerFactoryPluginInterface {

    /**
     * @var \Drupal\bart\BartClient
     */
    //protected $bartClient;
    protected $formBuilder;

    /**
     * Bart constructor.
     *
     * @param array $configuration
     * @param $plugin_id
     * @param $plugin_definition
     * @param $bart_client \Drupal\bart\BartClient
     */


    public function __construct(array $configuration, $plugin_id, $plugin_definition, FormBuilderInterface $form_builder) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->formBuilder = $form_builder;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
                $configuration,
                $plugin_id,
                $plugin_definition,
                $container->get('form_builder')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function build() {

        $output = [
            'description' => [
                '#markup' => $this->t('Get schedule data for a station'),
            ],
        ];

        // Use the form builder service to retrieve a form by providing the full
        // name of the class that implements the form you want to display. getForm()
        // will return a render array representing the form that can be used
        // anywhere render arrays are used.
        //
        // In this case the build() method of a block plugin is expected to return
        // a render array so we add the form to the existing output and return it.
        $output['form'] = $this->formBuilder->getForm(BartForm::class);
        return $output;



    }

}
