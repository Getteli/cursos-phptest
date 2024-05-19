<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase
{
    /**
     * Metodo que verificará se o valor passado é um cartão valido, de crédito.
     *
     * @dataProvider dataProvider
     * @test
     */
    public function testCardNumberisValidCard($value, $xpectResult)
    {
        // cenario
        $cardNumberValidator = new CreditCardNumberValidator($value);

        // ação
        $isValid = $cardNumberValidator->isValid();

        // assert
        $this->assertEquals($xpectResult, $isValid);
    }

    /**
     * Data provider com o cenario e seu respectivo valor e resultado esperador
     *
     * @return Array
     */
    public static function dataProvider()
    {
        return [
            'ShouldBeValidWhenValueIsCardNumber' => ['value' => 1234567890111213, 'expectResult' => true],
            'ShouldBeNotValidWhenValueIsShortCardNumber' => ['value' => 123456789, 'expectResult' => false],
            'ShouldBeValidWhenValueIsStringCardNumber' => ['value' => '1234567890111213', 'expectResult' => true],
        ];
    }
}