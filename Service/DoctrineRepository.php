<?php

/*
 * This file is part of the ElaoAdminBundle.
 *
 * (c) 2014 Elao <contact@elao.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Elao\Bundle\AdminBundle\Service;

use Doctrine\ORM\EntityRepository;
use Elao\Bundle\AdminBundle\Behaviour\RepositoryInterface;
use Elao\Bundle\AdminBundle\Behaviour\PaginableRepositoryInterface;

/**
 * Doctrine repository
 */
class DoctrineRepository extends EntityRepository implements RepositoryInterface, PaginableRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create() {
        $className = $this->getClassName();

        return new $className;
    }

    /**
     * Persist the given model to the database
     *
     * @param Object $model
     */
    public function persist($model) {
        $this->getEntityManager()->persist($model);
        $this->getEntityManager()->flush($model);
    }

    /**
     * Remove the given model from the database
     *
     * @param Object $model
     */
    public function delete($model) {
        $this->getEntityManager()->remove($model);
        $this->getEntityManager()->flush($model);
    }

    /**
     * {@inheritdoc}
     */
    public function paginate(array $parameters = [])
    {
        $queryBuilder = $this->createQueryBuilder($this->getEntityName());

        foreach ($parameters as $attribute => $value) {
            $property = sprintf('%s.%s', $queryBuilder->getRootAlias(), $attribute);

            if ($value !== null) {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($property, sprintf(':%s', $attribute)))
                    ->setParameter($attribute, $value);
            } else {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->isNull($property));
            }
        }

        return $queryBuilder;
    }
}
