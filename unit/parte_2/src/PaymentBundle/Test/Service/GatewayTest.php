<?php

namespace PaymentBundle\Test\Service;

use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase
{
    /**
     * Metodo para testar o retorno da autenticacao do gateway
     *
     * @test
     */
    public function testShoudNotPayWhenAuthenticationFail()
    {
        // cenario
        // cria os dubles
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
        ->will($this->returnCallback(
            function($method, $address, $body)
            {
                return $this->fakeHttpClientSend($method, $address, $body);
            }
        ));
        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, "usuario@fail", "123456");

        // ação
        $pay = $gateway->pay(
            "duble teste",
            5173998314083754,
            new \DateTime('now'),
            100
        );

        // assert
        $this->assertEquals(false, $pay);
    }

    /**
     * Metodo para testar o retorno da autenticacao do gateway com o mock retornando um metodo de mapeamento com arrays
     *
     * @test
     */
    public function testShoudNotPayWhenAuthenticationFailReturnMock()
    {
        // cenario
        // cria os dubles
        $httpClient = $this->createMock(HttpClientInterface::class);

        // array de arrays
        $map =[
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => 'usuario@teste',
                    'password' => '123456',
                ],
                null
            ],
        ];
        $httpClient->method('send')
        // garantir q ele ta sendo executado = agora vira MOCK
        ->expects($this->once())
        ->will($this->returnValueMap($map));
        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, "usuario@fail", "123456");

        // ação
        $pay = $gateway->pay(
            "duble teste",
            5173998314083754,
            new \DateTime('now'),
            100
        );

        // assert
        $this->assertEquals(false, $pay);
    }

    /**
     * Metodo para testar o retorno do gateway com mock retornando um metodo de mapeamento...
     *
     * @test
     */
    public function testShoudNotPayWhenFailOnGatewayReturnMock()
    {
        // cenario
        // cria os dubles
        $httpClient = $this->createMock(HttpClientInterface::class);
        // array de arrays
        $map =[
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => 'usuario@teste',
                    'password' => '123456',
                ],
                '123140-54354t'
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => "duble teste",
                    'credit_card_number' => 5173998314083754,
                    'validity' => new \DateTime('now'),
                    'value' => 100,
                    'token' => '123140-54354t'
                ],
                false
            ],
        ];
        $httpClient->method('send')
        // garantir q ele ta sendo executado = agora vira MOCK
        ->expects($this->atLeast(2))
        ->will($this->returnValueMap($map));

        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, "usuario@teste", "123456");

        // ação
        $pay = $gateway->pay(
            "duble teste",
            5173998314083754,
            new \DateTime('now'),
            100
        );

        // assert
        $this->assertEquals(false, $pay);
    }
    /**
     * Metodo para testar o retorno do gateway
     *
     * @test
     */
    public function testShoudNotPayWhenFailOnGateway()
    {
        // cenario
        // cria os dubles
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
        ->will($this->returnCallback(
            function($method, $address, $body)
            {
                return $this->fakeHttpClientSend($method, $address, $body);
            }
        ));
        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, "usuario@teste", "123456");

        // ação
        $pay = $gateway->pay(
            "duble teste",
            5173998314083754,
            new \DateTime('now'),
            100
        );

        // assert
        $this->assertEquals(false, $pay);
    }

    /**
     * Metodo para testar o retorno do gateway com o pagamento funcionando
     *
     * @test
     */
    public function testShoudNotPayWhenGatewayReturnOk()
    {
        // cenario
        // cria os dubles
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
        ->will($this->returnCallback(
            function($method, $address, $body)
            {
                return $this->fakeHttpClientSend($method, $address, $body);
            }
        ));
        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, "usuario@teste", "123456");

        // ação
        $pay = $gateway->pay(
            "duble teste",
            5111133193300096,
            new \DateTime('now'),
            100
        );

        // assert
        $this->assertEquals(true, $pay);
    }

    /**
     * OBS:
     * O FAKE É UM TIPO DE DUBLE, A DIFERENÇA É QUE ELE UTILIZA MAIS METODOS DENTRO DA CLASSE TESTE, ONDE PODEMOS TER UMA FORMA DE ARMAZENAMENTO USANDO ARRAYS, E PODEMOS SALVAR, PEGAR E REMOVER, DE UM ARRAY COMO SE FOSSE DO BANCO DE DADOS, ASSIM PODEMOS TESTAR COISAS MAIS ESPECIFICAS.
     * POREM COM MOCK PODEMOS FAZER O MESMO E DE FORMA MAIS FACIL E MAIS DINAMICO
     */

    /**
     * metodo auxiliar, de fake, para saber o retorno de token
     *
     * @param [type] $method
     * @param [type] $address
     * @param [type] $body
     * @return void
     */
    public function fakeHttpClientSend($method, $address, $body)
    {
        switch ($address) {
            case Gateway::BASE_URL . '/authenticate':
                if ($body['user'] == "usuario@teste")
                {
                    return 123132131;
                }
                return null;
                break;

            case Gateway::BASE_URL . '/pay':
                    if($body['credit_card_number'] == 5111133193300096)
                    {
                        return ['paid' => true];
                    }
                    return ['paid' => false];
                break;
        }
    }
}