<?php
declare(strict_types=1);

namespace App\Tests\EasyFaq;

use Adeliom\EasyFaqBundle\EventListener\EntryListener;
use App\Tests\Fixtures\FaqFixtures;
use App\Tests\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class EntryListenerTest extends WebTestCase
{
    use FixturesTrait;

    private EntryListener $listener;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadFixtures([new FaqFixtures()]);
        $this->listener = new EntryListener(
            self::getContainer()->get('easy_faq.entry.repository'),
            self::getContainer()->get('easy_faq.category.repository'),
            ['root_path' => '/faq']
        );
    }

    public function testSetRequestLayout(): void
    {
        $request = Request::create('/faq/faq-category/faq-entry');
        $event = new RequestEvent(self::$kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $this->listener->setRequestLayout($event);

        self::assertTrue($request->attributes->has('_easy_faq_category'));
        self::assertTrue($request->attributes->has('_easy_faq_entry'));
    }

    public function testSetRequestLayoutRoot(): void
    {
        $request = Request::create('/faq');
        $event = new RequestEvent(self::$kernel, $request, HttpKernelInterface::MAIN_REQUEST);

        $this->listener->setRequestLayout($event);

        self::assertTrue($request->attributes->getBoolean('_easy_faq_root'));
    }
}
