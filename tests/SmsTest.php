<?php

namespace IsaEken\NetGSM\Test;

use Illuminate\Support\Str;
use IsaEken\NetGSM\Enums\SmsEncoding;
use IsaEken\NetGSM\Enums\SmsFilter;
use IsaEken\NetGSM\Exceptions\NotAuthorizedException;
use IsaEken\NetGSM\Sms;
use PHPUnit\Framework\TestCase;

class SmsTest extends TestCase
{
    public function testSend()
    {
        $sms = new Sms;
        $sms->setUsername("deneme");
        $sms->setPassword("deneme");
        $sms->setGsm(["123", 456, "789"]);
        $sms->setMessage("deneme");
        $sms->setHeader("deneme");
        $sms->setStartDate(new \DateTime());
        $sms->setStopDate(new \DateTime());
        $sms->setEncoding(SmsEncoding::TR);
        $sms->setFilter(SmsFilter::Allowed);
        $sms->setDealerCode("123123");
        $this->expectException(NotAuthorizedException::class);
        $sms->send();
    }
}
