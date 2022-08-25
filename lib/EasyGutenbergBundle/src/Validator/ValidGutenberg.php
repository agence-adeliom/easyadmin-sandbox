<?php

namespace Adeliom\EasyGutenbergBundle\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ValidGutenberg extends Constraint
{
    /** @var string  */
    public string $message = 'The string "{{ string }}" contains an illegal character: it can only contain letters or numbers.';

}
