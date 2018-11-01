<?php

namespace Drupal\rp_points\Plugin\Transaction;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\transaction\Plugin\Transaction\BalanceTransactor;
use Drupal\transaction\TransactionInterface;
use Drupal\transaction\TransactionTypeInterface;
use Drupal\user\Entity\Role;
use Drupal\user\RoleInterface;

/**
 * Transactor for user points type transactions.
 *
 * @Transactor(
 *   id = "userpoints",
 *   title = @Translation("User points"),
 *   description = @Translation("Transactor for user points system."),
 *   supported_entity_types = {
 *     "user",
 *   },
 *   transaction_fields = {
 *     {
 *       "name" = "amount",
 *       "type" = "decimal",
 *       "title" = @Translation("Amount"),
 *       "description" = @Translation("A numeric field with the amount of points."),
 *       "required" = TRUE,
 *       "list" = TRUE,
 *     },
 *     {
 *       "name" = "balance",
 *       "type" = "decimal",
 *       "title" = @Translation("Balance"),
 *       "description" = @Translation("A numeric field to store the current points balance."),
 *       "required" = TRUE,
 *       "list" = TRUE,
 *     },
 *     {
 *       "name" = "log_message",
 *       "type" = "string",
 *       "title" = @Translation("Log message"),
 *       "description" = @Translation("A log message with details about the transaction."),
 *       "required" = FALSE,
 *     },
 *   },
 *   target_entity_fields = {
 *     {
 *       "name" = "last_transaction",
 *       "type" = "entity_reference",
 *       "title" = @Translation("Last transaction"),
 *       "description" = @Translation("A reference field in the user entity type to update with a reference to the last executed transaction of this type."),
 *       "required" = FALSE,
 *     },
 *     {
 *       "name" = "target_balance",
 *       "type" = "decimal",
 *       "title" = @Translation("Balance"),
 *       "description" = @Translation("A numeric field in the user entity to update with the current points balance."),
 *       "required" = FALSE,
 *     },
 *   },
 * )
 */
class UserPointsTransactor extends BalanceTransactor {

  /**
   * {@inheritdoc}
   */
  public function validateTransaction(TransactionInterface $transaction) {
    if (parent::validateTransaction($transaction)) {
      // @todo check required fields and values
      return TRUE;
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function getTransactionDescription(TransactionInterface $transaction, $langcode = NULL) {
    $settings = $transaction->getType()->getPluginSettings();

    // Transaction amount.
    $amount = $transaction->get($settings['amount'])->value;

    $t_options = $langcode ? ['langcode' => $langcode] : [];
    $t_args = ['@status' => $transaction->isPending() ? $this->t('(pending)') : ''];
    if ($amount > 0) {
      $description = $this->t('Points credit @status', $t_args, $t_options);
    }
    elseif ($amount < 0) {
      $description = $this->t('Points debit @status', $t_args, $t_options);
    }
    else {
      $description = $this->t('Zero points transaction @status', $t_args, $t_options);
    }

    return $description;
  }

  /**
   * {@inheritdoc}
   */
  public function getExecutionIndications(TransactionInterface $transaction, $langcode = NULL) {
    $settings = $transaction->getType()->getPluginSettings();
    // Transaction amount.
    $amount = $transaction->get($settings['amount'])->value;

    // @todo pretty print of amount according to default display settings
    $t_args = ['@amount' => abs($transaction->get($settings['amount'])->value)];
    $t_options = $langcode ? ['langcode' => $langcode] : [];
    if ($amount > 0) {
      $indication = $this->t('The user will gain @amount points.', $t_args, $t_options);
    }
    elseif ($amount < 0) {
      $indication = $this->t('The user will loss @amount points.', $t_args, $t_options);
    }
    else {
      $indication = $this->t('The current user points balance will not be altered.', [], $t_options);
    }

    return $indication;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\transaction\TransactionTypeInterface $transaction_type */
    $transaction_type = $form_state->getFormObject()->getEntity();
    $transactor_settings = $transaction_type->getPluginSettings();

    // Applicable roles.
    $roles = [];
    foreach (Role::loadMultiple() as $role_id => $role_entity) {
      if (!in_array($role_id, [
          RoleInterface::ANONYMOUS_ID,
          RoleInterface::AUTHENTICATED_ID,
        ])) {
        $roles[$role_id] = $role_entity->label();
      }
    }
    asort($roles);

    if (!count($roles)) {
      $roles = ['' => $this->t('- None -')];
    }

    $form['roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Applicable user roles'),
      '#description' => $this->t('The user roles to which this type of points is applicable. Leave empty to apply to any existing role.'),
      '#options' => $roles,
      '#default_value' => isset($transactor_settings['roles']) ? explode(',', $transactor_settings['roles']) : [''],
    ];

    return parent::buildConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    // Add roles to plugin settings settings.
    $roles = [];
    foreach($form_state->getValue('roles') as $role) {
      if (!empty($role)) {
        $roles[] = $role;
      }
    };

    /** @var \Drupal\transaction\TransactionTypeInterface $transaction_type */
    $transaction_type = $form_state->getFormObject()->getEntity();
    $settings = $transaction_type->getPluginSettings();
    $settings['roles'] = implode(',', $roles);
    $transaction_type->setPluginSettings($settings);

    return parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function isApplicable(ContentEntityInterface $entity, TransactionTypeInterface $transaction_type = NULL) {
    /** @var \Drupal\user\UserInterface $entity */
    if (parent::isApplicable($entity)) {
      if ($transaction_type) {
        $settings = $transaction_type->getPluginSettings();
        // Apply to any user when no roles in settings.
        return empty($settings['roles'])
          || !empty(array_intersect($entity->getRoles(TRUE), explode(',', $settings['roles'])));
      }
    }

    return FALSE;
  }

}
