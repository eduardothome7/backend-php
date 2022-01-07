<?php

namespace Moovin\Job\Backend;

/**
 * @author Eduardo Cannini Thomé <eduardocanninithome@hotmail.com>
 */
class Screen
{
	/**
     * Imprime quebrando a linha, como uma pequena pausa no console, com opção de formatação quando for uma mensagem do próprio caixa eletrônico
     * @param $ln
     * @param bool $formataInstrucao
     * @return array
    */
    public function print($ln, $formataInstrucao = false) 
    {
        if($formataInstrucao) {
            echo "\n======================================================================================================\n";
            echo $ln;
            echo "\n======================================================================================================\n";
        } else {
    	   echo "\n$ln";
        }
		// sleep(1);
    }
}