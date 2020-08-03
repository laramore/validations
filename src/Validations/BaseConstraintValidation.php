<?php
/**
 * Define a basic validation option which can change with a constraint field.
 *
 * @author Samy Nastuzzi <samy@nastuzzi.fr>
 *
 * @copyright Copyright (c) 2019
 * @license MIT
 */

namespace Laramore\Validations;

use Laramore\Fields\Constraint\BaseConstraint;

abstract class BaseConstraintValidation extends BaseValidation
{
    protected $constraint;

    /**
     * Generate a new constraint validation based on a constraint.
     *
     * @param BaseConstraint $constraint
     * @param integer        $priority
     * @return static
     */
    public static function validationConstraint(BaseConstraint $constraint, int $priority=self::MEDIUM_PRIORITY)
    {
        $validation = new static($constraint->getMainAttribute(), $priority);
        $validation->setConstraint($constraint);

        return $validation;
    }

    /**
     * Define the constraint.
     *
     * @param BaseConstraint $constraint
     * @return self
     */
    public function setConstraint(BaseConstraint $constraint)
    {
        $this->needsToBeUnlocked();

        $this->constraint = $constraint;

        return $this;
    }

    /**
     * Return the constraint which this validation is set for.
     *
     * @return BaseConstraint
     */
    public function getConstraint(): BaseConstraint
    {
        return $this->constraint;
    }

    /**
     * Indicate if the constraint is for this validation.
     *
     * @param  BaseConstraint $constraint
     * @return boolean
     */
    abstract public static function isConstraintValid(BaseConstraint $constraint): bool;
}
