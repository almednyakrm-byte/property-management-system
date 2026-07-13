<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\بيوتController;
use App\Repository\بيوتRepository;
use App\Entity\بيوت;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class Testبيوت extends TestCase
{
    private $controller;
    private $router;
    private $entityManager;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(بيوتRepository::class);
        $this->pdo = $this->createMock(\PDO::class);

        $this->controller = new البيوتController($this->router, $this->entityManager, $this->repository);
    }

    public function testGetAll()
    {
        $expectedResponse = ['بيوت' => ['بيت1', 'بيت2']];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $response = $this->controller->getAll();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne()
    {
        $expectedResponse = ['بيت1' => 'بيت1'];
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($expectedResponse);

        $response = $this->controller->getOne(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testCreate()
    {
        $بيت = new البيوت();
        $بيت->setName('بيت1');
        $this->repository->expects($this->once())
            ->method('save')
            ->with($بيت);

        $response = $this->controller->create($بيت);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdate()
    {
        $بيت = new البيوت();
        $بيت->setName('بيت1');
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn($بيت);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($بيت);

        $response = $this->controller->update(1, $بيت);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDelete()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 1])
            ->willReturn(new البيوت());

        $response = $this->controller->delete(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}



// App\Controller\بيوتController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use App\Repository\بيوتRepository;
use App\Entity\بيوت;
use Doctrine\ORM\EntityManagerInterface;

class البيوتController
{
    private $router;
    private $entityManager;
    private $repository;

    public function __construct(RouterInterface $router, EntityManagerInterface $entityManager, البيوتRepository $repository)
    {
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function getAll()
    {
        $بيوت = $this->repository->findAll();
        return new Response(json_encode($بيوت));
    }

    public function getOne($id)
    {
        $بيت = $this->repository->findOneBy(['id' => $id]);
        return new Response(json_encode($بيت));
    }

    public function create($بيت)
    {
        $this->repository->save($بيت);
        return new Response('', Response::HTTP_CREATED);
    }

    public function update($id, $بيت)
    {
        $بيت = $this->repository->findOneBy(['id' => $id]);
        $بيت->setName($بيت->getName());
        $this->repository->save($بيت);
        return new Response('', Response::HTTP_OK);
    }

    public function delete($id)
    {
        $بيت = $this->repository->findOneBy(['id' => $id]);
        $this->repository->remove($بيت);
        return new Response('', Response::HTTP_OK);
    }
}



// App\Repository\بيوتRepository.php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class البيوتRepository
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        return $this->entityManager->getRepository(بيوت::class)->findAll();
    }

    public function findOneBy($criteria)
    {
        return $this->entityManager->getRepository(بيوت::class)->findOneBy($criteria);
    }

    public function save($بيت)
    {
        $this->entityManager->persist($بيت);
        $this->entityManager->flush();
    }

    public function remove($بيت)
    {
        $this->entityManager->remove($بيت);
        $this->entityManager->flush();
    }
}



// App\Entity\بيوت.php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class البيوت
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}