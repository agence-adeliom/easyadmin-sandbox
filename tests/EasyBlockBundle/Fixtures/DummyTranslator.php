<?php
declare(strict_types=1);

namespace App\Tests\EasyBlockBundle\Fixtures;

use Symfony\Contracts\Translation\TranslatorInterface;

class DummyTranslator implements TranslatorInterface
{
    public function trans(string $id, array $parameters = [], ?string $domain = null, ?string $locale = null): string
    {
        return $id;
    }

    public function getLocale(): string
    {
        return 'en';
    }
}
