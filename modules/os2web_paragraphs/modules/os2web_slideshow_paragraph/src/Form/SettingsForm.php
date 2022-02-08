<?php

namespace Drupal\os2web_slideshow_paragraph\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure os2web_slideshow_paragraph settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Name of the config.
   *
   * @var string
   */
  public static $configName = 'os2web_slideshow_paragraph.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'os2web_slideshow_paragraph_settings';
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
    // Import settings.
   $form['os2web_slideshow_paragraph_items'] = [
      '#type' => 'textfield',
      '#title' => t('Amount of slider\'s items on desktop view'),
      '#description' => t('This decided how much items will be shown in slider on desktop view'),
      '#default_value' =>($this->config(SettingsForm::$configName)
        ->get('os2web_slideshow_paragraph_items') !== null) ? $this->config(SettingsForm::$configName)
        ->get('os2web_slideshow_paragraph_items') : 2,
    ];    return parent::buildForm($form, $form_state);
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
