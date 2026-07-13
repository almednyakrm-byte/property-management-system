<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MkhaznController;
use App\Repository\MkhaznRepository;
use App\Entity\Mkhazn;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestMkhazn extends TestCase
{
    private $controller;
    private $repository;
    private $router;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MkhaznRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->request = $this->createMock(Request::class);

        $this->controller = new MkhaznController($this->repository, $this->router);
    }

    public function testGetAll()
    {
        $expectedResponse = new JsonResponse(['data' => []]);
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $response = $this->controller->getAll($this->request);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testGetOne()
    {
        $id = 1;
        $expectedResponse = new JsonResponse(['data' => new Mkhazn()]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new Mkhazn());

        $response = $this->controller->getOne($this->request, ['id' => $id]);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPost()
    {
        $data = ['name' => 'test'];
        $expectedResponse = new JsonResponse(['data' => new Mkhazn()]);
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new Mkhazn($data))
            ->willReturn(new Mkhazn($data));

        $response = $this->controller->post($this->request, $data);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testPut()
    {
        $id = 1;
        $data = ['name' => 'test'];
        $expectedResponse = new JsonResponse(['data' => new Mkhazn()]);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new Mkhazn());
        $this->repository->expects($this->once())
            ->method('save')
            ->with(new Mkhazn($data))
            ->willReturn(new Mkhazn($data));

        $response = $this->controller->put($this->request, $data, ['id' => $id]);
        $this->assertEquals($expectedResponse, $response);
    }

    public function testDelete()
    {
        $id = 1;
        $expectedResponse = new JsonResponse(['message' => 'Deleted successfully']);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new Mkhazn());
        $this->repository->expects($this->once())
            ->method('remove')
            ->with(new Mkhazn());

        $response = $this->controller->delete($this->request, ['id' => $id]);
        $this->assertEquals($expectedResponse, $response);
    }
}