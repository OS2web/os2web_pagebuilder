<?php

namespace Drupal\os2web_pagebuilder\Form;

/**
 * @file
 * Contains \Drupal\os2web_pagebuilder\Form\SettingsForm.
 */

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

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
    $config = $this->config(SettingsForm::$configName);

    $form['hide_os2web_contact_fields'] = [
      '#type' => 'checkbox',
      '#title' => t('Hide OS2Web Contact block fields'),
      '#default_value' => $config->get('hide_os2web_contact_fields'),
      '#description' => t('If checked, the OS2Web contact block related fields will be hidden on Indholdside edit form.'),
    ];

    $form['hide_os2web_paragraph_banner_field'] = [
      '#type' => 'checkbox',
      '#title' => t('Hide Banner paragraph reference field (Media tab)'),
      '#default_value' => $config->get('hide_os2web_paragraph_banner_field'),
      '#description' => t('If checked, Banner paragraph reference field will be hidden on Indholdside edit form.'),
    ];

    $form['hide_os2web_paragraph_title_field'] = [
      '#type' => 'checkbox',
      '#title' => t('Hide Title field in paragraphs'),
      '#default_value' => $config->get('hide_os2web_paragraph_title_field'),
      '#description' => t('If checked, Title field will be hidden on paragraphs.'),
    ];

    $form['hide_os2web_paragraph_subtitle_field'] = [
      '#type' => 'checkbox',
      '#title' => t('Hide Subtitle field in paragraphs'),
      '#default_value' => $config->get('hide_os2web_paragraph_subtitle_field'),
      '#description' => t('If checked, Subtitle field will be hidden on paragraphs.'),
    ];

    $form['hide_os2web_content_page_section_tab'] = [
      '#type' => 'checkbox',
      '#title' => t('Hide Section tab'),
      '#default_value' => $config->get('hide_os2web_content_page_section_tab'),
      '#description' => t('If checked, Section tab will be hidden on Indholdside edit form.'),
    ];

    $form['os2web_related_links_block_reference_mode'] = [
      '#type' => 'radios',
      '#title' => t('Related Links Block reference mode'),
      '#options' => [
        'section_keyword' => t('Section AND keyword'),
        'sections_parents_keyword' => t('Section AND siblings (terms belong to the same top level parent) AND keyword'),
      ],
      '#default_value' => $config->get('os2web_related_links_block_reference_mode'),
      '#description' => t('How related links block should be generated'),
    ];

    $form['os2web_section_pages'] = [
      '#type' => 'entity_autocomplete',
      '#title' => t('Section pages'),
      '#default_value' => ($config->get('os2web_section_pages')) ? Node::loadMultiple(array_column($config->get('os2web_section_pages'), 'target_id')) : NULL,
      '#tags' => TRUE,
      '#size' => 1000,
      '#maxlength' => 1000,
      '#target_type' => 'node',
      '#selection_handler' => 'default',
      '#selection_settings' => [
        'target_bundles' => ['os2web_page'],
      ],
      '#description' => t('List pages which will have "Intro and "Description" fields marked non-mandatory'),
    ];

    // Overriding transliteration.
    $form['os2web_pagebuilder_transliteration_overrides'] = [
      '#type' => 'details',
      '#title' => t('OS2Web Pagebuilder Transliteration overrides')
    ];

    // Transliteration overrides.
    $chars = [
      // Char Ø.
      '0xd8',
      // Char ø.
      '0xf8',
      // Char Å.
      '0xc5',
      // Char å.
      '0xe5',
      // Char Æ.
      '0xc6',
      // Char æ.
      '0xe6',
    ];

    foreach ($chars as $char) {
      $form['os2web_pagebuilder_transliteration_overrides']['os2web_pagebuilder_translit_alter_' . $char] = [
        '#type' => 'textfield',
        '#title' => t('Override for character') . ' ' . mb_chr(hexdec($char)),
        '#size' => 5,
        '#description' => t('Leave empty to use default'),
        '#default_value' => $this->config(SettingsForm::$configName)->get('os2web_pagebuilder_translit_alter_' . $char),
      ];
    }

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
