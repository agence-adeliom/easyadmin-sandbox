<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Form;

use Symfony\Component\Validator\Constraint;

class Basket extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Basket not valid';

    public function validatedBy()
    {
        return 'sonata_basket_validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
