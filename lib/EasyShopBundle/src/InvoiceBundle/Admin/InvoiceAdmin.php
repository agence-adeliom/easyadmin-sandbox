<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\InvoiceBundle\Admin;

use Adeliom\EasyShop\AdminBundle\Admin\AbstractAdmin;
use Adeliom\EasyShop\AdminBundle\Datagrid\DatagridMapper;
use Adeliom\EasyShop\AdminBundle\Datagrid\ListMapper;
use Adeliom\EasyShop\AdminBundle\Form\FormMapper;
use Adeliom\EasyShop\AdminBundle\Form\Type\ModelListType;
use Adeliom\EasyShop\Component\Currency\CurrencyDetectorInterface;
use Adeliom\EasyShop\Component\Currency\CurrencyFormType;
use Adeliom\EasyShop\InvoiceBundle\Form\Type\InvoiceStatusType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class InvoiceAdmin extends AbstractAdmin
{
    /**
     * @var CurrencyDetectorInterface
     */
    protected $currencyDetector;

    public function setCurrencyDetector(CurrencyDetectorInterface $currencyDetector): void
    {
        $this->currencyDetector = $currencyDetector;
    }

    public function configure(): void
    {
        $this->setTranslationDomain('SonataInvoiceBundle');
    }

    public function configureFormFields(FormMapper $formMapper): void
    {
        if (!$this->isChild()) {
            $formMapper
                ->with('invoice.form.group_main_label')
                    ->add('customer', ModelListType::class)
                ->end();
        }

        $formMapper
            ->with('invoice.form.group_main_label')
                ->add('reference')
                ->add('currency', CurrencyFormType::class)
                ->add('status', InvoiceStatusType::class, ['translation_domain' => $this->translationDomain])
                ->add('totalExcl')
                ->add('totalInc')
            ->end()
            ->with('invoice.form.group_billing_label', ['collapsed' => true])
                ->add('name')
                ->add('phone')
                ->add('address1')
                ->add('address2')
                ->add('address3')
                ->add('city')
                ->add('postcode')
                ->add('country', CountryType::class)
                ->add('fax')
                ->add('email')
                ->add('mobile')
            ->end();
    }

    public function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('reference')
            ->add('customer')
            ->add('status', TextType::class, [
                'template' => '@SonataInvoice/InvoiceAdmin/list_status.html.twig',
            ])
            ->add('totalExcl', CurrencyFormType::class, [
                'currency' => $this->currencyDetector->getCurrency()->getLabel(),
            ])
            ->add('totalInc', CurrencyFormType::class, [
                'currency' => $this->currencyDetector->getCurrency()->getLabel(),
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('reference')
            ->add('customer')
            ->add('status', null, [], InvoiceStatusType::class, ['translation_domain' => $this->translationDomain]);
    }
}
