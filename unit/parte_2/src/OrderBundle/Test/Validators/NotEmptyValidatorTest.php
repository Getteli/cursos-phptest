<?php

namespace OrderBundle\Validators\Test;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase
{
    /**
     * Metodo para testar se o valor do validador será falso, pois a variavel passada sera vazia
     *
     * @test
     */
    public function testShouldNotBeValidWhenValueIsEmpty()
    {
        // cenario
        $emptyValue = "";
        $notEmptyValidator = new NotEmptyValidator($emptyValue);

        // ação
        $isValid = $notEmptyValidator->isValid();

        // verificação
        $this->assertFalse($isValid);
    }

    /**
     * Metodo para testar se o valor do validador será verdadeiro, pois a variavel passada conterá um valor
     *
     * @test
     */
    public function testShouldBeValidWhenValueNotIsEmpty()
    {
        // cenario
        $value = "Foo";
        $notEmptyValidator = new NotEmptyValidator($value);

        // ação
        $isValid = $notEmptyValidator->isValid();

        // verificação
        $this->assertTrue($isValid);
    }

    /**
     * metodo para testar se o valor é valido E se não é, tudo em 1 só, usando data Provider
     *
     * @test
     */
    public function testWithValueFromValidatorNotEmptyAndEmpty()
    {
        $dataProvider = [
            "" => false,
            "Foo" => true,
        ];

        foreach ($dataProvider as $value => $expectResult)
        {
            // cenario
            $notEmptyValidator = new NotEmptyValidator($value);

            // ação
            $isValid = $notEmptyValidator->isValid();

            // verificação (o que ele espera / o resultado do teste)
            $this->assertEquals($expectResult, $isValid);
        }
    }

    /**
     * metodo para testar se o valor é valido E se não é, tudo em 1 só, mas com o dataProvider explicando o resultado, vindo de outro metodo. A anotação a seguir, de dataProvider com o nome do metodo, é para indicar ao phpUnit que os parametros no metodo vem desse metodo
     *
     * @dataProvider valueProvider
     * @test
     */
    public function testIsvalid($value, $expectResult)
    {
        // cenario
        $notEmptyValidator = new NotEmptyValidator($value);

        // ação
        $isValid = $notEmptyValidator->isValid();

        // verificação (o que ele espera / o resultado do teste)
        $this->assertEquals($expectResult, $isValid);
    }

    /**
     * Data Provider com: descricao do cenario, e um array com seu valor e o seu resulttado.
     * Esse metodo precisa ser static
     *
     * @return Array
     */
    public static function valueProvider()
    {
        return [
            'ShouldBeValidWhenValueIsEmpty' => ['value' => '', 'expectedResult' => false],
            'ShouldBeValidWhenValueIsNotEmpty' => ['value' => 'foo', 'expectedResult' => true]
        ];
    }
}