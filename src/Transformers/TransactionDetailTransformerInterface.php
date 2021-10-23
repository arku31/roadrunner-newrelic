<?php

namespace Arku\Newrelic\Transformers;

use Arku\Newrelic\Transactions\SegmentInterface;
use Arku\Newrelic\Transactions\TransactionDetail;
use Arku\Newrelic\Transactions\TransactionDetailInterface;

interface TransactionDetailTransformerInterface
{
    public function transform(TransactionDetailInterface $transactionDetail): array;

    public function transformSegment(SegmentInterface $segment): array;
}