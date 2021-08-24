<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Basket;

use Adeliom\EasyShop\Component\Currency\CurrencyDetectorInterface;
use Adeliom\EasyShop\Component\Customer\CustomerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;


abstract class BaseBasketFactory implements BasketFactoryInterface, LogoutHandlerInterface
{
    public const SESSION_BASE_NAME = 'sonata/basket/factory/customer/';

    /**
     * @var \Adeliom\EasyShop\Component\Basket\BasketManagerInterface
     */
    protected $basketManager;

    /**
     * @var \Adeliom\EasyShop\Component\Basket\BasketBuilderInterface
     */
    protected $basketBuilder;

    /**
     * @var \Adeliom\EasyShop\Component\Currency\CurrencyDetectorInterface
     */
    protected $currencyDetector;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    public function __construct(BasketManagerInterface $basketManager, BasketBuilderInterface $basketBuilder, CurrencyDetectorInterface $currencyDetector, SessionInterface $session)
    {
        $this->basketManager = $basketManager;
        $this->basketBuilder = $basketBuilder;
        $this->currencyDetector = $currencyDetector;
        $this->session = $session;
    }

    public function load(CustomerInterface $customer)
    {
        $basket = $this->getFromSession($customer);

        if (!$basket) {
            $basket = $this->basketManager->create();
            $basket->setLocale($customer->getLocale());
            $basket->setCurrency($this->currencyDetector->getCurrency());
        }

        $basket->setCustomer($customer);
        $this->basketBuilder->build($basket);

        return $basket;
    }

    public function logout(Request $request, Response $response, TokenInterface $token): void
    {
        // Remove anonymous basket
        $this->session->remove($this->getSessionVarName());
    }

    /**
     * Retrieved basket associated with $customer from session.
     *
     * @return BasketInterface|null
     */
    protected function getFromSession(CustomerInterface $customer)
    {
        $basket = $this->session->get($this->getSessionVarName($customer));

        if ($basket && !$basket->isEmpty()) {
            return $basket;
        }

        return $this->session->get($this->getSessionVarName());
    }

    /**
     * Stores $basket in session.
     */
    protected function storeInSession(BasketInterface $basket): void
    {
        $this->session->set($this->getSessionVarName($basket->getCustomer()), $basket);
    }

    /**
     * Get the name of the session variable.
     *
     * @param \Adeliom\EasyShop\Component\Customer\CustomerInterface $customer
     *
     * @return string
     */
    protected function getSessionVarName(?CustomerInterface $customer = null)
    {
        if (null === $customer || null === $customer->getId()) {
            return self::SESSION_BASE_NAME.'new';
        }

        return self::SESSION_BASE_NAME.$customer->getId();
    }

    /**
     * Clears the baskets in session.
     */
    protected function clearSession(CustomerInterface $customer): void
    {
        $this->session->remove($this->getSessionVarName($customer));
        $this->session->remove($this->getSessionVarName());
    }
}
