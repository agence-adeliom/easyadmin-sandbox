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
    protected $translator;
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => $this->translator->trans("form.title", [], "EasySEOBundle"),
            ])
            ->add('cover', EasyMediaType::class, [
                'label' => $this->translator->trans("form.cover", [], "EasySEOBundle"),
                "restrictions_uploadTypes" => ["image/*"],
            ])
            ->add('cannonical', UrlType::class, [
                'label' => $this->translator->trans("form.cannonical", [], "EasySEOBundle"),
            ])
            ->add('description', TextareaType::class, [
                'label' => $this->translator->trans("form.description", [], "EasySEOBundle"),
            ])
            ->add('keywords', TextType::class, [
                'label' => $this->translator->trans("form.keywords", [], "EasySEOBundle"),
            ])
            ->add('key', TextType::class, [
                'label' => $this->translator->trans("form.key", [], "EasySEOBundle"),
            ])
            ->add('robots', ChoiceType::class, [
                'label' => $this->translator->trans("form.robots", [], "EasySEOBundle"),
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
                'label' => $this->translator->trans("form.sitemap", [], "EasySEOBundle"),
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
