<?php

namespace App\Controller\Admin\Shop;

use Adeliom\EasyFieldsBundle\Admin\Field\FormTypeField;
use Adeliom\EasyFieldsBundle\Admin\Field\SortableCollectionField;
use Adeliom\EasyShopBundle\Form\Type\PromotionBundle\PromotionRuleType;
use App\Entity\Shop\Addressing\Country;
use App\Entity\Shop\Promotion\Promotion;
use App\Entity\Shop\Promotion\PromotionCoupon;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sylius\Bundle\AddressingBundle\Form\Type\ProvinceType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionActionType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionCouponGeneratorInstructionType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionCouponType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionRuleCollectionType;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionActionCollectionType;
use Sylius\Bundle\ResourceBundle\Form\Registry\FormTypeRegistryInterface;
use Sylius\Component\Attribute\Factory\AttributeFactoryInterface;
use Sylius\Component\Promotion\Factory\PromotionCouponFactoryInterface;
use Sylius\Component\Promotion\Generator\PromotionCouponGeneratorInterface;
use Sylius\Component\Promotion\Repository\PromotionCouponRepositoryInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PromotionCrudController extends AbstractCrudController
{

    /** @var FormTypeRegistryInterface */
    protected $formTypeRegistry;
    /** @var PromotionCouponFactoryInterface */
    protected $couponFactory;
    /** @var EntityManagerInterface */
    protected $couponManager;
    /** @var PromotionCouponRepositoryInterface */
    protected $couponRepository;
    /** @var PromotionCouponGeneratorInterface */
    protected $couponGenerator;
    private AdminUrlGenerator $crudUrlGenerator;
    private ParameterBagInterface $parameterBag;

    public function __construct(AdminUrlGenerator $crudUrlGenerator,
                                ParameterBagInterface $parameterBag,
                                PromotionCouponFactoryInterface $couponFactory,
                                EntityManagerInterface $couponManager,
                                PromotionCouponRepositoryInterface $couponRepository,
                                PromotionCouponGeneratorInterface $couponGenerator
    )
    {
        $this->crudUrlGenerator = $crudUrlGenerator;
        $this->parameterBag = $parameterBag;
        $this->couponFactory = $couponFactory;
        $this->couponManager = $couponManager;
        $this->couponRepository = $couponRepository;
        $this->couponGenerator = $couponGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Promotion::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@EasyFields/form/choice_mask_widget.html.twig')
            ->addFormTheme('@EasyFields/form/sortable_widget.html.twig')
            ->setPageTitle(Crud::PAGE_INDEX, "sylius.ui.manage_promotions")
            ->setPageTitle(Crud::PAGE_NEW, "sylius.ui.create_promotion")
            ->setPageTitle(Crud::PAGE_EDIT, "sylius.ui.edit_promotion")
            ->setPageTitle(Crud::PAGE_DETAIL, "sylius.ui.promotions")
            ->setEntityLabelInSingular('sylius.ui.promotions')
            ->setEntityLabelInPlural('sylius.ui.promotions')
            ->setFormOptions([
                'validation_groups' => ['Default', 'sylius']
            ])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {

        $actions = parent::configureActions($actions);

        $viewCoupon = Action::new('manageCoupon', 'sylius.ui.manage_coupons', 'fas fa-ticket-alt')
            ->displayIf(static function ($entity) {
                return $entity->isCouponBased();
            })->linkToCrudAction("manageCoupon");

        $actions
            ->add(Crud::PAGE_INDEX, $viewCoupon);


        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('code','sylius.ui.code')
            ->setFormTypeOption('disabled', (in_array($pageName, [Crud::PAGE_EDIT]) ? 'disabled' : ''))
            ->setRequired(true)->setColumns(6);
        yield TextField::new('name','sylius.form.promotion.name')->setRequired(true)->setColumns(6);
        yield TextareaField::new('description','sylius.form.promotion.description')->setColumns(12)->hideOnIndex();
        yield IntegerField::new('usageLimit','sylius.form.promotion.usage_limit')->setColumns(6);
        yield IntegerField::new('priority','sylius.form.promotion.priority')->setColumns(6);
        yield BooleanField::new('exclusive','sylius.form.promotion.exclusive')->setColumns(6)->hideOnIndex();
        yield BooleanField::new('couponBased','sylius.form.promotion.coupon_based')->setColumns(6)->renderAsSwitch(in_array($pageName, [Crud::PAGE_EDIT, Crud::PAGE_NEW]));

        yield FormTypeField::new('channels', 'sylius.form.promotion.channels', ChannelChoiceType::class)->hideOnIndex()
            ->setFormTypeOptions(['multiple' => true, 'expanded' => true]);

        yield DateTimeField::new("startsAt", 'sylius.form.promotion.starts_at')->setColumns(6)->hideOnIndex();
        yield DateTimeField::new("endsAt", 'sylius.form.promotion.ends_at')->setColumns(6)->hideOnIndex();

        yield SortableCollectionField::new('rules', 'sylius.form.promotion.rules')->setColumns(6)
            ->setEntryType(PromotionRuleType::class)->allowAdd()->allowDrag(false)->hideOnIndex();
        yield SortableCollectionField::new('actions', 'sylius.form.promotion.actions')->setColumns(6)
            ->setEntryType(\Adeliom\EasyShopBundle\Form\Type\PromotionBundle\PromotionActionType::class)->allowAdd()->allowDrag(false)->hideOnIndex();
    }

    public function manageCoupon(AdminContext $context): Response
    {
        return $this->render('@EasyShop/crud/promotion/coupon.html.twig', [
            'promotion' => $context->getEntity()->getInstance()
        ]);
    }

    public function createCoupon(AdminContext $context): Response
    {
        $coupon = $this->couponFactory->createForPromotion($context->getEntity()->getInstance());
        $form = $this->createForm(PromotionCouponType::class, $coupon);

        $form->handleRequest($context->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $coupon = $form->getData();
            $this->couponManager->persist($coupon);
            $this->couponManager->flush();
            $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($coupon->getPromotion()->getId())->setAction("manageCoupon")->generateUrl();
            return $this->redirect($url);
        }

        return $this->render('@EasyShop/crud/promotion/new_coupon.html.twig', [
            'promotion' => $context->getEntity()->getInstance(),
            'form' => $form->createView()
        ]);
    }

    public function generateCoupons(AdminContext $context): Response
    {
        $form = $this->createForm(PromotionCouponGeneratorInstructionType::class);

        $form->handleRequest($context->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $instruction = $form->getData();
            $this->couponGenerator->generate($context->getEntity()->getInstance(), $instruction);

            $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($context->getEntity()->getPrimaryKeyValue())->setAction("manageCoupon")->generateUrl();
            return $this->redirect($url);
        }

        return $this->render('@EasyShop/crud/promotion/generate_coupons.html.twig', [
            'promotion' => $context->getEntity()->getInstance(),
            'form' => $form->createView()
        ]);
    }

    public function batchDeleteCoupons(AdminContext $context): Response
    {
        foreach ($context->getRequest()->get("batchActionEntityIds", []) as $i){
            $coupon = $this->couponRepository->find($i);
            if (!$coupon) {
                continue;
            }
            $this->couponManager->remove($coupon);
            $this->couponManager->flush();
        }
        $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($coupon->getPromotion()->getId())->setAction("manageCoupon")->generateUrl();
        return $this->redirect($url);
    }



    public function editCoupon(AdminContext $context): Response
    {
        $coupon = $this->couponRepository->find($context->getRequest()->query->get("couponId"));
        if (!($coupon instanceof \Sylius\Component\Promotion\Model\PromotionCoupon)){
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(PromotionCouponType::class, $coupon);
        $form->handleRequest($context->getRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $coupon = $form->getData();
            $this->couponManager->persist($coupon);
            $this->couponManager->flush();
            $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($coupon->getPromotion()->getId())->setAction("manageCoupon")->generateUrl();
            return $this->redirect($url);
        }

        return $this->render('@EasyShop/crud/promotion/edit_coupon.html.twig', [
            'promotion' => $context->getEntity()->getInstance(),
            'form' => $form->createView()
        ]);
    }

    public function deleteCoupon(AdminContext $context): Response
    {
        $coupon = $this->couponRepository->find($context->getRequest()->query->get("couponId"));
        if (!($coupon instanceof \Sylius\Component\Promotion\Model\PromotionCoupon)){
            throw new NotFoundHttpException();
        }

        $this->couponManager->remove($coupon);
        $this->couponManager->flush();

        $url = $this->crudUrlGenerator->setController(self::class)->setEntityId($coupon->getPromotion()->getId())->setAction("manageCoupon")->generateUrl();
        return $this->redirect($url);
    }
}
