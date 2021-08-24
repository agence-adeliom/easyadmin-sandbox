<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\ProductBundle\Block;

use Knp\Menu\Provider\MenuProviderInterface;
use Adeliom\EasyShop\BlockBundle\Block\BlockContextInterface;
use Adeliom\EasyShop\BlockBundle\Block\Service\MenuBlockService;
use Adeliom\EasyShop\ProductBundle\Menu\ProductMenuBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Templating\EngineInterface;


class FiltersMenuBlockService extends MenuBlockService
{
    /**
     * @var ProductMenuBuilder
     */
    private $menuBuilder;

    /**
     * @param string $name
     */
    public function __construct($name, EngineInterface $templating, MenuProviderInterface $menuProvider, ProductMenuBuilder $menuBuilder)
    {
        parent::__construct($name, $templating, $menuProvider, []);

        $this->menuBuilder = $menuBuilder;
    }

    public function getName()
    {
        return 'Filters Menu';
    }

    public function configureSettings(OptionsResolver $resolver): void
    {
        parent::configureSettings($resolver);

        $resolver->setDefaults([
            'menu_class' => 'nav nav-list',
            'product_provider' => null,
        ]);
    }

    protected function getFormSettingsKeys()
    {
        return array_merge(parent::getFormSettingsKeys(), [
            ['menu_class', 'text', ['required' => false]],
        ]);
    }

    protected function getMenu(BlockContextInterface $blockContext)
    {
        $settings = $blockContext->getSettings();

        $menu = parent::getMenu($blockContext);

        if (null === $menu || '' === $menu) {
            $menu = $this->menuBuilder->createFiltersMenu($settings['product_provider'], ['childrenAttributes' => ['class' => $settings['menu_class']]], $settings['current_uri']);
        }

        return $menu;
    }
}
