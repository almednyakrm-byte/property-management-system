<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ عقارController;
use App\Repository\ عقارRepository;
use App\Service\ عقارService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testالعقار extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock( عقارRepository::class);
        $this->service = $this->createMock( عقارService::class);
        $this->controller = new عقارController($this->repository, $this->service);
    }

    public function testGetAll(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'عقار 1'],
                ['id' => 2, 'name' => 'عقار 2'],
            ]);

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['items' => [
            ['id' => 1, 'name' => 'عقار 1'],
            ['id' => 2, 'name' => 'عقار 2'],
        ]], json_decode($response->getContent(), true));
    }

    public function testGetOne(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(['id' => 1, 'name' => 'عقار 1']);

        $request = new Request();
        $response = $this->controller->getOne($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'عقار 1'], json_decode($response->getContent(), true));
    }

    public function testCreate(): void
    {
        $this->service->expects($this->once())
            ->method('create')
            ->with(['name' => 'عقار 1'])
            ->willReturn(['id' => 1, 'name' => 'عقار 1']);

        $request = new Request([], [], ['name' => 'عقار 1']);
        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'عقار 1'], json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $this->service->expects($this->once())
            ->method('update')
            ->with(1, ['name' => 'عقار 1'])
            ->willReturn(['id' => 1, 'name' => 'عقار 1']);

        $request = new Request([], [], ['name' => 'عقار 1']);
        $response = $this->controller->update($request, 1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(['id' => 1, 'name' => 'عقار 1'], json_decode($response->getContent(), true));
    }

    public function testDelete(): void
    {
        $this->service->expects($this->once())
            ->method('delete')
            ->with(1);

        $request = new Request();
        $response = $this->controller->delete($request, 1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\ عقارController.php

namespace App\Controller;

use App\Repository\ عقارRepository;
use App\Service\ عقارService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class عقارController
{
    private $repository;
    private $service;

    public function __construct( عقارRepository $repository, عقارService $service)
    {
        $this->repository = $repository;
        $this->service = $service;
    }

    public function getAll(Request $request)
    {
        $items = $this->repository->findAll();
        return new Response(json_encode(['items' => $items]), Response::HTTP_OK);
    }

    public function getOne(Request $request, $id)
    {
        $item = $this->repository->find($id);
        return new Response(json_encode($item), Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $item = $this->service->create($request->request->all());
        return new Response(json_encode($item), Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $item = $this->service->update($id, $request->request->all());
        return new Response(json_encode($item), Response::HTTP_OK);
    }

    public function delete(Request $request, $id)
    {
        $this->service->delete($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}



// App\Repository\ عقارRepository.php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class عقارRepository extends EntityRepository
{
    public function findAll()
    {
        // Implement logic to retrieve all items from database
    }

    public function find($id)
    {
        // Implement logic to retrieve item by id from database
    }
}



// App\Service\ عقارService.php

namespace App\Service;

class عقارService
{
    public function create($data)
    {
        // Implement logic to create new item
    }

    public function update($id, $data)
    {
        // Implement logic to update existing item
    }

    public function delete($id)
    {
        // Implement logic to delete item
    }
}