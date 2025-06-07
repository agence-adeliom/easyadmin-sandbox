<?php

declare(strict_types=1);

namespace App\Tests\EasyEditorBundle\EventListener;

use Adeliom\EasyEditorBundle\EventListener\ResizeFormListener;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Forms;

final class ResizeFormListenerTest extends TestCase
{
    public function testGetSubscribedEvents(): void
    {
        $events = ResizeFormListener::getSubscribedEvents();
        self::assertArrayHasKey('form.pre_set_data', $events);
        self::assertArrayHasKey('form.pre_submit', $events);
        self::assertArrayHasKey('form.submit', $events);
    }

    public function testOnSubmitSortsByPosition(): void
    {
        $factory = Forms::createFormFactory();
        $form = $factory->createBuilder(CollectionType::class, [
            ['block_type' => TextType::class, 'position' => 2],
            ['block_type' => TextType::class, 'position' => 1],
        ], [
            'entry_type' => TextType::class,
        ])->getForm();

        $listener = new ResizeFormListener(TextType::class, [], true, true);
        $event = new FormEvent($form, $form->getData());
        $listener->onSubmit($event);

        $data = $event->getData();
        self::assertSame(1, $data[0]['position']);
        self::assertSame(2, $data[1]['position']);
    }
}
