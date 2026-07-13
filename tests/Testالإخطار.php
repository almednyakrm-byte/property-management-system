<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\إخطارController;
use App\Repository\إخطارRepository;
use App\Entity\إخطار;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testالإخطار extends TestCase
{
    private $controller;
    private $repository;
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock('PDO');
        $this->repository = $this->createMock(إخطارRepository::class);
        $this->controller = new إخطارController($this->repository);
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse);

        $request = new Request();
        $response = $this->controller->getAll($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOne(): void
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse);

        $request = new Request();
        $response = $this->controller->getOne($request, $id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetOneNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $request = new Request();
        $this->controller->getOne($request, $id);
    }

    public function testCreate(): void
    {
        $expectedResponse = ['data' => []];
        $data = ['title' => 'Test Title', 'content' => 'Test Content'];
        $this->repository->expects($this->once())
            ->method('save')
            ->with($data)
            ->willReturn($expectedResponse);

        $request = new Request();
        $request->request->replace($data);
        $response = $this->controller->create($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $expectedResponse = ['data' => []];
        $id = 1;
        $data = ['title' => 'Test Title', 'content' => 'Test Content'];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse);

        $request = new Request();
        $request->request->replace($data);
        $response = $this->controller->update($request, $id);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['title' => 'Test Title', 'content' => 'Test Content'];
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(null);

        $request = new Request();
        $request->request->replace($data);
        $this->controller->update($request, $id);
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $request = new Request();
        $response = $this->controller->delete($request, $id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(null);

        $request = new Request();
        $this->controller->delete($request, $id);
    }
}