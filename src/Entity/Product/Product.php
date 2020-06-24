<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductTranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct
{
    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $ebayState;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @var int
     */
    private $ebayId;

    protected function createTranslation(): ProductTranslationInterface
    {
        return new ProductTranslation();
    }
}
