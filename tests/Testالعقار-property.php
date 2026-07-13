<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use PDO;
use PDOStatement;

class TestالعقارProperty extends TestCase
{
    private MockObject $pdo;
    private MockObject $pdoStatement;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->pdoStatement = $this->createMock(PDOStatement::class);
    }

    public function testGetProperties(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->pdo
            ->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM properties')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Property 1'],
                ['id' => 2, 'name' => 'Property 2'],
            ]);

        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $stream
            ->expects($this->once())
            ->method('write')
            ->with(json_encode([
                ['id' => 1, 'name' => 'Property 1'],
                ['id' => 2, 'name' => 'Property 2'],
            ]));

        $propertyModule = new PropertyModule($this->pdo);
        $propertyModule->getProperties($request, $response);
    }

    public function testCreateProperty(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'New Property']);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO properties (name) VALUES (:name)')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'New Property');

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(201);

        $propertyModule = new PropertyModule($this->pdo);
        $propertyModule->createProperty($request, $response);
    }

    public function testUpdateProperty(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $request
            ->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'Updated Property']);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('UPDATE properties SET name = :name WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':name', 'Updated Property');

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $propertyModule = new PropertyModule($this->pdo);
        $propertyModule->updateProperty($request, $response, ['id' => 1]);
    }

    public function testDeleteProperty(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $stream = $this->createMock(StreamInterface::class);

        $this->pdo
            ->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM properties WHERE id = :id')
            ->willReturn($this->pdoStatement);

        $this->pdoStatement
            ->expects($this->once())
            ->method('bindParam')
            ->with(':id', 1);

        $this->pdoStatement
            ->expects($this->once())
            ->method('execute')
            ->willReturn(true);

        $response
            ->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(204);

        $propertyModule = new PropertyModule($this->pdo);
        $propertyModule->deleteProperty($request, $response, ['id' => 1]);
    }
}