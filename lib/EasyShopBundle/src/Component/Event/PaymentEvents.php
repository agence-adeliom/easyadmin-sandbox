<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Event;


final class PaymentEvents
{
    public const PRE_ERROR = 'sonata.ecommerce.payment.pre_error';

    // Sent just before adding the order to the message queue
    public const POST_ERROR = 'sonata.ecommerce.payment.post_error';

    public const CONFIRMATION = 'sonata.ecommerce.payment.confirmation';

    public const PRE_CALLBACK = 'sonata.ecommerce.payment.pre_callback';

    // Sent just before adding the order to the message queue
    public const POST_CALLBACK = 'sonata.ecommerce.payment.post_callback';

    public const PRE_SENDBANK = 'sonata.ecommerce.payment.pre_sendbank';
    public const POST_SENDBANK = 'sonata.ecommerce.payment.post_sendbank';
}
