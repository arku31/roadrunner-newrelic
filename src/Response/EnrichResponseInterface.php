<?php

namespace Arku\Newrelic\Response;

use Arku\Newrelic\Transactions\TransactionDetail;
use Psr\Http\Message\MessageInterface;

interface EnrichResponseInterface
{
    public function enrich(MessageInterface $response, TransactionDetail $transactionDetail): MessageInterface;
}