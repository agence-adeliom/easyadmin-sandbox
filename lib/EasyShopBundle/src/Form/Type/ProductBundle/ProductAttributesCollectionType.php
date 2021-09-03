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

namespace Adeliom\EasyShopBundle\Form\Type\ProductBundle;

use A2lix\TranslationFormBundle\Form\Type\TranslationsFormsType;
use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Adeliom\EasyShopBundle\Form\Listener\ProductAttributesResizeFormListener;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Product\ProductAttribute;
use App\Entity\Shop\Product\ProductAttributeValue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use Sylius\Bundle\CoreBundle\Form\Type\Product\ChannelPricingType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductAutocompleteChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ProductAttributesCollectionType extends AbstractType
{
    /** @var RepositoryInterface */
    private $productAttributesTypeRepository;

    /** @var RepositoryInterface */
    private $localeRepository;

    /** @var FormTypeRegistryInterface */
    private $formTypeRegistry;

    /** @var FormFactoryInterface */
    private $formFactory;

    public function __construct(
        RepositoryInterface $productAttributesTypeRepository,
        RepositoryInterface $localeRepository,
        FormTypeRegistryInterface $formTypeRegistry,
        FormFactoryInterface $formFactory
    ) {
        $this->productAttributesTypeRepository = $productAttributesTypeRepository;
        $this->localeRepository = $localeRepository;
        $this->formTypeRegistry = $formTypeRegistry;
        $this->formFactory = $formFactory;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['allow_add'] && $options['prototype']) {
            $prototypeOptions = array_replace([
                'required' => $options['required'],
                'label' => $options['prototype_name'].'label__',
            ], $options['entry_options']);

            if (null !== $options['prototype_data']) {
                $prototypeOptions['data'] = $options['prototype_data'];
            }
            $prototypeOptions['compound'] = true;
            $prototypeOptions['allow_extra_fields'] = true;

            $prototypes = [];
            foreach ($options['attributes'] as $type => $attribute){
                $form = $builder->create($options['prototype_name'], ProductAttributesCollectionEntryType::class, array_merge($prototypeOptions, [
                    'attribute' => $attribute,
                    'label' => $attribute->getName()
                ]));
                $prototypes[$type] = $form->getForm();
            }
            $builder->setAttribute('prototypes', $prototypes);
        }


        $resizeListener = new ProductAttributesResizeFormListener(
            $options['entry_type'],
            $options['entry_options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['delete_empty'],
            $this->productAttributesTypeRepository
        );

        $builder->addEventSubscriber($resizeListener);

        $builder->addModelTransformer(new CallbackTransformer(
            function ($array) {
                $newArray = array();

                if (!($array instanceof PersistentCollection)) {
                    return new ArrayCollection();
                }

                /** @var \Sylius\Component\Product\Model\ProductAttributeValue $entry */
                foreach ($array as $position => $entry){
                    if(!isset($newArray[$entry->getAttribute()->getCode()])){
                        $newArray[$entry->getAttribute()->getCode()] = [
                            'attribute' => $entry->getAttribute()->getCode(),
                            'position' => $position,
                        ];
                    }
                    $newArray[$entry->getAttribute()->getCode()]["value__".$entry->getLocaleCode()] = $entry->getValue();
                }
                $newArray = array_values($newArray);
                return new ArrayCollection($newArray);
            },
            function ($array) {
                $newArray = array();

                if (!$array) {
                    return new ArrayCollection();
                }

                foreach ($array as $key => $value) {
                    $item = $this->productAttributesTypeRepository->findOneBy(array('code' => $value['attribute']));
                    unset($value['attribute']);
                    unset($value['position']);
                    if (!is_null($item)) {
                        foreach ($value as $k => $data){
                            $locale = str_replace("value__", '', $k);
                            $pv = new ProductAttributeValue();
                            $pv->setAttribute($item);
                            $pv->setLocaleCode($locale);
                            $pv->setValue($data);
                            $newArray[] = $pv;
                        }
                    }
                }
                return new ArrayCollection($newArray);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $entryOptionsNormalizer = function (Options $options, $value) {
            $value['block_name'] = 'entry';

            return $value;
        };

        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'allow_drag' => false,
            'allow_add' => false,
            'allow_delete' => false,
            'prototype' => true,
            'prototypes' => [],
            'prototype_data' => null,
            'prototype_name' => '__name__',
            'entry_type' => TextType::class,
            'entry_options' => [],
            'delete_empty' => true,
            'by_reference' => false,
            'attributes' => $this->productAttributesTypeRepository->findAll(),
            'invalid_message' => function (Options $options, $previousValue) {
                return ($options['legacy_error_messages'] ?? true)
                    ? $previousValue
                    : 'The collection is invalid.';
            },
        ]);

        $resolver->setNormalizer('entry_options', $entryOptionsNormalizer);
        $resolver->setAllowedTypes('delete_empty', ['bool', 'callable']);
        $resolver->setAllowedTypes('attributes', 'array');
        $resolver->setAllowedTypes('allow_drag', 'bool');
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'allow_drag' => $options['allow_drag'],
            'allow_add' => $options['allow_add'],
            'allow_delete' => $options['allow_delete'],
            'attributes' => $options['attributes'],
        ]);
        if ($form->getConfig()->hasAttribute('prototypes')) {
            $prototypes = $form->getConfig()->getAttribute('prototypes');
            $view->vars['prototypes'] = [];
            foreach ($prototypes as $type => $prototype){
                $view->vars['prototypes'][$type] = $prototype->setParent($form)->createView($view);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $prefixOffset = -2;
        // check if the entry type also defines a block prefix
        /** @var FormInterface $entry */
        foreach ($form as $entry) {
            if ($entry->getConfig()->getOption('block_prefix')) {
                --$prefixOffset;
            }

            break;
        }

        foreach ($view as $entryView) {
            array_splice($entryView->vars['block_prefixes'], $prefixOffset, 0, 'sylius_product_attributes_entry');
        }

        /** @var FormInterface $prototype */
        if ($prototypes = $form->getConfig()->getAttribute('prototypes')) {
            foreach ($prototypes as $type => $prototype){
                if ($view->vars['prototypes'][$type]->vars['multipart']) {
                    $view->vars['multipart'] = true;
                }

                if ($prefixOffset > -3 && $prototype->getConfig()->getOption('block_prefix')) {
                    --$prefixOffset;
                }

                array_splice($view->vars['prototypes'][$type]->vars['block_prefixes'], $prefixOffset, 0, 'sylius_product_attributes_entry');
            }

        }
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_product_attributes';
    }
}
