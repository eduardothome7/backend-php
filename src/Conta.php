<?php

namespace Moovin\Job\Backend;

/**
 * @author Eduardo Cannini Thomé <eduardocanninithome@hotmail.com>
 */
class Conta
{
    protected float $saldo;
    protected string $numero;
    protected Pessoa $pessoa;
    protected ?int $digito;
    protected string $tipo;
    protected Agencia $agencia;
    protected float $valorTotalSaquesNesteAcesso;

     /**
     * Informa a agência que a conta pertence
     * Número da conta com DV(dígito verificador)
     * Cada vez que se inicia a classe, é ententido que houve um novo acesso, ou seja, os valores de transações são reiniciados, ou seja, quando inicia o valor é sempre setado para 0 novamente
     * Também é informado um titular, que irá ter suas informações de nome, cpf e data de nascimento(nisso todos os planetes são iguais...)
     * Pode iniciar com valor no saldo ou inicia sem um valor declarado, assim defininido o saldo inicial como vazio "zero" biteris
     * @param Agencia $agencia
     * @param string $numero
     * @param Pessoa $pessoa
     * @param float $valor
     * @param ?string $tipo por default é ContaCorrente(CC) 
     */
    public function __construct(Agencia $agencia, Pessoa $pessoa, string $numero, float $valor = 0, ?string $tipo = "CC")
    {
        $this->agencia = $agencia;
        $this->pessoa = $pessoa;
        
        $arrNumero = Helper::arrContaDigito($numero);
        $this->numero = $arrNumero['numero'];
        if($arrNumero['digito']) {
            $this->digito = $arrNumero['digito'];
        } else {
            $this->digito = null;
        }        
        $this->saldo = $valor;
        
        $this->tipo = $tipo;
        $this->valorTotalSaquesNesteAcesso = 0;
    }

    public function __toString()
    {
        $dv = ($this->digito)? "/".$this->digito : "";
        $tipo = ($this->tipo == "CC")? "Conta Corrente" : "Poupança";
        return $tipo . ' | Número: '.$this->numero.$dv. ' | Titular | ' . $this->pessoa . "\n" . "Saldo atual: " . Helper::moneyFormat($this->saldo);
    }

    /**
     * Retorna saldo com valor de movimentações da sessão formatado
     * @return string
    */
    public function extrato(): string
    {
        $str = "=== SALDO ATUAL ===================================================";
        $str .= "\n";
        $str .= Helper::moneyFormat($this->saldo);
        $str .= "\n";
        $str .= "=== MOVIMENTADO NESTA SESSÃO ======================================";
        $str .= "\n";
        $str .= Helper::moneyFormat($this->valorTotalSaquesNesteAcesso);
        return $str;
    }

    /**
     * Retorna valor do saldo da conta
     * @return float
    */
    public function getSaldo(): float
    {
        return $this->saldo;
    }

     /**
     * Retorna tipo de conta: Conta Corrente | Poupança
     * @return string
    */
    public function getTipo(): string
    {
        return $this->tipo;
    }

     /**
     * Cada novo valor sacado é registrado durante o acesso da conta
     * @param float $valor
    */
    public function atualizaValorTotalSaquesNesteAcesso(float $valor)
    {
        $this->valorTotalSaquesNesteAcesso += $valor;
    }

    /** 
     * @return float
    */
    public function getValorTotalSaqueNesteAcesso() : float
    {
        return $this->valorTotalSaquesNesteAcesso;
    }

    /**
     * Define valor do saldo da conta
     * @param float $novoValor
    */
    public function setSaldo(float $novoValor)
    {
        // também é possível incluir regras de negócio para alterar o saldo. Ex: não ser possivel 'zerar' o saldo
        $this->saldo = $novoValor;
    }
}