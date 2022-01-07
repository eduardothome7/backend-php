<?php

namespace Moovin\Job\Backend;

/**
 * @author Eduardo Cannini Thomé <eduardocanninithome@hotmail.com>
 */
class Agencia
{
    // API interna para uso de ceps do planeta Cyber
    protected $API_ENDERECOS = "./api/enderecos.json";

    protected string $numero;
    protected ?int $digito;
    protected string $cep;
    protected string $logradouro;
    protected string $bairro;
    protected string $cidade;
    protected string $uf;
    protected ?string $n = null;
    protected ?string $complemento = null;

     /**
     * Informa o numero da agência com DV(dígito verificador) e os valores são separados ao serem atribuidos (se houver o dígito, podendo ser separado por -(hífen) ou X)
     * É realizada uma consulta em API para atribuir os valores corretos de endereço com base no CEP 
     * @param string $numero
     * @param string $cep
     * @param string $n
     * @param string $complemento
    */
    public function __construct(string $numero, string $cep, string $n, ?string $complemento)
    {
        $arrNumero = Helper::arrContaDigito($numero);
        $this->numero = $arrNumero['numero'];
        if($arrNumero['digito']) {
            $this->digito = $arrNumero['digito'];
        } else {
            $this->digito = null;
        }
        
        $arrEndereco = Helper::getDataFromFile($this->API_ENDERECOS, $cep);
        if(!$arrEndereco) 
        {
            echo "Erro: CEP não encontrado.";
            exit;
        }
        
        $this->n = $n;
        if($complemento) 
        {
            $this->complemento = $complemento;
        }
        $this->logradouro = $arrEndereco['logradouro'];
        $this->cidade = $arrEndereco['cidade'];
        $this->uf = $arrEndereco['uf'];
        $this->cep = $cep;
    }

    /**
     * Retorna o numero da agência com DV
     * @return string
    */
    public function __toString(): string
    {
        $dv = ($this->digito)? "-".$this->digito : "";
        $str = "(AEB)Agência Espacial Bancária: " . $this->numero . $dv;
        $str .= "\n";
        $str = "Endereço: " . $this->getEndereco();
        $str .= "\n";
        $str .= "Os menores juros da galáxia!";
        return $str;
    }

    /**
     * Retorna endereço formatado da agência
     * @return string
    */
    public function getEndereco(): string
    {
        $strComplemento = ($this->complemento)? "/".$this->complemento : "";
        return $this->logradouro . " Nº " . $this->n . $strComplemento . " CEP: " . $this->cep . " - " .$this->cidade. "/" . $this->uf;
    }
}