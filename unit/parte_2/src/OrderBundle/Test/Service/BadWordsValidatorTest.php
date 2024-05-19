<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;
use OrderBundle\Test\Service\BadWordsRepositoryStub;

class BadWordsValidatorTest extends TestCase
{
    /**
     * Metodo criado para testar se um texto possui palavrao
     *
     * @dataProvider badWordsDataProvider
     * @test
     */
    public function testHasBadWords($text, $expectedResult)
    {
        // cenario
        // esse metodo que foi instanciado, é um stub, um duble, criado ja fixo com o que precisamos para o teste (de forma manual)
        // $badWordsRepository = new BadWordsRepositoryStub();

        // stub usando o phpunit
        // metodo para criar um duble, usa o createMock e passa a classe completa como parametro
        $badWordsRepository = $this->createMock(BadWordsRepository::class);
        // cria o metodo dentro desse duble, passando o nome do metodo e o que ele deve retornar
        $badWordsRepository->method('findAllAsArray')
        ->willReturn(['bobo','chule','besta']);

        $badWordsValidator = new BadWordsValidator($badWordsRepository);

        // ação
        $hasbadWord = $badWordsValidator->hasBadWords($text);

        // assert
        $this->assertEquals($expectedResult, $hasbadWord);
    }

    /**
     * Data provider com o cenario e seu respectivo valor e resultado esperador
     *
     * @return Array
     */
    public static function badWordsDataProvider()
    {
        return [
            'shouldBeTrueWhenTextHasBadWords' => ['text' => 'Seu restaurante fede a chule', 'expectedResult' => true],
            'shouldBeFalseWhenTextHasNoBadWords' => ['text' => 'ola mundo', 'expectedResult' => false],
            'shouldBeFalseWhenTextHasEmpty' => ['text' => '', 'expectedResult' => false],
        ];
    }
}