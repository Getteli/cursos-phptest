<?php

namespace OrderBundle\Test\Entity;

use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{
    /**
     * Metodo para testar se o customer tem liberação para criar pedido
     *
     * @dataProvider customerAllowerDataProvider
     * @test
     */
    public function testCustomercanAllowToOrder($isActive, $isBlocked, $expectResult)
    {
        // cenario
        $customer = new Customer(
            $isActive,
            $isBlocked,
            'Cliente Teste',
            '21965178849'
        );

        // ação
        $isAllow = $customer->isAllowedToOrder();

        // assert
        $this->assertEquals($expectResult, $isAllow);
    }

    /**
     * Data provider com o cenario e seu respectivo valor e resultado esperador
     *
     * @return Array
     */
    public static function customerAllowerDataProvider()
    {
        return [
            'ShouldBeAllowedWhenCustomerIsActiveAndNotBlocked' => ['isActive' => true, 'isBlocked' => false, 'expectResult' => true],
            'ShouldBeNotAllowedWhenCustomerIsNotActiveAndNotBlocked' => ['isActive' => false, 'isBlocked' => false, 'expectResult' => false],
            'ShouldBeNotAllowedWhenCustomerIsNotActiveAndBlocked' => ['isActive' => false, 'isBlocked' => true, 'expectResult' => false],
        ];
    }
}