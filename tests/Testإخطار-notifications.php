<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\إخطارController;
use App\Repository\إخطارRepository;
use App\Entity\إخطار;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TestإخطارNotifications extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(إخطارRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->controller = new إخطارController(
            $this->repository,
            $this->entityManager,
            $this->router,
            $this->tokenStorage
        );
    }

    public function testGetNotifications()
    {
        $notifications = [
            new إخطار(),
            new إخطار(),
            new إخطار(),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($notifications);

        $response = $this->controller->getNotifications();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($notifications, $response->getContent());
    }

    public function testCreateNotification()
    {
        $notification = new إخطار();
        $notification->setTitle('Test Notification');
        $notification->setMessage('This is a test notification');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($notification);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $request = new Request();
        $request->request->set('title', 'Test Notification');
        $request->request->set('message', 'This is a test notification');

        $response = $this->controller->createNotification($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($notification, $response->getContent());
    }

    public function testUpdateNotification()
    {
        $notification = new إخطار();
        $notification->setTitle('Test Notification');
        $notification->setMessage('This is a test notification');

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(إخطار::class, 1)
            ->willReturn($notification);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($notification);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $request = new Request();
        $request->request->set('title', 'Updated Test Notification');
        $request->request->set('message', 'This is an updated test notification');

        $response = $this->controller->updateNotification(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($notification, $response->getContent());
    }

    public function testDeleteNotification()
    {
        $notification = new إخطار();

        $this->entityManager->expects($this->once())
            ->method('find')
            ->with(إخطار::class, 1)
            ->willReturn($notification);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($notification);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->deleteNotification(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}



// App\Controller\إخطارController.php

namespace App\Controller;

use App\Repository\إخطارRepository;
use App\Entity\إخطار;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\ORMException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class إخطارController
{
    private $repository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    public function __construct(
        إخطارRepository $repository,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->tokenStorage = $tokenStorage;
    }

    public function getNotifications()
    {
        $notifications = $this->repository->findAll();

        return new Response(json_encode($notifications), Response::HTTP_OK);
    }

    public function createNotification(Request $request)
    {
        $notification = new إخطار();
        $notification->setTitle($request->request->get('title'));
        $notification->setMessage($request->request->get('message'));

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return new Response(json_encode($notification), Response::HTTP_CREATED);
    }

    public function updateNotification($id, Request $request)
    {
        $notification = $this->entityManager->find(إخطار::class, $id);

        if (!$notification) {
            throw new EntityNotFoundException();
        }

        $notification->setTitle($request->request->get('title'));
        $notification->setMessage($request->request->get('message'));

        $this->entityManager->persist($notification);
        $this->entityManager->flush();

        return new Response(json_encode($notification), Response::HTTP_OK);
    }

    public function deleteNotification($id)
    {
        $notification = $this->entityManager->find(إخطار::class, $id);

        if (!$notification) {
            throw new EntityNotFoundException();
        }

        $this->entityManager->remove($notification);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}