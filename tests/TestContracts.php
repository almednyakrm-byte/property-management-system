<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use PDOStatement;

class TestContracts extends TestCase
{
    private $pdo;
    private $contractService;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->contractService = new ContractService($this->pdo);
    }

    public function testGetContracts()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1]));

        $statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Contract 1'],
                ['id' => 2, 'name' => 'Contract 2'],
            ]);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM contracts WHERE id = :id'))
            ->willReturn($statement);

        $contracts = $this->contractService->getContracts(1);
        $this->assertCount(2, $contracts);
    }

    public function testGetContractById()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1]));

        $statement->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'name' => 'Contract 1']);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM contracts WHERE id = :id'))
            ->willReturn($statement);

        $contract = $this->contractService->getContractById(1);
        $this->assertEquals(1, $contract['id']);
    }

    public function testCreateContract()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['name' => 'New Contract']));

        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO contracts (name) VALUES (:name)'))
            ->willReturn($statement);

        $result = $this->contractService->createContract(['name' => 'New Contract']);
        $this->assertTrue($result);
    }

    public function testUpdateContract()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1, 'name' => 'Updated Contract']));

        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE contracts SET name = :name WHERE id = :id'))
            ->willReturn($statement);

        $result = $this->contractService->updateContract(1, ['name' => 'Updated Contract']);
        $this->assertTrue($result);
    }

    public function testDeleteContract()
    {
        $statement = $this->createMock(PDOStatement::class);
        $statement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1]));

        $statement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM contracts WHERE id = :id'))
            ->willReturn($statement);

        $result = $this->contractService->deleteContract(1);
        $this->assertTrue($result);
    }
}

class ContractService
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getContracts($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM contracts WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->fetchAll();
    }

    public function getContractById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM contracts WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    public function createContract($data)
    {
        $statement = $this->pdo->prepare('INSERT INTO contracts (name) VALUES (:name)');
        $statement->execute($data);
        return $statement->rowCount() > 0;
    }

    public function updateContract($id, $data)
    {
        $statement = $this->pdo->prepare('UPDATE contracts SET name = :name WHERE id = :id');
        $statement->execute(array_merge($data, ['id' => $id]));
        return $statement->rowCount() > 0;
    }

    public function deleteContract($id)
    {
        $statement = $this->pdo->prepare('DELETE FROM contracts WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->rowCount() > 0;
    }
}