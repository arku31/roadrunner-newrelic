<?php

namespace Arku\Newrelic\Transactions;

interface TransactionDetailInterface
{
    public function setName(string $name): TransactionDetailInterface;

    public function setCustomData(string $key, string $value): TransactionDetailInterface;

    /**
     * @return string[]
     */
    public function getDetailsAsArray(): array;

    public function addSegment(SegmentInterface $segment): self;

    public function getSegments(): array;
}