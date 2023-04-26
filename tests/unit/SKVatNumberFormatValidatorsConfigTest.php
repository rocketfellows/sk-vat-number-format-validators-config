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
        $defaultValidator = $this->createMock(CountryVatFormatValidatorInterface::class);
        $validator = $this->createMock(CountryVatFormatValidatorInterface::class);

        return [
            'default validator null, additional validators null' => [
                'defaultValidator' => null,
                'additionalValidators' => null,
                'expectedValidators' => [
                    new SKVatFormatValidator(),
                ],
            ],
            'default validator null, additional validators not null and empty' => [
                'defaultValidator' => null,
                'additionalValidators' => new CountryVatFormatValidators(),
                'expectedValidators' => [
                    new SKVatFormatValidator(),
                ],
            ],
            'default validator null, additional validators not null and not empty' => [
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
            'default validator not null, additional validators null' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => null,
                'expectedValidators' => [
                    $defaultValidator,
                ],
            ],
            'default validator not null, additional validators not null and empty' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => new CountryVatFormatValidators(),
                'expectedValidators' => [
                    $defaultValidator,
                ],
            ],
            'default validator not null, additional validators not null and not empty' => [
                'defaultValidator' => $defaultValidator,
                'additionalValidators' => new CountryVatFormatValidators(
                    $validator,
                    $validator,
                    $validator
                ),
                'expectedValidators' => [
                    $defaultValidator,
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
