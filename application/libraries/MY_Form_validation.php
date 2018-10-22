<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MY_Form_validation Class
 *
 * @package     Evidencia
 * @category    Library
 * @author      Pedro Cerdeirinha
 */
class MY_Form_validation extends CI_Form_validation {
	
    //-------------------------------------------------------------------------------

	/**
	 * Construtor da Classe
	 * 
	 * Chama o construtor da classe pai
	 *
	 * @return void
	 */
	function __construct()
	{
	    parent::__construct();
	}

	//-------------------------------------------------------------------------------
	
	/**
	 * Retorna todos os erros gerados pelo form_valition->run() em um array contendo
	 * o nome do input do erro e a descrição do erro
	 *
	 * @return array
	 */
	public function errors_array()
	{
	    if (count($this->_error_array) === 0)
	      return FALSE;
	    else
	      return $this->_error_array;  					
	}

	/**

    *

    * valid_cpf

    *

    * Verifica CPF é válido

    * @access  public

    * @param   string

    * @return  bool

    */

	function valid_cpf($cpf)

	{
 
		$CI =& get_instance();
 
		
 
		$CI->form_validation->set_message('valid_cpf', 'O %s informado não é válido.');
 
		$cpf = preg_replace('/[^0-9]/','',$cpf);
 
		if(strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf))
 
		{
 
			return false;
 
		}
 
		// 9 primeiros digitos do cpf
 
		$digit = substr($cpf, 0, 9);
 
		// calculo dos 2 digitos verificadores
 
		for($j=10; $j <= 11; $j++)
 
		{
 
			$sum = 0;
 
			for($i=0; $i< $j-1; $i++)
 
			{
 
				$sum += ($j-$i) * ((int) $digit[$i]);
 
			}
 
			$summod11 = $sum % 11;
 
			$digit[$j-1] = $summod11 < 2 ? 0 : 11 - $summod11;
 
		}
 
		
 
		return $digit[9] == ((int)$cpf[9]) && $digit[10] == ((int)$cpf[10]);
 
	}
}