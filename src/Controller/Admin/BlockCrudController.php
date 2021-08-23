<?php

namespace App\Controller\Admin;

use Adeliom\EasyBlockBundle\Controller\BaseBlockCrudController;
use App\Entity\Block;

class BlockCrudController extends BaseBlockCrudController
{
    public static function getEntityFqcn(): string
    {
        return Block::class;
    }
}
