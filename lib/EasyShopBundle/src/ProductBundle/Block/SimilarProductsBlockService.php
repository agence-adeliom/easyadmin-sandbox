<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Block;

use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Adeliom\EasyShop\AdminBundle\Form\FormMapper;
use Adeliom\EasyShop\BlockBundle\Block\BaseBlockService;
use Adeliom\EasyShop\BlockBundle\Block\BlockContextInterface;
use Adeliom\EasyShop\BlockBundle\Model\BlockInterface;
use Adeliom\EasyShop\Component\Currency\CurrencyDetectorInterface;
use Adeliom\EasyShop\Component\Product\ProductFinderInterface;
use Adeliom\EasyShop\Form\Type\ImmutableArrayType;
use Adeliom\EasyShop\Form\Validator\ErrorElement;
use Adeliom\EasyShop\ProductBundle\Repository\BaseProductRepository;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SimilarProductsBlockService extends BaseBlockService
{
    /**
     * @var EntityRepository
     */
    protected $productRepository;

    /**
     * @var ProductFinderInterface
     */
    protected $productFinder;

    /**
     * @var CurrencyDetectorInterface
     */
    protected $currencyDetector;

    /**
     * @param string $name
     * @param string $productClass
     */
    public function __construct($name, EngineInterface $templating, ManagerRegistry $registry, CurrencyDetectorInterface $currencyDetector, ProductFinderInterface $productFinder, $productClass)
    {
        $this->productRepository = $registry->getManager()->getRepository($productClass);
        $this->currencyDetector = $currencyDetector;
        $this->productFinder = $productFinder;

        parent::__construct($name, $templating);
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null)
    {
        if (!$product = $this->getProductRepository()->findOneBy(['id' => $blockContext->getSetting('base_product_id')])) {
            return;
        }

        $products = $this->getProductFinder()->getCrossSellingSimilarParentProducts($product, $blockContext->getSetting('number'));

        $params = [
            'context' => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block' => $blockContext->getBlock(),
            'products' => $products,
            'currency' => $this->currencyDetector->getCurrency(),
        ];

        return $this->renderResponse($blockContext->getTemplate(), $params, $response);
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block): void
    {
        // TODO: Implement validateBlock() method.
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block): void
    {
        $formMapper->add('settings', ImmutableArrayType::class, [
            'keys' => [
                ['number',  IntegerType::class, [
                    'required' => true,
                ]],
                ['title', TextType::class, [
                    'required' => false,
                ]],
                ['base_product_id', IntegerType::class, [
                    'required' => false,
                ]],
            ],
        ]);
    }

    public function getName()
    {
        return 'Similar products';
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'number' => 5,
            'title' => 'Similar products',
            'base_product_id' => null,
            'template' => '@SonataProduct/Block/similar_products.html.twig',
        ]);
    }

    /**
     * Returns the Base ProductRepository.
     *
     * @return BaseProductRepository
     */
    protected function getProductRepository()
    {
        return $this->productRepository;
    }

    /**
     * Returns the Product finder.
     *
     * @return ProductFinderInterface
     */
    protected function getProductFinder()
    {
        return $this->productFinder;
    }
}
