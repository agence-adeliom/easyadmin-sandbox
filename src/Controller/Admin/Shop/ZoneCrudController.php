<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\AssociationField;
use Adeliom\EasyFieldsBundle\Admin\Field\TranslationField;
use App\Entity\Shop\Addressing\Country;
use App\Entity\Shop\Addressing\Zone;
use App\Entity\Shop\Channel\Channel;
use App\Entity\Shop\Currency\Currency;
use App\Entity\Shop\Locale\Locale;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\Taxonomy\Taxon;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\ActionConfigDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\LocaleField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\AddressingBundle\Form\Type\CountryCodeChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceCodeChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ZoneCodeChoiceType;
use Sylius\Bundle\AddressingBundle\Form\Type\ZoneMemberType;
use Sylius\Bundle\CurrencyBundle\Form\Type\CurrencyType;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\Scope;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class ZoneCrudController extends AbstractCrudController
{
    private AdminUrlGenerator $crudUrlGenerator;

    public function __construct(AdminUrlGenerator $crudUrlGenerator)
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Zone::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $url = $this->crudUrlGenerator->setController(self::class)->setAction(Action::NEW);

        $newZoneCountries = Action::new('zoneCountries', 'Zone of countries')->linkToUrl((clone $url)->set("zoneType", ZoneInterface::TYPE_COUNTRY)->generateUrl())->createAsGlobalAction()->setCssClass("btn btn-primary");
        $newZoneProvinces = Action::new('zoneProvinces', 'Zone of provinces')->linkToUrl((clone $url)->set("zoneType", ZoneInterface::TYPE_PROVINCE)->generateUrl())->createAsGlobalAction()->setCssClass("btn btn-primary");
        $newZoneOther = Action::new('zoneOther', 'Zone of other zones')->linkToUrl((clone $url)->set("zoneType", ZoneInterface::TYPE_ZONE)->generateUrl())->createAsGlobalAction()->setCssClass("btn btn-primary");

        $actions = parent::configureActions($actions);

        $actions->remove(Crud::PAGE_INDEX, Action::NEW);
        $actions->add(Crud::PAGE_INDEX, $newZoneOther);
        $actions->add(Crud::PAGE_INDEX, $newZoneProvinces);
        $actions->add(Crud::PAGE_INDEX, $newZoneCountries);

        return $actions;
    }

    public function new(AdminContext $context)
    {
        global $zoneType;
        $zoneType = $context->getRequest()->query->get("zoneType");
        return parent::new($context);
    }

    public function createEntity(string $entityFqcn)
    {
        global $zoneType;
        /** @var ZoneInterface $entity */
        $entity = new $entityFqcn();
        $entity->setType($zoneType);
        $entity->setScope(Scope::ALL);
        return $entity;
    }

    private function getZoneMemberEntryType(string $zoneMemberType): string
    {
        $zoneMemberEntryTypes = [
            ZoneInterface::TYPE_COUNTRY => CountryCodeChoiceType::class,
            ZoneInterface::TYPE_PROVINCE => ProvinceCodeChoiceType::class,
            ZoneInterface::TYPE_ZONE => ZoneCodeChoiceType::class,
        ];

        return $zoneMemberEntryTypes[$zoneMemberType];
    }

    private function getZoneMemberEntryOptions(string $zoneMemberType): array
    {
        $zoneMemberEntryOptions = [
            ZoneInterface::TYPE_COUNTRY => ['label' => 'sylius.form.zone.types.country'],
            ZoneInterface::TYPE_PROVINCE => ['label' => 'sylius.form.zone.types.province'],
            ZoneInterface::TYPE_ZONE => ['label' => 'sylius.form.zone.types.zone'],
        ];

        return $zoneMemberEntryOptions[$zoneMemberType];
    }

    public function configureFields(string $pageName): iterable
    {
        $context = $this->get(AdminContextProvider::class)->getContext();
        $subject = $context->getEntity();
        $zone = $subject->getInstance();

        yield TextField::new('type')->hideOnForm();
        yield TextField::new('code');
        yield TextField::new('name');
        yield ChoiceField::new('scope')->setChoices([
            "Shipping" => Scope::SHIPPING,
            "Tax" => Scope::TAX,
            "All" => Scope::ALL,
        ]);

        if (in_array($pageName, [Crud::PAGE_NEW , Crud::PAGE_EDIT])) {
            $entryOptions = [
                'entry_type' => $this->getZoneMemberEntryType($zone->getType()),
                'entry_options' => $this->getZoneMemberEntryOptions($zone->getType()),
            ];

            if ($zone->getType() === ZoneInterface::TYPE_ZONE) {
                $entryOptions['entry_options']['choice_filter'] = static function (?ZoneInterface $subZone) use ($zone): bool {
                    return $subZone !== null && $zone->getId() !== $subZone->getId();
                };
            }

            yield CollectionField::new("members")
                ->setEntryType(ZoneMemberType::class)
                ->setFormTypeOption("entry_options", $entryOptions)
                ->setFormTypeOption('allow_add', true)
                ->setFormTypeOption('allow_delete', true)
                ->setFormTypeOption('by_reference', false)
                ->setFormTypeOption('delete_empty', true);
        }
    }

}
