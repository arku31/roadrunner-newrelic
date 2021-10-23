<?php

declare(strict_types=1);
namespace Arku\Newrelic\Transformers;


use Arku\Newrelic\Transactions\SegmentInterface;
use Arku\Newrelic\Transactions\TransactionDetailInterface;

final class TransactionDetailTransformer implements TransactionDetailTransformerInterface
{
    public function transform(TransactionDetailInterface $transactionDetail): array
    {
        $transformed = [];
        foreach ($transactionDetail->getDetailsAsArray() as $key => $customParameter) {
            $transformed[] = implode(':', [strval($key), $customParameter]);
        }
        return $transformed;
    }

    public function transformSegment(SegmentInterface $segment): array
    {
        $transformed[] = implode(':', ['name', $segment->getName()]);
        $transformed[] = implode(':', ['duration', $segment->getDuration()]);
        foreach ($segment->getMeta() as $metaKey => $metaInformation) {
            $transformed[] = implode(':', [strval($metaKey), $metaInformation]);
        }
        return $transformed;
    }
}