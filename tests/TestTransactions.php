<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\TransactionsController;
use App\Repository\TransactionsRepository;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TestTransactions extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $router;
    private $tokenStorage;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(TransactionsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->router = $this->createMock(RouterInterface::class);
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);

        $this->controller = new TransactionsController(
            $this->repository,
            $this->entityManager,
            $this->router,
            $this->tokenStorage
        );
    }

    public function testGetTransactions(): void
    {
        $transactions = [
            new Transaction(),
            new Transaction(),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($transactions);

        $response = $this->controller->getTransactions();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetTransaction(): void
    {
        $transaction = new Transaction();

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($transaction);

        $response = $this->controller->getTransaction(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetTransactionNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->getTransaction(1);
    }

    public function testPostTransaction(): void
    {
        $transaction = new Transaction();
        $transaction->setId(1);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($transaction)
            ->willReturn($transaction);

        $request = new Request();
        $request->request->set('name', 'Transaction 1');
        $request->request->set('amount', 10.99);

        $response = $this->controller->postTransaction($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutTransaction(): void
    {
        $transaction = new Transaction();
        $transaction->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($transaction);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($transaction)
            ->willReturn($transaction);

        $request = new Request();
        $request->request->set('name', 'Transaction 1');
        $request->request->set('amount', 10.99);

        $response = $this->controller->putTransaction(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutTransactionNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->putTransaction(1, new Request());
    }

    public function testDeleteTransaction(): void
    {
        $transaction = new Transaction();
        $transaction->setId(1);

        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($transaction);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($transaction);

        $response = $this->controller->deleteTransaction(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteTransactionNotFound(): void
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);

        $this->controller->deleteTransaction(1);
    }
}


This test file covers the following scenarios:

- `testGetTransactions`: Tests the GET request to retrieve all transactions.
- `testGetTransaction`: Tests the GET request to retrieve a single transaction.
- `testGetTransactionNotFound`: Tests the GET request to retrieve a non-existent transaction.
- `testPostTransaction`: Tests the POST request to create a new transaction.
- `testPutTransaction`: Tests the PUT request to update an existing transaction.
- `testPutTransactionNotFound`: Tests the PUT request to update a non-existent transaction.
- `testDeleteTransaction`: Tests the DELETE request to delete an existing transaction.
- `testDeleteTransactionNotFound`: Tests the DELETE request to delete a non-existent transaction.

Note that this is a basic example and you may need to adjust it to fit your specific use case. Additionally, you should replace the mocked objects with real ones in a production environment.