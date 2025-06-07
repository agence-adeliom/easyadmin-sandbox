<?php
declare(strict_types=1);

namespace App\Tests\EasyConfigBundle\Twig;

use Adeliom\EasyConfigBundle\Twig\EasyConfigExtension;
use App\Tests\EasyConfigBundle\DatabaseSetupTrait;
use App\Tests\EasyConfigBundle\Fixtures\ConfigFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Twig\Environment;

class EasyConfigExtensionTest extends KernelTestCase
{
    use DatabaseSetupTrait;

    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->em = $this->bootKernelWithMemoryDatabase();
        (new ConfigFixture())->load($this->em);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->restoreDatabaseEnv();
    }

    public function testGetConfigReturnsTextValue(): void
    {
        $twig = static::getContainer()->get(Environment::class);
        /** @var EasyConfigExtension $extension */
        $extension = static::getContainer()->get(EasyConfigExtension::class);
        $result = $extension->getConfig($twig, [], 'site_name');

        self::assertSame('EasyAdmin', (string) $result);
    }

    public function testGetConfigDecodesJson(): void
    {
        $twig = static::getContainer()->get(Environment::class);
        $extension = static::getContainer()->get(EasyConfigExtension::class);
        $result = $extension->getConfig($twig, [], 'settings');

        self::assertSame(['foo' => 'bar'], $result);
    }

    public function testGetConfigReturnsArrayWhenNotDirect(): void
    {
        $twig = static::getContainer()->get(Environment::class);
        $extension = static::getContainer()->get(EasyConfigExtension::class);
        $result = $extension->getConfig($twig, [], 'site_name', false);

        self::assertSame('text', $result['type']);
        self::assertSame('EasyAdmin', $result['value']);
        self::assertSame('EasyAdmin', $result['raw_value']);
    }
}
