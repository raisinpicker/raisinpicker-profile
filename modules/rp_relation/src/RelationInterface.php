<?php

namespace Drupal\rp_relation;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Relation entity.
 *
 * We have this interface so we can join the other interfaces it extends.
 *
 * @ingroup rp_relation
 */
interface RelationInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

}
