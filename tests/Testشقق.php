<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ShqafController;
use App\Repository\ShqafRepository;
use App\Entity\Shqaf;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class TestShqaf extends TestCase
{
    private $shqafController;
    private $shqafRepository;
    private $router;

    protected function setUp(): void
    {
        $this->shqafRepository = $this->createMock(ShqafRepository::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->shqafController = new ShqafController($this->shqafRepository, $this->router);
    }

    public function testGetAllShqaf()
    {
        $this->shqafRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Shqaf()]);

        $request = new Request();
        $response = $this->shqafController->getAllShqaf($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateShqaf()
    {
        $shqaf = new Shqaf();
        $shqaf->setId(1);
        $shqaf->setName('Test Shqaf');

        $this->shqafRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($shqaf));

        $request = new Request();
        $request->request->set('name', 'Test Shqaf');
        $response = $this->shqafController->createShqaf($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateShqaf()
    {
        $shqaf = new Shqaf();
        $shqaf->setId(1);
        $shqaf->setName('Test Shqaf');

        $this->shqafRepository->expects($this->once())
            ->method('find')
            ->with($this->equalTo(1))
            ->willReturn($shqaf);

        $this->shqafRepository->expects($this->once())
            ->method('save')
            ->with($this->equalTo($shqaf));

        $request = new Request();
        $request->request->set('name', 'Updated Test Shqaf');
        $response = $this->shqafController->updateShqaf($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteShqaf()
    {
        $shqaf = new Shqaf();
        $shqaf->setId(1);
        $shqaf->setName('Test Shqaf');

        $this->shqafRepository->expects($this->once())
            ->method('find')
            ->with($this->equalTo(1))
            ->willReturn($shqaf);

        $this->shqafRepository->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($shqaf));

        $request = new Request();
        $response = $this->shqafController->deleteShqaf($request, 1);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  **Get All Shqaf**: Tests the `getAllShqaf` method by mocking the `findAll` method of the `ShqafRepository` to return a list of `Shqaf` objects. It verifies that the method returns a `Response` object with a status code of 200 (OK).
2.  **Create Shqaf**: Tests the `createShqaf` method by mocking the `save` method of the `ShqafRepository` to save a new `Shqaf` object. It verifies that the method returns a `Response` object with a status code of 201 (Created).
3.  **Update Shqaf**: Tests the `updateShqaf` method by mocking the `find` method of the `ShqafRepository` to return a `Shqaf` object and then mocking the `save` method to save the updated `Shqaf` object. It verifies that the method returns a `Response` object with a status code of 200 (OK).
4.  **Delete Shqaf**: Tests the `deleteShqaf` method by mocking the `find` method of the `ShqafRepository` to return a `Shqaf` object and then mocking the `remove` method to remove the `Shqaf` object. It verifies that the method returns a `Response` object with a status code of 204 (No Content).