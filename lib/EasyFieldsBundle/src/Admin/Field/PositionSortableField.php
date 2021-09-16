<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use function Symfony\Component\String\s;

final class PositionSortableField implements FieldInterface
{
    const ACTION_URL = 'actionUrl';

    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        $field = (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(NumberType::class)
            ->setTemplateName("form_easy_field_position_sortable")
            ->setTemplatePath('@EasyFields/form/form-easy-field-position-sortable.html.twig')
            ->setCustomOption("parentProperty", "parent")
            ->setCustomOption("positionProperty", "position");
        return $field;
    }

    public function setParentProperty($field)
    {
        $this->setCustomOption("parentProperty", $field);
        return $this;
    }

    public function setPositionProperty($field)
    {
        $this->setCustomOption("positionProperty", $field);
        return $this;
    }

    public function setActionUrl($value)
    {
        $this->setCustomOption(self::ACTION_URL, $value);
        return $this;
    }

}
