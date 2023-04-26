<?php

namespace rocketfellows\SKVatNumberFormatValidatorsConfig\tests\unit;

use arslanimamutdinov\ISOStandard3166\ISO3166;
use PHPUnit\Framework\TestCase;
use rocketfellows\SKVatFormatValidator\SKVatFormatValidator;

class SKVatNumberFormatValidatorsConfigTest extends TestCase
{
    public function testDefaultConfigInitialization(): void
    {
        $config = new SKVatNumberFormatValidatorsConfig();

        $actualValidators = [];

        foreach ($config->getValidators() as $validator) {
            $actualValidators[] = $validator;
        }

        $this->assertEquals(ISO3166::SK(), $config->getCountry());
        $this->assertCount(1, $actualValidators);
        $this->assertInstanceOf(SKVatFormatValidator::class, $actualValidators[0]);
    }
}
