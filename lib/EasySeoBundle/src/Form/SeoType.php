<?php

namespace Adeliom\EasySeoBundle\Form;


use Adeliom\EasyMediaBundle\Form\EasyMediaType;
use Adeliom\EasySeoBundle\Entity\SEO;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class SeoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "form.title",
                'translation_domain' => 'EasySeoBundle'
            ])
            ->add('cover', EasyMediaType::class, [
                'label' => "form.cover",
                'translation_domain' => 'EasySeoBundle',
                "restrictions_uploadTypes" => ["image/*"],
            ])
            ->add('cannonical', UrlType::class, [
                'label' => "form.cannonical",
                'translation_domain' => 'EasySeoBundle',
            ])
            ->add('description', TextareaType::class, [
                'label' => "form.description",
                'translation_domain' => 'EasySeoBundle',
            ])
            ->add('keywords', TextType::class, [
                'label' => "form.keywords",
                'translation_domain' => 'EasySeoBundle',
            ])
            ->add('key', TextType::class, [
                'label' => "form.key",
                'translation_domain' => 'EasySeoBundle',
            ])
            ->add('robots', ChoiceType::class, [
                'label' => "form.robots",
                'translation_domain' => 'EasySeoBundle',
                'multiple' => 'true',
                'attr' => [
                    'data-ea-widget' => 'ea-autocomplete',
                ],
                'choices' => [
                    "noindex" => "noindex",
                    "nofollow" => "nofollow",
                    "noarchive" => "noarchive",
                    "nosnippet" => "nosnippet",
                    "notranslate" => "notranslate",
                    "noimageindex" => "noimageindex",
                ]
            ])
            ->add('sitemap', CheckboxType::class, [
                'label' => "form.sitemap",
                'translation_domain' => 'EasySeoBundle',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'data_class' => SEO::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return "easy_seo";
    }

}
