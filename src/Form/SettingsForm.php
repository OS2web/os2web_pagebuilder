<?php

namespace Drupal\os2web_pagebuilder\Form;

/**
 * @file
 * Contains \Drupal\os2web_pagebuilder\Form\SettingsForm.
 */

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * OS2Web Pagbuilder settings form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Name of the config.
   *
   * @var string
   */
  public static $configName = 'os2web_pagebuilder.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'os2web_pagebuilder_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [SettingsForm::$configName];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['hide_os2web_contact_fields'] = [
      '#type' => 'checkbox',
      '#title' => t('Hide OS2Web Contact block fields'),
      '#default_value' => $this->config(SettingsForm::$configName)
        ->get('hide_os2web_contact_fields'),
      '#description' => t('If checked, the OS2Web contact block related fields will be hidden on Indholdside edit form.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();

    $config = $this->config(SettingsForm::$configName);
    foreach ($values as $key => $value) {
      $config->set($key, $value);
    }
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
