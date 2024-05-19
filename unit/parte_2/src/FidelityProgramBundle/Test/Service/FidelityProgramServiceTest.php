<?php

namespace FidelityProgramBundle\Test\Service;

use FidelityProgramBundle\Repository\PointsRepository;
use FidelityProgramBundle\Service\FidelityProgramService;
use FidelityProgramBundle\Service\PointsCalculator;
use MyFramework\LoggerInterface;
use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class FidelityProgramServiceTest extends TestCase
{
    /**
     * Metodo que vai testar que o metodo save vai ser executado ao salvar os pontos do usuario
     *
     * @test
     */
    public function testShouldBeSaveReceivedPoints()
    {
        // cenario
        // dubles
        // $pointsRepository = $this->createMock(PointsRepository::class);
        $pointsCalculator = $this->createMock(PointsCalculator::class);
        $valueParam = 100;
        $pointsCalculator->method('calculatePointsToReceive')->willReturn($valueParam);
        $logger = $this->createMock(LoggerInterface::class);
        
        $pointsRepository = new PointsRepositorySpy();

        // dummie
        // é um tipo de duble, só que a diferença é que ele nao é usado para nada, apenas para atender expectativas, parametros e etc.
        $customer = $this->createMock(Customer::class);

        // assert (QUANDO SE TEM METODO SEM RETORNO)
        // como o metodo de add points, nao tem um retorno, a forma de validar que a asserção foi feita, que o teste passou e esta ok.
        // é verificando no mock/duble criado, que o metodo que a gente espera q seja executado lá na classe, onde saberemos que deu tudo certo, foi feita.
        // obs1: nesse caso, a ordem que entendemos como: cenario -> action -> assert, é alterada, pois precisamos dizer ANTES, QUAL o metodo que esperamos q seja executado, antes de executar.
        // a leitura é: no duble/mock, esperamos (expects) que pelo menos 1 vez (this->once()), execute o metodo save
        // $pointsRepository->expects($this->once())->method('save');  

        // ação
        $fidelityProgramServer = new FidelityProgramService($pointsRepository,$pointsCalculator,$logger);

        $fidelityProgramServer->addPoints($customer, $valueParam);

        // assert (PARA CENARIO COM SPY/SPIES)
        // verificamos se o metodo save, foi chamado pela classe spy
        $this->assertEquals(true, $pointsRepository->called());
    }

    /**
     * Metodo que vai testar que o metodo de salvar nunca sera executado
     *
     * @test
     */
    public function testShouldBeNOtSaveReceivedZeroPoints()
    {
        // cenario
        $pointsRepository = $this->createMock(PointsRepository::class);
        $pointsCalculator = $this->createMock(PointsCalculator::class);
        $valueParam = 0;
        $pointsCalculator->method('calculatePointsToReceive')->willReturn($valueParam);
        $logger = $this->createMock(LoggerInterface::class);
        
            // dubles
        $customer = $this->createMock(Customer::class);

        // assert
        // como o metodo de add points, nao tem um retorno, a forma de validar que a asserção foi feita, que o teste passou e esta ok.
        // é verificando no mock/duble criado, que o metodo que a gente espera q seja executado lá na classe, onde saberemos que deu tudo certo, foi feita.
        // obs1: nesse caso, a ordem que entendemos como: cenario -> action -> assert, é alterada, pois precisamos dizer ANTES, QUAL o metodo que esperamos q seja executado, antes de executar.
        // a leitura é: no duble/mock, esperamos (expects) que NUNCA (this->never()) seja executada o metodo save
        $pointsRepository->expects($this->never())->method('save');  

        // ação
        $fidelityProgramServer = new FidelityProgramService($pointsRepository,$pointsCalculator,$logger);

        $fidelityProgramServer->addPoints($customer, $valueParam);
    }

    /**
     * Metodo que vai testar que o metodo save vai ser executado ao salvar os pontos do usuario
     *
     * @test
     */
    public function testShouldBeSaveReceivedPointsWithSpy()
    {
        // cenario
        // dubles
        $pointsRepository = $this->createMock(PointsRepository::class);
        $pointsCalculator = $this->createMock(PointsCalculator::class);
        $valueParam = 100;
        $pointsCalculator->method('calculatePointsToReceive')->willReturn($valueParam);
        $logger = $this->createMock(LoggerInterface::class);
        $AllMessages = [];
        // metodo no spy, que vai realizar pegar o que for salvo no metodo log e vamos recuperar (por isso returnCallback)
        // entao a leitura é: no spy, criamos um metodo log, que ao ser chamado será (will) feito um callback, e vamos pegar o seu parametro esperado de verdade, e vamos por o use, para uma variavel aqui no test q vamos pegar o que for salvo no metodo log.
        $logger->method('log')->will($this->returnCallback(
            function ($message) use (&$AllMessages)
            {
                $AllMessages[] = $message;
            }
        ));

        // dummie
        // é um tipo de duble, só que a diferença é que ele nao é usado para nada, apenas para atender expectativas, parametros e etc.
        $customer = $this->createMock(Customer::class);

        // ação
        $fidelityProgramServer = new FidelityProgramService($pointsRepository,$pointsCalculator,$logger);

        $fidelityProgramServer->addPoints($customer, $valueParam);

        // assert
        // vamos verificar se as mensagens que sao salvas no metodo log, é a que salvamos na variavel aqui no test
        $this->assertEquals([
            'Checking points for customer',
            'Customer received points'
        ], $AllMessages);
    }

}