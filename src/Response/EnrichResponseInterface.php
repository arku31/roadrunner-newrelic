<?php

namespace Arku\Newrelic\Response;

use Arku\Newrelic\Transactions\TransactionDetail;
use Psr\Http\Message\MessageInterface;

interface EnrichResponseInterface
{
    public const MAIN_RR_HEADER = 'rr_newrelic';
    public const SEGMENTS_RR_HEADER = 'rr_newrelic_headers';
    public const ERROR_RR_REPORTING = 'rr_newrelic_error';

    public function enrich(MessageInterface $response, TransactionDetail $transactionDetail): MessageInterface;
}