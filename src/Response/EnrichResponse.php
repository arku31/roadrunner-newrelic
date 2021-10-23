<?php

declare(strict_types=1);

namespace Arku\Newrelic\Response;

use Arku\Newrelic\Transactions\TransactionDetail;
use Arku\Newrelic\Transformers\TransactionDetailTransformer;
use Arku\Newrelic\Transformers\TransactionDetailTransformerInterface;
use Psr\Http\Message\MessageInterface;

final class EnrichResponse implements EnrichResponseInterface
{
    private TransactionDetailTransformer $transformer;

    public function __construct(TransactionDetailTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    public function enrich(MessageInterface $response, TransactionDetail $transactionDetail): MessageInterface
    {
        $response =  $response->withHeader('rr_newrelic', $this->transformer->transform($transactionDetail));

        if ($segments = $transactionDetail->getSegments()) {
            $keys = $this->generateKeys(count($segments));
            $segments = array_combine($keys, $segments);
            $response = $response->withHeader('rr_newrelic_headers', implode(',', $keys));
            foreach ($segments as $key => $segment) {
                $response = $response->withHeader($key, $this->transformer->transformSegment($segment));
            }
        }
        return $response;
    }

    private function generateKeys(int $count): array
    {
        $segments = [];
        for ($i=0;$i< $count; $i++) {
            $segments[] = sprintf('nr_segment%d', $count);
        }
        return $segments;
    }
}