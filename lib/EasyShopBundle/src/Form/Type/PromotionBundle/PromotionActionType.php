<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Adeliom\EasyShopBundle\Form\Type\PromotionBundle;

use Adeliom\EasyFieldsBundle\Form\ChoiceMaskType;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Promotion\PromotionAction;
use App\Entity\Shop\Promotion\PromotionRule;
use App\Entity\Shop\Shipping\ShippingMethod;
use Sylius\Bundle\CoreBundle\Form\Type\Product\ChannelPricingType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionRuleChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Bundle\CoreBundle\Form\Type\Promotion\Rule\ChannelBasedTotalOfItemsFromTaxonConfigurationType;

final class PromotionActionType extends AbstractType
{
    /** @var FormTypeRegistryInterface */
    private $formTypeRegistry;

    /** @var array */
    private $actions;

    public function __construct(array $actions, FormTypeRegistryInterface $formTypeRegistry)
    {
        $this->formTypeRegistry = $formTypeRegistry;
        $this->actions = $actions;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $actions = $this->actions;

        $map = [];
        foreach ($this->actions as $k => $v){
            $map[$k] = [$k];
        }

        $builder
            ->add('type', ChoiceMaskType::class, [
                'label' => 'sylius.form.promotion_action.type',
                'choices' => array_flip($this->actions),
                'map' => $map,
            ])
        ;
        foreach ($this->actions as $form => $label){
            $builder->add($form, $this->formTypeRegistry->get($form, "default"), [
                "label" => false,
                "mapped" => false
            ]);
        }

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            if($rule = $event->getData()) {
                /** @var PromotionRule $rule */
                $configurations = $rule->getConfiguration();
                $form->get($rule->getType())->setData($configurations);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($actions) {
            $data = $event->getData();
            $form = $event->getForm();
            global $ruleConfiguration;
            $data["configuration"] = $ruleConfiguration = $data[$data["type"]];
            foreach (array_keys($actions) as $rule){
                $form->remove($rule);
                unset($data[$rule]);
            }
            $event->setData($data);
        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            global $ruleConfiguration;
            $data = $event->getData();
            $data->setConfiguration($ruleConfiguration);
            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefault("allow_extra_fields", true);
        $resolver->setDefault("data_class", PromotionAction::class);
    }
}
