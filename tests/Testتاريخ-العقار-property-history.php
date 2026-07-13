<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\PropertyHistoryController;
use App\Repository\PropertyHistoryRepository;
use App\Entity\PropertyHistory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class Testتاريخ-العقار-property-history.php extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(PropertyHistoryRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->controller = new PropertyHistoryController($this->entityManager);
    }

    public function testGetPropertyHistory()
    {
        $propertyHistory = new PropertyHistory();
        $propertyHistory->setId(1);
        $propertyHistory->setDate('2022-01-01');
        $propertyHistory->setDescription('Test description');

        $this->repository->method('findAll')->willReturn([$propertyHistory]);

        $request = new Request();
        $response = $this->controller->getPropertyHistory($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetPropertyHistoryById()
    {
        $propertyHistory = new PropertyHistory();
        $propertyHistory->setId(1);
        $propertyHistory->setDate('2022-01-01');
        $propertyHistory->setDescription('Test description');

        $this->repository->method('find')->willReturn($propertyHistory);

        $request = new Request();
        $request->attributes->set('id', 1);
        $response = $this->controller->getPropertyHistoryById($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreatePropertyHistory()
    {
        $propertyHistory = new PropertyHistory();
        $propertyHistory->setId(1);
        $propertyHistory->setDate('2022-01-01');
        $propertyHistory->setDescription('Test description');

        $this->repository->method('save')->willReturn($propertyHistory);

        $request = new Request();
        $request->request->set('date', '2022-01-01');
        $request->request->set('description', 'Test description');
        $response = $this->controller->createPropertyHistory($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdatePropertyHistory()
    {
        $propertyHistory = new PropertyHistory();
        $propertyHistory->setId(1);
        $propertyHistory->setDate('2022-01-01');
        $propertyHistory->setDescription('Test description');

        $this->repository->method('find')->willReturn($propertyHistory);
        $this->repository->method('save')->willReturn($propertyHistory);

        $request = new Request();
        $request->attributes->set('id', 1);
        $request->request->set('date', '2022-01-02');
        $request->request->set('description', 'Updated description');
        $response = $this->controller->updatePropertyHistory($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeletePropertyHistory()
    {
        $propertyHistory = new PropertyHistory();
        $propertyHistory->setId(1);
        $propertyHistory->setDate('2022-01-01');
        $propertyHistory->setDescription('Test description');

        $this->repository->method('find')->willReturn($propertyHistory);
        $this->repository->method('remove')->willReturn($propertyHistory);

        $request = new Request();
        $request->attributes->set('id', 1);
        $response = $this->controller->deletePropertyHistory($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}