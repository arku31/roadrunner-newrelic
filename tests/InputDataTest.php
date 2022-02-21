<?php

declare(strict_types=1);

namespace Arku\Tests\Newrelic;

use Arku\Newrelic\Response\EnrichResponse;
use Arku\Newrelic\Transactions\Segment;
use Arku\Newrelic\Transactions\TransactionDetail;
use Arku\Newrelic\Transformers\TransactionDetailTransformer;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class InputDataTest extends TestCase
{
    /**
     * @dataProvider getData
     * @param string $key
     * @param string $value
     */
    public function testSomeInputs(string $key, string $value)
    {
        $response = new Response();
        $transactionDetail = new TransactionDetail();
        $transactionDetail->setName('test');
        $transactionDetail->setCustomData($key, $value);

        $enricher = new EnrichResponse(new TransactionDetailTransformer());
        $response = $enricher->enrich($response, $transactionDetail);
        $headers = $response->getHeaders();

        $this->assertArrayHasKey('rr_newrelic', $headers);
        $this->assertEquals('transaction_name:test', $headers['rr_newrelic'][0]);
        $this->assertEquals($key.':'.$value, $headers['rr_newrelic'][1]);
    }

    public function getData()
    {
        return [
            ['key', 'value'],
            ['test[test]', 'test[test]'],
            ['test\n', 'test\n'],
            ['filters[term]:7167956\n\\', 'filters[term]:7167956\n\\'],
        ];
    }
}