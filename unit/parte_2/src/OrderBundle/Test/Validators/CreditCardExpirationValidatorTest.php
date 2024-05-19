<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\TestCase;

class CreditCardExpirationValidatorTest extends TestCase
{
    /**
     * Metodo para testar se o cartao de credito tem uma data valida (maior que a atual)
     *
     * @dataProvider dataProvider
     * @test
     */
    public function testCreditCarExpiration($value, $expectResult)
    {
        // cenario
        $creditCardValidatorExpiration = new CreditCardExpirationValidator($value);        

        // ação
        $isValid = $creditCardValidatorExpiration->isValid();

        // assert
        $this->assertEquals($expectResult, $isValid);
    }

    /**
     * Data provider com o cenario e seu respectivo valor e resultado esperador
     *
     * @return Array
     */
    public static function dataProvider()
    {
        return [
            'shouldBeNotValidCreditcardWhenValueisNow' => ['value' => new \DateTime(), 'expectResult' => false],
            'shouldBeValidCreditcardWhenValueGreaterThanNow' => ['value' => new \DateTime('tomorrow'), 'expectResult' => true],
            'shouldBeNotValidCreditcardWhenValueLessThanNow' => ['value' => new \DateTime('2019-03-15'), 'expectResult' => false],
        ];
    }
}