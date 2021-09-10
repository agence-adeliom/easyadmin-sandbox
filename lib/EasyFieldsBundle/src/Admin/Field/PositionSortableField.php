<?php

namespace Adeliom\EasyFieldsBundle\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

final class PositionSortableField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        $field = (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(NumberType::class)
            
            ->setCustomOption("parentField", "parent")
            ->setCustomOption("positionField", "position")
            ->addJsFiles([
                "bundles/easyfields/form-position-sortable.js"
            ]);
        dump($field);
        return $field;
    }

    public function setParentField($field)
    {
        $this->setCustomOption("parentField", $field);
    }

    public function setPositionField($field)
    {
        $this->setCustomOption("positionField", $field);
    }
}
