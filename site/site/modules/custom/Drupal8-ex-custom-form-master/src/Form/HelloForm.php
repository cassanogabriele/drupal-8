<?php

namespace Drupal\ex81\Form;
use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * HelloForm controller.
 */
class HelloForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'ex81_hello_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
		$form['description'] = [
		  '#type' => 'item',
		  '#markup' => $this->t('Veuillez entrer le nom et le style du groupe à ajouters.'),
		];

		$form['nom'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Nom du groupe'),
			'#attributes' => array(
				'placeholder' => t('Entrez le nom du groupe'),
			),
			'#required' => TRUE,
		];
		
		$form['style'] = [
			'#type' => 'textfield',
			'#title' => $this->t('Style du groupe'),
			'#attributes' => array(
				'placeholder' => t('Entrez le style du groupe'),
			),
			'#required' => TRUE,
		];    
	   
		$form['actions'] = [
		  '#type' => 'actions',
		];

		// Add a submit button that handles the submission of the form.
		$form['actions']['submit'] = [
		  '#type' => 'submit',
		  '#value' => $this->t('Submit'),
		];

		return $form;
	}

    public function validateForm(array &$form, FormStateInterface $form_state) {
		parent::validateForm($form, $form_state);

		$nom = $form_state->getValue('nom');
		$style = $form_state->getValue('style');

		if(empty($nom)) {		 
		  $form_state->setErrorByName('nom', $this->t('Vous devez entrer un nom de groupe'));
		}
		
		if(empty($style)) {		 
		  $form_state->setErrorByName('nom', $this->t('Vous devez entrer un style pour le groupe'));
		}
	}

	public function submitForm(array &$form, FormStateInterface $form_state) {
		$connection = Database::getConnection();
		
		$connection->insert('listofbands')->fields(
			array(
				'band_name' => $form_state->getValue('nom'),
				'style_rock' => $form_state->getValue('style'),			
			)
		)->execute();
		
		$messenger = \Drupal::messenger();
		$messenger->addMessage('Nom: ' . $form_state->getValue('nom'));
		$messenger->addMessage('Style: ' . $form_state->getValue('style'));

		$form_state->setRedirect('<front>');
	}
}
