<?php

declare(strict_types=1);

namespace App\Tests;

use App\Payments;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;
use PDOStatement;

class TestPayments extends TestCase
{
    private Payments $payments;
    private MockObject $pdo;
    private MockObject $stmt;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->stmt = $this->createMock(PDOStatement::class);
        $this->payments = new Payments($this->pdo);
    }

    public function testGetPayments(): void
    {
        $this->stmt->expects($this->once())
            ->method('execute')
            ->with([]);

        $this->stmt->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'amount' => 100.0],
                ['id' => 2, 'amount' => 200.0],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM payments')
            ->willReturn($this->stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->getPayments($request, $response);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
    }

    public function testCreatePayment(): void
    {
        $data = ['amount' => 100.0];

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with($data);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO payments (amount) VALUES (:amount)')
            ->willReturn($this->stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->createPayment($request, $response);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('amount', $result);
    }

    public function testUpdatePayment(): void
    {
        $id = 1;
        $data = ['amount' => 200.0];

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with($data);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE payments SET amount = :amount WHERE id = :id')
            ->willReturn($this->stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn($data);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->updatePayment($request, $response);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('id', $result);
        $this->assertArrayHasKey('amount', $result);
    }

    public function testDeletePayment(): void
    {
        $id = 1;

        $this->stmt->expects($this->once())
            ->method('execute')
            ->with(['id' => $id]);

        $this->stmt->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM payments WHERE id = :id')
            ->willReturn($this->stmt);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with('id')
            ->willReturn($id);

        $response = $this->createMock(ResponseInterface::class);

        $result = $this->payments->deletePayment($request, $response);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('message', $result);
    }
}