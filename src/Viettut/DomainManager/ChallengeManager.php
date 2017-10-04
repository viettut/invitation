<?php
/**
 * Created by PhpStorm.
 * User: giang
 * Date: 10/21/15
 * Time: 10:07 PM
 */

namespace Viettut\DomainManager;


use Doctrine\ORM\EntityManagerInterface;
use ReflectionClass;
use Viettut\Exception\InvalidArgumentException;
use Viettut\Model\Core\ChallengeInterface;
use Viettut\Model\ModelInterface;
use Viettut\Model\User\UserEntityInterface;
use Viettut\Repository\Core\ChallengeRepositoryInterface;

class ChallengeManager implements ChallengeManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    protected $repository;

    public function __construct(EntityManagerInterface $em, ChallengeRepositoryInterface $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * Should take an object instance or string class name
     * Should return true if the supplied entity object or class is supported by this manager
     *
     * @param ModelInterface|string $entity
     * @return bool
     */
    public function supportsEntity($entity)
    {
        return is_subclass_of($entity, ChallengeInterface::class);
    }

    /**
     * @param ModelInterface $entity
     * @return void
     */
    public function save(ModelInterface $entity)
    {
        if (!$entity instanceof ChallengeInterface) {
            throw new InvalidArgumentException('expect an ChallengeInterface object');
        }

        $this->em->persist($entity);
        $this->em->flush();
    }

    /**
     * @param ModelInterface $entity
     * @return void
     */
    public function delete(ModelInterface $entity)
    {
        if (!$entity instanceof ChallengeInterface) {
            throw new InvalidArgumentException('expect an ChallengeInterface object');
        }

        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @return ModelInterface
     */
    public function createNew()
    {
        $entity = new ReflectionClass($this->repository->getClassName());
        return $entity->newInstance();
    }

    /**
     * @param int $id
     * @return ModelInterface|null
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return ModelInterface[]
     */
    public function all($limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria = [], $orderBy = null, $limit, $offset);
    }

    public function getChallengeForUser(UserEntityInterface $user, $limit = null, $offset = null)
    {
        return $this->repository->getChallengeByUser($user, $published = true, $limit, $offset);
    }
}