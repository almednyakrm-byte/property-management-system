<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\التاريخController;
use App\Repository\التاريخRepository;
use App\Entity\التاريخ;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class Testالتاريخ extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(التاريخRepository::class);
        $this->entityManager = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $this->controller = new التاريخController($this->repository, $this->entityManager);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testGetOne(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new التاريخ());

        $response = $this->controller->getOne(1);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testGetOneNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getOne(1);
    }

    public function testCreate(): void
    {
        $expectedResponse = ['data' => []];
        $data = ['name' => 'test'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new التاريخ($data))
            ->willReturn(new التاريخ($data));

        $response = $this->controller->create($data);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testUpdate(): void
    {
        $expectedResponse = ['data' => []];
        $data = ['name' => 'test'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new التاريخ($data));

        $this->repository->expects($this->once())
            ->method('save')
            ->with(new التاريخ($data));

        $response = $this->controller->update(1, $data);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testUpdateNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $data = ['name' => 'test'];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->update(1, $data);
    }

    public function testDelete(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new التاريخ());

        $this->repository->expects($this->once())
            ->method('remove')
            ->with(new التاريخ());

        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response->toArray());
    }

    public function testDeleteNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->delete(1);
    }
}


Note: This is a basic example and you may need to adjust it according to your specific use case. Also, you should replace `App\Controller\التاريخController` and `App\Repository\التاريخRepository` with the actual namespace of your controller and repository.