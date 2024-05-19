<?php

namespace FidelityProgramBundle\Test\Service;

use FidelityProgramBundle\Repository\PointsRepositoryInterface;

class PointsRepositorySpy implements PointsRepositoryInterface
{
    private $called;

    /**
     * metodo implementado para copiar o da interface, mas vai apenas nos dizer que foi chamado
     *
     * @return void
     */
    public function save($points)
    {
        $this->called = true;
    }

    /**
     * Metodo para gente saber que foi chamado
     *
     * @return boolean
     */
    public function called()
    {
        return $this->called;
    }
}