<?php

namespace Moovin\Job\Backend;
use Carbon\Carbon;

/**
 * @author Eduardo Cannini Thomé <eduardocanninithome@hotmail.com>
 */
class CaixaEletronico
{
    
    protected Agencia $agenciaAcesso;
    protected Carbon $dataAcesso;

    /** Taxas **/
    protected float $TAXA_CC = 2.50;
    protected float $TAXA_POUPANCA = 0.80;

    /** Limites **/
    protected float $LIMITE_CC = 600.00;
    protected float $LIMITE_POUPANCA = 1000.00;
    
    /**
     * Inicia o caixa eletrônico informando qual agência está localizado, além da data de acesso 
     * @param Agencia $agenciaAcesso
     * @param Carbon $dataAcesso
     */
    public function __construct(Agencia $agenciaAcesso, Carbon $dataAcesso)
    {
        $this->agenciaAcesso = $agenciaAcesso;
        $this->dataAcesso = $dataAcesso;
    }


    public function __toString()
    {
        return 'Caixa Eletrônico: Agência'. $this->numero . ' | Titular | ' . $this->pessoa . "\n" . "Saldo atual: " . Helper::moneyFormat($this->saldo);
    }

    /**
     * Insere um valor float na conta. Como o depósito envolve apenas uma conta, não é preciso explicitar no nome da variável qual conta irá receber e/ou pagar
     * @param Conta $conta
     * @param float $valor
     */
    public function depositar(Conta $conta, float $valor)
    {
        $novoSaldo = $conta->getSaldo() + $valor;
        $conta->setSaldo($novoSaldo);
    }

    /**
    * Pagador envia saldo para a conta favorecida, onde há uma verificação se há saldo para poder realizar a transação
     * @param Conta $contaPagador
     * @param Conta $contaFavorecido
     * @param float $valor
     */
    public function transferir(Conta $contaPagador, Conta $contaFavorecido, float $valor)
    {
        $operacao = $this->movimentar($conta, null, $valor, "transferência"); 
        if(!$operacao[0]) {
            return [false, $operacao[1]];
        } else {
            return [true, $operacao[1]];
        }
    }

    /**
     * Desconta valor da conta após imprimir em papel-moeda o valor informado. Há validação se há saldo na conta para poder fazer a transição 
     * @param Conta $conta
     * @param float $valor
     */
    public function sacar(Conta $conta, float $valor)
    {
        $operacao = $this->movimentar($conta, null, $valor); 
        if(!$operacao[0]) {
            // mantém essa estrutura pois pode ser possível se desajado, incluir um Exception ou melhor tratamento visual do erro, ao invés de simplesmente retornar o array do método interno de movimentação
            return [false, $operacao[1]];
        } else {
            return [true, $operacao[1]];
        }
    }

    /**
    * Função para uso interno apenas, que realiza de fato a transação e contém as regras de negócios válidas para amabas movimentações Saque|Transferência, facilitando a modelagem, pois em caso uma nova movimentação Ex: PIX, Agendamento, podemos utilizar a mesma função sem necessidade de reescrever as regras de negócio
     * @param Conta $conta
     * @param ?Conta $contaFavorecido
     * @param float $valor
     * @param string $tipo = Saque(default)
     * @return array
     */
    protected function movimentar(Conta $conta, ?Conta $contaFavorecido = null, float $valor, ?string $tipo = "saque")
    {
        $saldoAtual = $conta->getSaldo();

        $taxa = ($conta->getTipo() == "CC")? $this->TAXA_CC : $this->TAXA_POUPANCA;
        $limite = ($conta->getTipo() == "CC")? $this->LIMITE_CC : $this->LIMITE_POUPANCA;

        $valorMovimentacao = $valor + $taxa;
        
        if($tipo == "saque") {
            
            $validacao = $this->validarMovimentacao($saldoAtual, $conta->getValorTotalSaqueNesteAcesso(), $valorMovimentacao, $limite);
            
            if(!$validacao[0]) {
                return [false, $validacao[1]];
            } else {
                $conta->setSaldo($saldoAtual - $valorMovimentacao);
                $conta->atualizaValorTotalSaquesNesteAcesso($valor);
            }
        
        } else {
            // Transferência
            $validacao = $this->validarMovimentacao($saldoAtual, $conta->getValorTotalSaqueNesteAcesso(), $valorMovimentacao, $limite, false);

            if(!$validacao[0]) {
                return [false, $validacao[1]];
            } else {
                $conta->setSaldo($saldoAtual - $valorMovimentacao);
                $contaFavorecido->setSaldo($contaFavorecido->getSaldo() + $valorMovimentacao);
            }
        }

        return [true, "Novo(a) $tipo. Movimentação realizada com sucesso"];
    }


    /**
    * Validação que será utilizada para todas as transações, retornando um status bool indicando sucesso 
    * e uma mensagem de qual motivo a transação não pode ser feita
     * @param float $saldo
     * @param float $movimentado
     * @param float $valorMovimentacao
     * @param float $limite
     * @param ?bool $saque = true(default)
     * @return array
     */
    protected function validarMovimentacao(float $saldo, float $movimentado = null, float $valorMovimentacao, float $limite, ?bool $saque = true) 
    {
        // Regra incluída apenas para saque
        if($saque){
            if($valorMovimentacao > $limite) {
                return [false, "Operação não pode ser realizada: O valor do saque supera o limite deste caixa."];
            } else {
                if($movimentado > $limite) {
                    return [false, "Operação não pode ser realizada: Limite de saques excedido."];
                } else {
                    if($movimentado + $valorMovimentacao > $limite) {
                        $valorRestante = $limite - $valorMovimentacao;
                        $valorRestante = Helper::moneyFormat($valorRestante);
                        return [false, "Operação não pode ser realizada: Este valor irá exceder seu limite neste acesso. Você ainda poder mover no máximo $valorRestante"];
                    }
                } 
            }
        }

        if($valorMovimentacao > $saldo) {
            return [false, "Operação não pode ser realizada: Saldo insuficiente."];
        } 

        return [true, 'Movimentação OK'];
    }

}