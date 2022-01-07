<?php

require_once __DIR__ . '/vendor/autoload.php';

use Carbon\Carbon;
use Moovin\Job\Backend\Agencia as Agencia;
use Moovin\Job\Backend\Conta as Conta;
use Moovin\Job\Backend\CaixaEletronico as CaixaEletronico;
use Moovin\Job\Backend\Pessoa as Pessoa;
use Moovin\Job\Backend\Screen as Screen;

// Agência 001
$agencia = new Agencia("0001-9", "99999997", "457", "A16");

Screen::print("Criando agência principal...", true);
Screen::print($agencia);

// Caixa Eletrônico da Agência 0001
$caixaEletronico = new CaixaEletronico($agencia, Carbon::now());
Screen::print("Iniciando o Caixa do CyBank...", true);

// Pessoa 001
// utilizei Carbon para facilitar criação e exibição de datas (usa o calendário terráqueo mesmo...)
$pessoa = new Pessoa('José Ztymplix', '02996985097-7', Carbon::createFromDate('1995','10','10'), 'joseztymplix@cybermail.cib', '(97) 77 31289312x');

Screen::print($pessoa);

Screen::print("Criando conta com saldo inicial de B$ 1000,00...", true);

// Conta 001
$conta = new Conta($agencia, $pessoa, "0000013", 1000.00);

Screen::print($conta);

Screen::print('"Vou sacar uns biteris para pagar o combustível da nave! "');

Screen::print('" - Opa! Lembrei que devo uma parcela da nave para o sr. Cosmotrix"');

Screen::print("Criando pessoa para credor...", true);

// Pessoa 002
$pessoa2 = new Pessoa('Alcides Cosmotrix', '013295985097', Carbon::createFromDate('1951','03','04'), 'alcidescosmotrix@eletromail.cib', '(97) 72 315873971B');

Screen::print($pessoa2);

Screen::print("Criando a conta do credor...", true);

// Conta 002
$conta2 = new Conta($agencia, $pessoa2, "000001-4", 99999.00);

Screen::print($conta2);

Screen::print("Transferindo Valor de  B$ 596,00");

Screen::print('" - Será que posso sacar para comprar uma Cybercola gelada? "');
Screen::print('" - Vou consultar o meu saldo... "');
Screen::print($conta->extrato());

Screen::print("Ótimo! Tenho! Vou sacar, Cybercola está custando B$ 2,45");

$caixaEletronico->sacar($conta, 2.45);

Screen::print('"Ah, estava esquecendo..."');
Screen::print('"Fiz uma ótima venda de xplyxzmotrofos, tenho B$ 700,00 impressos, melhor depositar, não está nada seguro andar de nave espacial por aí..."');

$caixaEletronico->depositar($conta, 1200.00);

Screen::print("Depósito realizado", true);
Screen::print($conta->extrato(), true);

// saldo
Screen::print('"Agora que foi descontado o valor da taxa de saque!"');
Screen::print('"Melhor colocar uma parte na poupança o valor é menor..."');

//deposito
Screen::print('"Deixa eu ver...se eu clicar aqui abro uma conta poupança, vejamos..."');

$contaPoupanca = new Conta($agencia, $pessoa, "00000124-3", 700);
Screen::print("Criando conta poupança", true);
Screen::print($contaPoupanca);

Screen::print("FIM! Muito obrigado! :)", true);	





