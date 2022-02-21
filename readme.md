# Response enricher for the newrelic implementation in the roadrunner

Usage

    $transactionDetail = new TransactionDetail();
    $transactionDetail->setName('test');
    $transactionDetail->setCustomData('key', 'value');

    $segment = new Segment();
    $segment->setName('testSegment');
    $segment->setDuration('1');
    $segment->setMeta(['testmetakey' => 'testmetavalue']);

    $transactionDetail->addSegment($segment);

    $enricher = new EnrichResponse(new TransactionDetailTransformer());
    $response = $enricher->enrich($response, $transactionDetail);


## Note: Duration tracking is not available atm due to restriction of the newrelic golang library.

Transaction can be marked as ignored with

```$transactionDetail->ignoreTransaction();```

In this case, roadrunner will not send this transaction to newrelic. This is useful f.e. for some `health` endpoints

### License: MIT

See https://github.com/arku31/newrelic-roadrunner-sample for example