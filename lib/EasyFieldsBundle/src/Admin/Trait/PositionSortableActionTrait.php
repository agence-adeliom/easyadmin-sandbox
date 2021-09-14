<?php

namespace Adeliom\EasyFieldsBundle\Admin\Trait;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait PositionSortableActionTrait {

    public function sortPositionAction(AdminContext $context): Response
    {
        list($leftPrimaryKeyName, $leftPrimaryKeyValue, $leftParentProperty, $leftPositionProperty) = $context->getRequest()->get('l') ? explode(':', $context->getRequest()->get('l')) : [null, null, null, null];
        list($rightPrimaryKeyName, $rightPrimaryKeyValue, $rightParentProperty, $rightPositionProperty) = $context->getRequest()->get('r') ? explode(':', $context->getRequest()->get('r')) : [null, null, null, null];
        list($primaryKeyName, $primaryKeyValue, $parentProperty, $positionProperty) = $context->getRequest()->get('c') ? explode(':', $context->getRequest()->get('c')) : [null, null, null, null];

        /**
         * @var ContainerInterface $container
         */
        $container = $this->container;
        $leftEntity = $rightEntity = $entity = null;

        $tableName = $container->get("doctrine")->getManager()->getClassMetadata($context->getEntity()->getFqcn() )->getTableName();
        $parentField = $container->get("doctrine")->getManager()->getClassMetadata($context->getEntity()->getFqcn() )->getFieldNames();


        if ( !empty($leftPrimaryKeyValue) &&  !empty($leftParentProperty) &&  !empty($leftPositionProperty) ) {

            $leftEntity = $container->get("doctrine")->getRepository( $context->getEntity()->getFqcn() )->find($leftPrimaryKeyValue);

        }

        if ( !empty($rightPrimaryKeyValue) &&  !empty($rightParentProperty) &&  !empty($rightPositionProperty) ) {

            $rightEntity = $container->get("doctrine")->getRepository( $context->getEntity()->getFqcn() )->find($rightPrimaryKeyValue);

        }

        if ( !empty($primaryKeyValue) &&  !empty($parentProperty) &&  !empty($positionProperty) ) {

            $entity = $container->get("doctrine")->getRepository( $context->getEntity()->getFqcn() )->find($primaryKeyValue);

        }

        $container->get("doctrine")->getRepository( $context->getEntity()->getFqcn() )->recover();

        if (!empty($leftEntity)) {
            try {
                if ($leftEntity->{'get' . ucFirst($parentProperty)}() !== $entity->{'get' . ucFirst($parentProperty)}()) {
                    $container->get("doctrine")->getRepository( $context->getEntity()->getFqcn() )->persistAsFirstChildOf($entity, $leftEntity);
                } else {
                    $container->get("doctrine")->getRepository( $context->getEntity()->getFqcn() )->persistAsNextSiblingOf($entity, $leftEntity);
                }
            } catch (\Exception $exception) {
                $container->get("doctrine")->getRepository( $context->getEntity()->getFqcn() )->persistAsFirstChildOf($entity, $leftEntity);
            }
        } else if (!empty($rightEntity)) {
            try {
                $container->get("doctrine")->getRepository($context->getEntity()->getFqcn())->persistAsPrevSiblingOf($entity, $rightEntity);
            } catch (\Exception $exception) {
//                $entity->{'set' . ucFirst($positionProperty)}($rightPosition - 1);
                $container->get("doctrine")->getRepository( $context->getEntity()->getFqcn() )->persistAsNextSiblingOf($rightEntity, $entity);
            }
        }

        /**
         * @var Doctrine\Bundle\DoctrineBundle\Registry $doctrine
         */
        $doctrine = $container->get("doctrine");
        $doctrine->getManagerForClass( $context->getEntity()->getFqcn() )->flush();
//
//        if (!empty($leftEntity)) {
//            $leftParent = null;
//            $leftPosition = 0;
//            $leftPrimaryKeyName = $primaryKeyName;
//            $leftPrimaryKeyValue = $primaryKeyValue;
//        }
//
//        if (($leftParent !== $rightParent) || (($leftParent === null && $rightParent === null))) {
//            $query = $container->get("doctrine")->getConnection()->prepare('UPDATE '. $tableName .' SET parent_id = ?, position = ? WHERE ' . $leftPrimaryKeyName . ' = ? ');
//            $query->bindValue(1, $leftParent ? $leftParent->getId() : null);
//            $query->bindValue(2, $leftPosition);
//            $query->bindValue(3, $leftPrimaryKeyValue);
//            $query->execute();
//
//            $query = $container->get("doctrine")->getConnection()->prepare('UPDATE '. $tableName .' SET position = position + 1 WHERE ' . ($leftParent ? 'parent_id = :parent' : 'parent_id is null') . ' and position >= :position ');
//            if ($leftParent) {
//                $query->bindParam('parent', $leftParent ? $leftParent->getId() : null);
//            }
//            $query->bindParam('position', $leftPosition);
//            $query->execute();
//        }

        return new JsonResponse(['redirectTo' => null]);
    }

}
