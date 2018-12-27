<?php

function checkPseudo($pseudo)
{
	$retErrors = array();	
	if ($pseudo == 'Hitler')
	{
		$retErrors[] = 'Hum... pseudo nul';
	}

	if ($pseudo == "")
	{
		$retErrors[] = "Votre pseudo ne peut pas être une chaîne vide";
	}

	if (strlen($pseudo) > 30)
	{
		$retErrors[] = 'Veuillez entrer un pseudo de 30 caractères maximum';
	}

	//si le pseudo contient des espaces ou des caracteres non imprimables

	return ($retErrors);
}
