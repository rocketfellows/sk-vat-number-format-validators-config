<?php

namespace rocketfellows\SKVatNumberFormatValidatorsConfig\tests\unit;

use arslanimamutdinov\ISOStandard3166\ISO3166;
use PHPUnit\Framework\TestCase;
use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidatorInterface;
use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidators;
use rocketfellows\CountryVatNumberFormatValidatorsConfig\CountryVatNumberFormatValidatorsConfigInterface;
use rocketfellows\SKVatFormatValidator\SKVatFormatValidator;
use rocketfellows\SKVatNumberFormatValidatorsConfig\SKVatNumberFormatValidatorsConfig;

/**
 * TODO: added tests or improve
 */
class SKVatNumberFormatValidatorsConfigTest extends TestCase
{
    private const EXPECTED_CONFIG_DEFAULT_VALIDATOR_CLASS = SKVatFormatValidator::class;

    public function testDefaultConfigInitialization(): void
    {
        $config = new SKVatNumberFormatValidatorsConfig();

        $actualValidators = [];

        foreach ($config->getValidators() as $validator) {
            $actualValidators[] = $validator;
        }

        $this->assertExpectedConfigCountry($config);
        $this->assertCount(1, $actualValidators);
        $this->assertInstanceOf(self::EXPECTED_CONFIG_DEFAULT_VALIDATOR_CLASS, $actualValidators[0]);
    }

    /**
     * @dataProvider getConfigValidators
     * @param CountryVatFormatValidatorInterface|null $defaultValidator
     * @param CountryVatFormatValidators|null $additionalValidators
     * @param CountryVatFormatValidatorInterface[] $expectedValidators
     */
    public function testConfigureConfigValidators(
        ?CountryVatFormatValidatorInterface $defaultValidator,
        ?CountryVatFormatValidators $additionalValidators,
        array $expectedValidators
    ): void {
        $config = new SKVatNumberFormatValidatorsConfig($defaultValidator, $additionalValidators);

        $actualValidators = [];

        foreach ($config->getValidators() as $validator) {
            $actualValidators[] = $validator;
        }

        $this->assertExpectedConfigCountry($config);
        $this->assertEquals($expectedValidators, $actualValidators);
    }

    public function getConfigValidators(): array
    {
        $validator = $this->createMock(CountryVatFormatValidatorInterface::class);

        return [
            'defaultValidator null and additional validators null' => [
                'defaultValidator' => null,
                'additionalValidators' => null,
                'expectedValidators' => [
                    new SKVatFormatValidator(),
                ],
            ],
            'defaultValidator not null and additional validators null' => [
                'defaultValidator' => $validator,
                'additionalValidators' => null,
                'expectedValidators' => [
                    $validator,
                ],
            ],
            'defaultValidator null and additional validators not null and empty' => [
                'defaultValidator' => null,
                'additionalValidators' => new CountryVatFormatValidators(),
                'expectedValidators' => [
                    new SKVatFormatValidator(),
                ],
            ],
            'defaultValidator null and additional validators not null and not empty' => [
                'defaultValidator' => null,
                'additionalValidators' => new CountryVatFormatValidators(
                    $validator,
                    $validator,
                    $validator
                ),
                'expectedValidators' => [
                    new SKVatFormatValidator(),
                    $validator,
                    $validator,
                    $validator
                ],
            ],
        ];
    }

    private function assertExpectedConfigCountry(CountryVatNumberFormatValidatorsConfigInterface $config): void
    {
        $this->assertEquals(ISO3166::SK(), $config->getCountry());
    }
}
