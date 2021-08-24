<?php

declare(strict_types=1);



namespace Adeliom\EasyShop\Component\Product;

use Doctrine\Persistence\ManagerRegistry;
use Adeliom\EasyShop\Exporter\Source\DoctrineDBALConnectionSourceIterator;
use Adeliom\EasyShop\Exporter\Source\SourceIteratorInterface;
use Adeliom\EasyShop\Exporter\Source\SymfonySitemapSourceIterator;
use Symfony\Component\Routing\RouterInterface;


class SeoProductIterator implements SourceIteratorInterface
{
    /**
     * @var SymfonySitemapSourceIterator
     */
    protected $iterator;

    /**
     * @param string $class
     * @param string $routeName
     */
    public function __construct(ManagerRegistry $registry, $class, RouterInterface $router, $routeName)
    {
        $tableName = $registry->getManager()->getClassMetadata($class)->table['name'];

        $dql = "SELECT p.id as productId, p.slug as slug,  p.updated_at as lastmod, 'weekly' as changefreq, '0.5' as priority "
            .'FROM '.$tableName.' p '
            .'WHERE p.enabled = 1';

        $source = new DoctrineDBALConnectionSourceIterator($registry->getConnection(), $dql);

        $this->iterator = new SymfonySitemapSourceIterator($source, $router, $routeName, ['productId' => null, 'slug' => null]);
    }

    public function current()
    {
        return $this->iterator->current();
    }

    public function next()
    {
        return $this->iterator->next();
    }

    public function key()
    {
        return $this->iterator->key();
    }

    public function valid()
    {
        return $this->iterator->valid();
    }

    public function rewind()
    {
        return $this->iterator->rewind();
    }
}
