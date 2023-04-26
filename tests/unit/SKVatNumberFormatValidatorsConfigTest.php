<?php

namespace rocketfellows\SKVatNumberFormatValidatorsConfig\tests\unit;

use arslanimamutdinov\ISOStandard3166\ISO3166;
use PHPUnit\Framework\TestCase;
use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface;
use rocketfellows\SKVatFormatValidator\SKVatFormatValidator;
use rocketfellows\SKVatNumberFormatValidatorsConfig\SKVatNumberFormatValidatorsConfig;

class SKVatNumberFormatValidatorsConfigTest extends TestCase
{
    public function testDefaultConfigInitialization(): void
    {
        $config = new SKVatNumberFormatValidatorsConfig();

        $actualValidators = [];

        foreach ($config->getValidators() as $validator) {
            $actualValidators[] = $validator;
        }

        $this->assertExpectedConfigCountry($config);
        $this->assertCount(1, $actualValidators);
        $this->assertInstanceOf(SKVatFormatValidator::class, $actualValidators[0]);
    }

    public function testSetDefaultConfigValidator(): void
    {
        $defaultValidator = $this->createMock(CountryVatFormatValidatorInterface::class);
        $config = new SKVatNumberFormatValidatorsConfig($defaultValidator);

        $actualValidators = [];

        foreach ($config->getValidators() as $validator) {
            $actualValidators[] = $validator;
        }

        $this->assertExpectedConfigCountry($config);
        $this->assertCount(1, $actualValidators);
        $this->assertEquals($defaultValidator, $actualValidators[0]);
    }

    private function assertExpectedConfigCountry(SKVatNumberFormatValidatorsConfig $config): void
    {
        $this->assertEquals(ISO3166::SK(), $config->getCountry());
    }
}
