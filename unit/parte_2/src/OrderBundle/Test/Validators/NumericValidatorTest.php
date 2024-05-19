<?php 

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase
{
    /**
     * Metodo que vai verificar se o validador esta correto com os valores, se o valor é numerico ou nao
     *
     * @dataProvider dataProvider
     * @test
     */
    public function TestValidatorIsNumeric($value,$expectResult)
    {
        // cenario
        $validatorIsNumeric = new NumericValidator($value);

        // ação
        $isValid = $validatorIsNumeric->isValid();

        // assert
        $this->assertEquals($expectResult, $isValid);
    }

    /**
     * Data provider com os cenarios e seus respectivos valores e resultados esperados
     *
     * @return Array
     */
    public static function dataProvider()
    {
        return [
            'ShouldBeValidWhenValueIsNumeric' => ['value' => 5, 'expectResult' => true],
            'ShouldBeNotValidWhenValueIsNotNumeric' => ['value' => 'string', 'expectResult' => false],
            'ShouldBeValidWhenValueIsNumericString' => ['value' => '3', 'expectResult' => true],
            'ShouldBeNotValidWhenValueIsEmpty' => ['value' => '', 'expectResult' => false],
        ];
    }
}