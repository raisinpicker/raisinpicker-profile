<?php

namespace Drupal\nodeviewcount\Form;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityDisplayRepositoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure nodeviewcount settings for this site.
 */
class NodeViewCountSettingsForm extends ConfigFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity display repository.
   *
   * @var \Drupal\Core\Entity\EntityDisplayRepositoryInterface
   */
  protected $entityDisplayRepository;

  /**
   * The date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * The cache tags invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * Constructs a NodeViewCountSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityDisplayRepositoryInterface $entity_display_repository
   *   The entity display repository.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   * @param \Drupal\Core\Cache\CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   The cache tags invalidator.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager, EntityDisplayRepositoryInterface $entity_display_repository, DateFormatterInterface $date_formatter, CacheTagsInvalidatorInterface $cache_tags_invalidator) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
    $this->entityDisplayRepository = $entity_display_repository;
    $this->dateFormatter = $date_formatter;
    $this->cacheTagsInvalidator = $cache_tags_invalidator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager'),
      $container->get('entity_display.repository'),
      $container->get('date.formatter'),
      $container->get('cache_tags.invalidator')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'nodeviewcount_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['nodeviewcount.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('nodeviewcount.settings');

    $form['node_types'] = [
      '#title' => $this->t('Node types'),
      '#description' => $this->t('Choose content types to count views of node.'),
      '#type' => 'checkboxes',
      '#options' => $this->getNodeTypesOptions(),
      '#default_value' => $config->get('node_types'),
    ];

    $form['view_modes'] = [
      '#title' => $this->t('View modes'),
      '#description' => $this->t('Choose node view modes to count views of node.'),
      '#type' => 'checkboxes',
      '#options' => $this->getNodeViewModesOptions(),
      '#default_value' => $config->get('view_modes'),
    ];

    $form['user_roles'] = [
      '#title' => $this->t('User roles'),
      '#description' => $this->t('Choose user roles to count node views for.'),
      '#type' => 'checkboxes',
      '#options' => $this->getRoleNamesOptions($config->get('excluded_user_roles')),
      '#default_value' => $config->get('user_roles'),
    ];

    $form['excluded_user_roles'] = [
      '#title' => $this->t('Excluded user roles'),
      '#description' => $this->t('Choose user roles which will be excluded from counting node views.'),
      '#type' => 'checkboxes',
      '#options' => $this->getRoleNamesOptions($config->get('user_roles')),
      '#default_value' => $config->get('excluded_user_roles'),
    ];

    $form['logs_life_time'] = [
      '#type' => 'select',
      '#title' => t('Discard node views logs older than'),
      '#default_value' => $config->get('logs_life_time'),
      '#options' => $this->getLogsLifeTimeOptions(),
      '#description' => $this->t('Older log entries will be automatically discarded, (Requires a correctly configured <a href="@cron">cron maintenance task</a>.). Pick Never if you dont want logs to be deleted.',
        ['@cron' => Url::fromRoute('system.status')->toString()]),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Get all possible node types names.
   *
   * Data returned in format applicable for '#options' key of form element.
   * Keys are ids of node types, values are labels (human readable names)
   * of node types.
   *
   * @return array
   *   All possible node types names for nodes.
   */
  private function getNodeTypesOptions() {
    $node_types_options = [];
    /** @var \Drupal\node\NodeTypeInterface[] $node_types */
    $node_types = $this->entityTypeManager->getStorage('node_type')
      ->loadMultiple();
    foreach ($node_types as $node_id => $node_type) {
      $node_types_options[$node_id] = $node_type->label();
    }
    return $node_types_options;
  }

  /**
   * Get all possible view modes names for nodes.
   *
   * Data returned in format applicable for '#options' key of form element.
   * Keys are ids of view modes, values are labels (human readable names)
   * of view modes.
   *
   * @return array
   *   All possible view modes names for nodes.
   */
  private function getNodeViewModesOptions() {
    $view_modes_options = [];
    $view_modes = $this->entityDisplayRepository->getViewModes('node');
    foreach ($view_modes as $view_mode_id => $view_mode) {
      $view_modes_options[$view_mode_id] = $view_mode['label'];
    }
    return $view_modes_options;
  }

  /**
   * Get user roles names that are not in excluded user roles.
   *
   * Data returned in format applicable for '#options' key of form element.
   * Keys are ids of role names, values are labels (human readable names)
   * of roles.
   *
   * @param array $excluded_user_roles
   *   Excluded user roles.
   *
   * @return array
   *   User roles names that are not in excluded user roles.
   */
  private function getRoleNamesOptions($excluded_user_roles) {
    $roles_options = [];
    /** @var \Drupal\user\RoleInterface[] $roles */
    $roles = $this->entityTypeManager->getStorage('user_role')->loadMultiple();
    foreach ($roles as $role_id => $role) {
      if (!in_array($role_id, $excluded_user_roles)) {
        $roles_options[$role_id] = $role->label();
      }
    }
    return $roles_options;
  }

  /**
   * Get logs lifetime options.
   *
   * Data returned in format applicable for '#options' key of form element.
   * Keys are time intervals in ms, values are human readable time spans.
   *
   * @return array
   *   Logs lifetime options.
   */
  private function getLogsLifeTimeOptions() {
    $life_time_options = [0 => $this->t('Never')];
    $time_intervals = [
      86400,
      604800,
      1209600,
      2592000,
      15552000,
      31536000,
    ];
    foreach ($time_intervals as $time_interval) {
      $life_time_options[$time_interval] = $this->dateFormatter->formatInterval($time_interval);
    }
    return $life_time_options;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $counting_user_roles = array_filter($form_state->getValue('user_roles'));
    $excluded_user_roles = array_filter($form_state->getValue('excluded_user_roles'));
    foreach ($counting_user_roles as $key => $couting_user_role) {
      if ($couting_user_role) {
        unset($excluded_user_roles[$key]);
      }
    }
    $this->config('nodeviewcount.settings')
      ->set('node_types', array_keys(array_filter($form_state->getValue('node_types'))))
      ->set('view_modes', array_keys(array_filter($form_state->getValue('view_modes'))))
      ->set('user_roles', array_keys($counting_user_roles))
      ->set('excluded_user_roles', array_keys($excluded_user_roles))
      ->set('logs_life_time', (int) $form_state->getValue('logs_life_time'))
      ->save(TRUE);
    $this->cacheTagsInvalidator->invalidateTags(['node_view']);
    parent::submitForm($form, $form_state);
  }

}
