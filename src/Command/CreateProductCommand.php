<?php

namespace App\Command;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Product\Factory\ProductVariantFactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sylius\Component\Product\Factory\ProductFactory;

class CreateProductCommand extends Command
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * @var ProductVariantFactoryInterface
     */
    private $variantFactory;

    /**
     * @var ProductVariantRepositoryInterface
     */
    private $variantRepository;

    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'app:create-product';

    public function __construct(
        ProductFactoryInterface $factory,
        ProductVariantFactoryInterface $variantFactory,
        ProductRepository $productRepository,
        ProductVariantRepositoryInterface $variantRepository
    ) {
        parent::__construct();
        $this->productFactory = $factory;
        $this->variantFactory = $variantFactory;
        $this->productRepository = $productRepository;
        $this->variantRepository = $variantRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ProductInterface $product */
        $product = $this->productFactory->createNew();
        $product->setCode('00000011');
        $product->setName('Test 3');
        $product->setSlug('test 3');
        $this->productRepository->add($product);

        /** @var ProductVariantInterface $variant */
        $variant = $this->variantFactory->createNew();
        $variant->setCode('variant-test-3');
        $variant->setProduct($product);
        $this->variantRepository->add($variant);

        $output->writeln('Halo!');
        return 0;
    }
}