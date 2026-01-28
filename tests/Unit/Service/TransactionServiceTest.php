<?php

declare(strict_types=1);

namespace OCA\ClubSuiteFinance\Tests\Unit\Service;

use OCA\ClubSuiteFinance\Db\Transaction;
use OCA\ClubSuiteFinance\Db\TransactionMapper;
use OCA\ClubSuiteFinance\Service\TransactionService;
use OCP\ILogger;
use PHPUnit\Framework\MockObject\MockObject;
use Test\TestCase;

class TransactionServiceTest extends TestCase {
    private TransactionService $service;
    private TransactionMapper|MockObject $mapper;
    private ILogger|MockObject $logger;

    protected function setUp(): void {
        parent::setUp();
        $this->mapper = $this->createMock(TransactionMapper::class);
        $this->logger = $this->createMock(ILogger::class);
        $this->service = new TransactionService($this->mapper, $this->logger);
    }

    public function testListTransactions(): void {
        $this->mapper->expects($this->once())
            ->method('findAll')
            ->willReturn([]);
            
        $result = $this->service->listTransactions();
        $this->assertIsArray($result);
    }

    public function testCreateTransaction(): void {
        $this->mapper->expects($this->once())
            ->method('insert')
            ->willReturn(new Transaction());
        
        $this->logger->expects($this->once())
             ->method('info');

        $transaction = $this->service->createTransaction(1, 100.50, '2023-01-01', 2, null, 'Test');
        $this->assertInstanceOf(Transaction::class, $transaction);
    }
}
