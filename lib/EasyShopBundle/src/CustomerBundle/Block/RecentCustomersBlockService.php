<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\CustomerBundle\Block;

use Adeliom\EasyShop\AdminBundle\Admin\Pool;
use Adeliom\EasyShop\AdminBundle\Form\FormMapper;
use Adeliom\EasyShop\BlockBundle\Block\BlockContextInterface;
use Adeliom\EasyShop\BlockBundle\Block\Service\AbstractAdminBlockService;
use Adeliom\EasyShop\BlockBundle\Model\BlockInterface;
use Adeliom\EasyShop\Component\Customer\CustomerManagerInterface;
use Adeliom\EasyShop\Form\Type\ImmutableArrayType;
use Adeliom\EasyShop\Form\Validator\ErrorElement;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * NEXT_MAJOR: make this class final.
 *
 */
class RecentCustomersBlockService extends AbstractAdminBlockService
{
    protected $manager;

    /**
     * @param string $name
     */
    public function __construct($name, EngineInterface $templating, CustomerManagerInterface $manager, ?Pool $adminPool = null)
    {
        $this->manager = $manager;
        $this->adminPool = $adminPool;

        parent::__construct($name, $templating);
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null)
    {
        $criteria = [
//            'mode' => $blockContext->getSetting('mode')
        ];

        return $this->renderResponse($blockContext->getTemplate(), [
            'context' => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block' => $blockContext->getBlock(),
            'customers' => $this->manager->findBy($criteria, ['createdAt' => 'DESC'], $blockContext->getSetting('number')),
            'admin_pool' => $this->adminPool,
        ], $response);
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block): void
    {
        // TODO: Implement validateBlock() method.
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block): void
    {
        $formMapper->add('settings', ImmutableArrayType::class, [
            'keys' => [
                ['number', IntegerType::class, ['required' => true]],
                ['title', TextType::class, ['required' => false]],
                ['mode', ChoiceType::class, [
                    'choices' => [
                        'public' => 'public',
                        'admin' => 'admin',
                    ],
                ]],
            ],
        ]);
    }

    public function getName()
    {
        return 'Recent Customers';
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'number' => 5,
            'mode' => 'public',
            'title' => 'Recent Customers',
//            'tags'      => 'Recent Customers',
            'template' => '@SonataCustomer/Block/recent_customers.html.twig',
        ]);
    }
}
