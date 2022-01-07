<?php

namespace Moovin\Job\Backend;

/**
 * @author Eduardo Cannini Thomé <eduardocanninithome@hotmail.com>
 */
class Helper
{
	/**
     * Separa o número da conta do DV(dígito verificador) 
     * @param string $numeroContaAgencia
     * @return array
    */
    public function arrContaDigito(string $numeroContaAgencia): array
    {   
        $arr = explode("-", $numeroContaAgencia);
        $dv = str_replace("-", "", $arr[1]);

        return ["numero" => $arr[0], "digito" => $dv];
    }

    /**
     * pega conteúdo json dos endereços, na camada de back-end, selecionado o campo que irá buscar, sem padrão 'cep'
     * @param string $uri
     * @param string $param
     * @param ?string $field
     * @return array|boolean
    */
    public function getDataFromFile(string $uri, string $param, ?string $field = 'cep')
    {	
    	$json = file_get_contents($uri);

    	$arr = json_decode($json, true);

    	foreach ($arr as $k => $v) 
    	{
    		if($v[$field] == $param) 
    		{
    			return $v;
    		}		
    	}

    	return false;
    }


    /**
     * formata valor monetário, sendo padrão o biteris (B$)
     * @param float $valor
     * @param ?string $unidade
     * @return string
    */
    public function moneyFormat(string $valor, ?string $unidade = "B$")
    {   
        return 'B$ ' . $valor;
    }


}