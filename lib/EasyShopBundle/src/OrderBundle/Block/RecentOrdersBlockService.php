<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\OrderBundle\Block;

use Adeliom\EasyShop\AdminBundle\Admin\Pool;
use Adeliom\EasyShop\AdminBundle\Form\FormMapper;
use Adeliom\EasyShop\BlockBundle\Block\BaseBlockService;
use Adeliom\EasyShop\BlockBundle\Block\BlockContextInterface;
use Adeliom\EasyShop\BlockBundle\Model\BlockInterface;
use Adeliom\EasyShop\Component\Customer\CustomerManagerInterface;
use Adeliom\EasyShop\Component\Order\OrderManagerInterface;
use Adeliom\EasyShop\Form\Type\ImmutableArrayType;
use Adeliom\EasyShop\Form\Validator\ErrorElement;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class RecentOrdersBlockService extends BaseBlockService
{
    /**
     * @var OrderManagerInterface
     */
    protected $orderManager;

    /**
     * @var CustomerManagerInterface
     */
    protected $customerManager;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @var Pool|null
     */
    protected $adminPool;

    public function __construct(
        string $name,
        EngineInterface $templating,
        OrderManagerInterface $orderManager,
        CustomerManagerInterface $customerManager,
        TokenStorageInterface $tokenStorage,
        ?Pool $adminPool
    ) {
        $this->orderManager = $orderManager;
        $this->customerManager = $customerManager;
        $this->tokenStorage = $tokenStorage;
        $this->adminPool = $adminPool;

        parent::__construct($name, $templating);
    }

    public function execute(BlockContextInterface $blockContext, ?Response $response = null): Response
    {
        if ('admin' !== $blockContext->getSetting('mode')) {
            $orders = $this->orderManager->findForUser(
                $this->tokenStorage->getToken()->getUser(),
                ['createdAt' => 'DESC'],
                $blockContext->getSetting('number')
            );
        } else {
            $orders = $this->orderManager->findBy(
                [],
                ['createdAt' => 'DESC'],
                $blockContext->getSetting('number')
            );
        }

        return $this->renderPrivateResponse($blockContext->getTemplate(), [
            'context' => $blockContext,
            'settings' => $blockContext->getSettings(),
            'block' => $blockContext->getBlock(),
            'orders' => $orders,
            'admin_pool' => $this->adminPool,
        ], $response);
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block): void
    {
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
        return 'Recent Orders';
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'number' => 5,
            'mode' => 'public',
            'title' => 'Recent Orders',
            'template' => '@SonataOrder/Block/recent_orders.html.twig',
        ]);
    }
}
