<?php

namespace Moovin\Job\Backend;
use Carbon\Carbon;

/**
 * @author Eduardo Cannini Thomé <eduardocanninithome@hotmail.com>
 */
class Pessoa
{	
	protected string $nome;
	protected string $cpf;
	protected Carbon $dataNascimento;
	protected string $email;
	protected string $telefone;

	/**
     * @param string $nome
     * @param string $cpf
     * @param Carbon $dataNascimento
     * @param string $email
     * @param string $telefone
     */
    public function __construct(string $nome, string $cpf, Carbon $dataNascimento, string $email, string $telefone)
	{
		$this->nome = $nome;
		$this->cpf = $cpf;
		$this->dataNascimento = $dataNascimento;
		$this->email = $email;
		$this->telefone = $telefone;
	}

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return "Nome do Titular: " . $this->nome . "| CPF: " . $this->cpf . "| E-mail: " .$this->email. " | Telefone: " . $this->telefone . " | Data de Nascimento: " . $this->dataNascimento->format('d/m/Y');
    }
}