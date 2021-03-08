<?php

namespace IsaEken\NetGSM\Test;

use Illuminate\Support\Str;
use IsaEken\NetGSM\Sms;
use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function testVariables()
    {
        $sms = new Sms;
        $variables = [
            "variable1" => [1, 2, 3, "testing..."],
            "variable2" => [1, 2, 3, "testing..."],
            "variable3" => [1, 2, 3, "testing..."],
        ];

        foreach ($variables as $variable => $values) {
            foreach ($values as $value) {
                $var = Str::of($variable)->kebab()->ucfirst();

                $sms->{"set" . $var}($value);
                $this->assertEquals($value, $sms->{"get" . $var}());
                $this->assertEquals($value, $sms->{Str::of($variable)->kebab()});

                $sms->$var = $value;
                $this->assertEquals($value, $sms->{"get" . $var}());
                $this->assertEquals($value, $sms->{Str::of($variable)->kebab()});
            }
        }
    }
}
