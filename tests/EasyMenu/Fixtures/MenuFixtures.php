<?php
declare(strict_types=1);

namespace App\Tests\EasyMenu\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\EasyMenu\Menu;
use App\Entity\EasyMenu\MenuItem;
use Adeliom\EasyCommonBundle\Enum\ThreeStateStatusEnum;

class MenuFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $menu = new Menu();
        $menu->setCode('main');
        $menu->setName('Main menu');
        $menu->setStatus(true);

        $item = new MenuItem();
        $item->setName('Home');
        $item->setUrl('/');
        $item->setMenu($menu);
        $item->setState(ThreeStateStatusEnum::PUBLISHED);
        $menu->addItem($item);

        $manager->persist($menu);
        $manager->persist($item);
        $manager->flush();
    }
}
