<?php

declare(strict_types=1);

namespace Arku\Newrelic\Response;

use Arku\Newrelic\Transactions\TransactionDetail;
use Arku\Newrelic\Transactions\TransactionDetailInterface;
use Arku\Newrelic\Transformers\TransactionDetailTransformer;
use Arku\Newrelic\Transformers\TransactionDetailTransformerInterface;
use Psr\Http\Message\MessageInterface;

final class EnrichResponse implements EnrichResponseInterface
{
    private const SEGMENT_PATTERN = 'nr_segment%d';

    private TransactionDetailTransformerInterface $transformer;

    public function __construct(TransactionDetailTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function enrich(MessageInterface $response, TransactionDetailInterface $transactionDetail): MessageInterface
    {
        $response =  $response->withHeader(self::MAIN_RR_HEADER, $this->transformer->transform($transactionDetail));

        if ($segments = $transactionDetail->getSegments()) {
            $keys = $this->generateKeys(count($segments));
            $segments = array_combine($keys, $segments);
            $response = $response->withHeader(self::SEGMENTS_RR_HEADER, implode(',', $keys));
            foreach ($segments as $key => $segment) {
                $response = $response->withHeader($key, $this->transformer->transformSegment($segment));
            }
        }

        if ($throwable = $transactionDetail->getThrowable()) {
            $throwableData = $this->transformer->transformThrowable($throwable);
            $response->withHeader(self::ERROR_RR_REPORTING, $throwableData);
        }

        return $response;
    }

    private function generateKeys(int $count): array
    {
        $segments = [];
        for ($i=0;$i< $count; $i++) {
            $segments[] = sprintf(self::SEGMENT_PATTERN, $count);
        }
        return $segments;
    }
}