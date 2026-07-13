<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\PropertiesController;
use App\Repository\PropertiesRepository;
use App\Entity\Properties;
use App\Service\PropertiesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Paginator\PaginationInterface;
use Symfony\Component\Paginator\PaginatorInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;

class TestProperties extends TestCase
{
    private $propertiesController;
    private $propertiesRepository;
    private $propertiesService;
    private $request;
    private $mockPDO;

    public function setUp(): void
    {
        $this->mockPDO = $this->createMock('Doctrine\DBAL\Driver\Connection');
        $this->propertiesRepository = $this->createMock(PropertiesRepository::class);
        $this->propertiesService = $this->createMock(PropertiesService::class);
        $this->request = $this->createMock(Request::class);
        $this->propertiesController = new PropertiesController($this->propertiesRepository, $this->propertiesService);
    }

    public function testGetProperties()
    {
        $this->mockPDO->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Property 1'],
                ['id' => 2, 'name' => 'Property 2'],
            ]);

        $this->propertiesRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Properties(1, 'Property 1'),
                new Properties(2, 'Property 2'),
            ]);

        $response = $this->propertiesController->getProperties($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetProperty()
    {
        $this->mockPDO->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Property 1']);

        $this->propertiesRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(new Properties(1, 'Property 1'));

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => 1]);

        $response = $this->propertiesController->getProperty($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetPropertyNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->mockPDO->expects($this->once())
            ->method('fetch')
            ->willReturn(null);

        $this->propertiesRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => 1]);

        $this->propertiesController->getProperty($this->request);
    }

    public function testCreateProperty()
    {
        $this->mockPDO->expects($this->once())
            ->method('insert')
            ->with('properties', ['name' => 'Property 1']);

        $this->propertiesService->expects($this->once())
            ->method('createProperty')
            ->with('Property 1')
            ->willReturn(new Properties(1, 'Property 1'));

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Property 1']);

        $response = $this->propertiesController->createProperty($this->request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateProperty()
    {
        $this->mockPDO->expects($this->once())
            ->method('update')
            ->with('properties', ['name' => 'Property 1'], ['id' => 1]);

        $this->propertiesService->expects($this->once())
            ->method('updateProperty')
            ->with(1, 'Property 1')
            ->willReturn(new Properties(1, 'Property 1'));

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => 1]);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'Property 1']);

        $response = $this->propertiesController->updateProperty($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteProperty()
    {
        $this->mockPDO->expects($this->once())
            ->method('delete')
            ->with('properties', ['id' => 1]);

        $this->propertiesService->expects($this->once())
            ->method('deleteProperty')
            ->with(1)
            ->willReturn(true);

        $this->request->expects($this->once())
            ->method('attributes')
            ->willReturn(['id' => 1]);

        $response = $this->propertiesController->deleteProperty($this->request);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// PropertiesController.php
namespace App\Controller;

use App\Repository\PropertiesRepository;
use App\Service\PropertiesService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PropertiesController
{
    private $propertiesRepository;
    private $propertiesService;

    public function __construct(PropertiesRepository $propertiesRepository, PropertiesService $propertiesService)
    {
        $this->propertiesRepository = $propertiesRepository;
        $this->propertiesService = $propertiesService;
    }

    public function getProperties(Request $request)
    {
        return new Response($this->propertiesRepository->findAll());
    }

    public function getProperty(Request $request)
    {
        $id = $request->attributes->get('id');
        return new Response($this->propertiesRepository->find($id));
    }

    public function createProperty(Request $request)
    {
        $name = $request->request->get('name');
        $property = $this->propertiesService->createProperty($name);
        $this->propertiesRepository->save($property);
        return new Response('', Response::HTTP_CREATED);
    }

    public function updateProperty(Request $request)
    {
        $id = $request->attributes->get('id');
        $name = $request->request->get('name');
        $property = $this->propertiesService->updateProperty($id, $name);
        $this->propertiesRepository->save($property);
        return new Response('', Response::HTTP_OK);
    }

    public function deleteProperty(Request $request)
    {
        $id = $request->attributes->get('id');
        $this->propertiesService->deleteProperty($id);
        return new Response('', Response::HTTP_NO_CONTENT);
    }
}