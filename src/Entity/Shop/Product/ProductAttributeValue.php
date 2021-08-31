<?php

declare(strict_types=1);

namespace App\Entity\Shop\Product;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Product\Model\ProductAttributeValue as BaseProductAttributeValue;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_attribute_value")
 */
class ProductAttributeValue extends BaseProductAttributeValue
{
    public function __toString()
    {
        return $this->getLocaleCode() . ' | ' . $this->getName() . ' : ' . ( !is_array($this->getValue()) ? $this->getValue() : implode(', ', $this->getValue()));
    }
}
