<?php
ini_set('display_errors',1);

function trouvelettrescommunes($mot1,$mot2)
{
	
	for ($i=0;$i<strlen($mot1);$i++)
	{
		$pos = strpos($mot2, $mot1[$i]);
		if ($pos !== false) 
		{
			$lettres[]=$mot1[$i];
		}
		
	}
	return $lettres;
}

function trouverlettresdansgrille($lettre,$grille)
{

	$coordonne=array();
	$liste_colonne=array_keys($grille);
	//echo "lettre ".$lettre." colonne ";
	sort($liste_colonne);
	//print_r($liste_colonne);
	for ($i=0;$i<count($liste_colonne);$i++)
	{
		$liste_ligne=array_keys($grille[$liste_colonne[$i]]);
		sort($liste_ligne);
	//	echo "ligne" ;
		//print_r($liste_ligne);
		for ($j=0;$j<count($liste_ligne);$j++)
		{
			if ($lettre==$grille[$liste_colonne[$i]][$liste_ligne[$j]])
			{
				$coordonne[]=[$liste_colonne[$i],$liste_ligne[$j]];
			}
		}	
	}

	return ($coordonne);
}

function affichebool($b)
{
	if ($b)
	{
			echo "true";
	}
	else
	{
		echo "false";
	}
}

function placeunmotdanslagrille($liste_mots,$grille,$num_mot,$definitions,$definitions_ori) //retourne la grille si placé, sinon retour false
{

	if (count($liste_mots)==0)
	{
		return array($grille,$definitions);
	}
	else
	{
		$h=0;
		$reussite=true;
		while ($reussite)
		{
			$reussite=$reussite&&($h<count($liste_mots));
			
			$mot=$liste_mots[$h];
			
			$defmot=$definitions_ori[$h]; //on recupère la définition, $defmot, correspondant au $mot 
		
			$definitions_apres=$definitions;
			$definitions_apres[]=$defmot;//on ajoute la définition à la liste des définitions à transmettre à l'étape d'apres
		
			$definitions_ori_apres=$definitions_ori;//on ôte la définition à la liste des définitions d'avant à transmettre à l'étape d'apres
			unset($definitions_ori_apres[$h]);
			$definitions_ori_apres= array_values($definitions_ori_apres);
			
			$liste_mots_apres=$liste_mots;
			unset($liste_mots_apres[$h]);//on ôte le mot à la liste des mots d'avant à transmettre à l'étape d'apres
			$liste_mots_apres= array_values($liste_mots_apres);
			$h++;
			//echo "mot_".$num_mot;
			for ($j=0;$j<strlen($mot);$j++) //on parcours le mot lettre par lettre, $j est la position de la lettre dans le mot qui nous intéresse
			{
				//on récupère les lettres et on regarde s'il y en a de présente dans la grille
				$tab=trouverlettresdansgrille($mot[$j],$grille);	
				//echo "recherche".$mot[$j]."\n";
				//print_r($tab);
				if (count($tab)!=0)// s il y a une lettre présente dans la grille
				{
					for ($k=0;$k<count($tab);$k++) //on parcours les coordonnées de chaque lettre qui nous intéresse
					{
						$x=$tab[$k][0];
						$y=$tab[$k][1];

						//print_r($grille);
						if ((!isset($grille[$x-1][$y-1]))&&(!isset($grille[$x+1][$y-1]))&&(!isset($grille[$x-1][$y+1]))&&(!isset($grille[$x+1][$y+1]))) //les cases de coins sont libres (pas de mots accollés possibles)
						{
						//	echo "pas de coins\n";
							if ((!isset($grille[$x-1][$y]))&&(!isset($grille[$x+1][$y])))//placement horizontal
							{
								//echo "--vertical\n";
								//echo "mot:".$mot." lettre".$mot[$j];
								$bool=true;
								for ($m=-1;$m<$j;$m++)
								{
									if (isset($grille[$x-$j+$m][$y]))
									{
										$bool=$bool&&($mot[$m]==($grille[$x-$j+$m][$y]));									
									}
									else
									{
										$bool=$bool&&(!isset($grille[$x-$j+$m][$y]));
										$bool=$bool&&(!isset($grille[$x-$j+$m][$y-1]));
										$bool=$bool&&(!isset($grille[$x-$j+$m][$y+1]));
									}
								}
								for ($m=$j+1;$m<strlen($mot);$m++)
								{
									if (isset($grille[$x-$j+$m][$y]))
									{
										$bool=$bool&&($mot[$m]==($grille[$x-$j+$m][$y]));									
									}
									else
									{
										$bool=$bool&&(!isset($grille[$x-$j+$m][$y]));
										$bool=$bool&&(!isset($grille[$x-$j+$m][$y-1]));
										$bool=$bool&&(!isset($grille[$x-$j+$m][$y+1]));
									}
								}
								if ($bool)
								{
									$grille_apres=$grille;
									$grille_apres[$x-$j-1][$y]="_".$num_mot;
									for ($m=0;$m<$j;$m++)
									{
										$grille_apres[$x+$m-$j][$y]=$mot[$m];
									}
									for ($m=$j+1;$m<strlen($mot);$m++)
									{
										$grille_apres[$x-$j+$m][$y]=$mot[$m];
									}
									//print_r($grille_apres);
									ksort($grille_apres);
									//affichegrille($grille_apres);
									$tabres=placeunmotdanslagrille($liste_mots_apres,$grille_apres,$num_mot+1,$definitions_apres,$definitions_ori_apres);
									
									if ($tabres!=NULL)
									{
										return $tabres;
									}
									else
									{
										$reussite=false;
									}
								}
							}
							elseif ((!isset($grille[$x][$y-1]))&&(!isset($grille[$x][$y+1])))//placement vertical
							{
								//echo "--horizontal\n";
								//echo "mot:".$mot." lettre".$mot[$j];
								$bool=true;
								for ($m=-1;$m<$j;$m++)
								{
									if (isset($grille[$x][$y+$m-$j]))
									{
										$bool=$bool&&($mot[$m]==($grille[$x][$y+$m-$j]));									
									}
									else
									{								
										$bool=$bool&&(!isset($grille[$x][$y+$m-$j]));
										$bool=$bool&&(!isset($grille[$x-1][$y+$m-$j]));
										$bool=$bool&&(!isset($grille[$x+1][$y+$m-$j]));
									}
								}
								for ($m=$j+1;$m<strlen($mot);$m++)
								{
									if (isset($grille[$x][$y+$m-$j]))
									{
										$bool=$bool&&($mot[$m]==($grille[$x][$y+$m-$j]));									
									}
									else
									{									
										$bool=$bool&&(!isset($grille[$x][$y-$j+$m]));
										$bool=$bool&&(!isset($grille[$x-1][$y-$j+$m]));
										$bool=$bool&&(!isset($grille[$x+1][$y-$j+$m]));
									}
								}
							//		affichebool($bool);
							//		echo $m."\n";
								if ($bool)
								{
									$grille_apres=$grille;
									$grille_apres[$x][$y-$j-1]="_".$num_mot;
									for ($m=0;$m<$j;$m++)
									{
										$grille_apres[$x][$y+$m-$j]=$mot[$m];
									}
									for ($m=$j+1;$m<strlen($mot);$m++)
									{
										$grille_apres[$x][$y-$j+$m]=$mot[$m];
									}
									//print_r($grille_apres);
									ksort($grille_apres[$x]);
									//affichegrille($grille_apres);
									$tabres=placeunmotdanslagrille($liste_mots_apres,$grille_apres,$num_mot+1,$definitions_apres,$definitions_ori_apres);
									if ($tabres!=NULL)
									{
										return $tabres;
									}
									else
									{
										$reussite=false;
									}									
								}
							
							}
						}
						else
						{

						}
					}
				}

			}
		}
		return FALSE;
	}
}	
function affichegrille($grille)
{
	$tabnumligne=array_keys($grille);
	$premiereligne=$tabnumligne[0];
	$derniereligne=$tabnumligne[count($tabnumligne)-1];
	
	$premierecolonne=0;
	$dernierecolonne=0;
	for ($m=0;$m<count($tabnumligne);$m++)
	{
		$colonne=$grille[$tabnumligne[$m]];
		$tabnumcolonne=array_keys($colonne);
		if ($premierecolonne>$tabnumcolonne[0])
		{
			$premierecolonne=$tabnumcolonne[0];
		}
		if ($dernierecolonne<$tabnumcolonne[count($tabnumcolonne)-1])
		{
			$dernierecolonne=$tabnumcolonne[count($tabnumcolonne)-1];
		}
	}
	//echo $premiereligne.' '.$derniereligne.' '.$premierecolonne.' '.$dernierecolonne;
	echo "[";
	for ($i=$premiereligne;$i<$derniereligne+1;$i++)
	{
		echo '[';
		for ($j=$premierecolonne;$j<$dernierecolonne+1;$j++)
		{	
			if (isset($grille[$i][$j]))
			{
				if ($grille[$i][$j][0]=='_')
				{
					echo '"'.$grille[$i][$j].'"';
				}
				else
				{
					echo '"'.$grille[$i][$j].'" ';
				}
			}
			else
			{
				echo '"  "';
			}
			if ($j!=$dernierecolonne)
			{
				echo ",";
			}
		}
		if ($i==$derniereligne)
		{
			echo "]]\n";	
		}
		else
		{
			echo "],\n";
		}
	}
	echo "\n";
}


function affichegrille_indexee($grille)
{
	
	//echo $premiereligne.' '.$derniereligne.' '.$premierecolonne.' '.$dernierecolonne;
	echo "[\n";
	for ($i=0;$i<count($grille);$i++)
	{
		echo '[';
		for ($j=0;$j<count($grille[$i]);$j++)
		{	
			if (($grille[$i][$j]!=''))
			{
				if (($grille[$i][$j][0]!='_'))
				{
						echo '"'.$grille[$i][$j].'" ';
					}
					else
					{
						echo '"'.$grille[$i][$j].'"';
					}
			}
			else
			{
				echo '"  "';
			}
			if ($j!=count($grille[$i]))
			{
				echo ",";
			}
		}
		if ($i==count($grille))
		{
			echo "]]\n";	
		}
		else
		{
			echo "],\n";
		}
	}
	echo "\n";
}


function indexergrille($grille)
{
	
	$tabnumligne=array_keys($grille);
	$premiereligne=$tabnumligne[0];
	$derniereligne=$tabnumligne[count($tabnumligne)-1];
	
	$premierecolonne=0;
	$dernierecolonne=0;
	for ($m=0;$m<count($tabnumligne);$m++)
	{
		$colonne=$grille[$tabnumligne[$m]];
		$tabnumcolonne=array_keys($colonne);
		if ($premierecolonne>$tabnumcolonne[0])
		{
			$premierecolonne=$tabnumcolonne[0];
		}
		if ($dernierecolonne<$tabnumcolonne[count($tabnumcolonne)-1])
		{
			$dernierecolonne=$tabnumcolonne[count($tabnumcolonne)-1];
		}
	}

	for ($i=$premiereligne;$i<$derniereligne+1;$i++)
	{

		for ($j=$premierecolonne;$j<$dernierecolonne+1;$j++)
		{
			if (isset($grille[$i][$j]))
			{
					$grilleindexee[$i-$premiereligne][$j-$premierecolonne]=$grille[$i][$j];
			}
			else
			{
				$grilleindexee[$i-$premiereligne][$j-$premierecolonne]="";
			}
		}
	}
	return $grilleindexee;
}


function genererlagrille($mots,$def)
{
	// on met le premier mot dans la grille

	$grille[0][0]='_1';
	for ($i=0;$i<strlen($mots[0]);$i++)
	{
		$grille[$i+1][0]=$mots[0][$i];
	}

	array_shift($mots);
	$grillefinale=placeunmotdanslagrille($mots,$grille,2,[],$def);


	if ($grillefinale!=NULL)
	{
		affichegrille($grillefinale[0]);
		$grillefinale[0]=indexergrille($grillefinale[0]);
		affichegrille_indexee($grillefinale[0]);
	}
	else
	{
		echo "Pas de grille possible";
	}
}

function genererlagrille2($mots,$def)
{
	// on met le premier mot dans la grille

	$grille[0][0]='_1';
	for ($i=0;$i<strlen($mots[0]);$i++)
	{
		$grille[$i+1][0]=$mots[0][$i];
	}
	$definition0=$def[0];
	array_shift($mots);
	array_shift($def);
	$grillefinale=placeunmotdanslagrille($mots,$grille,2,[$definition0],$def);


	if ($grillefinale!=NULL)
	{
		
		$grillefinale[0]=indexergrille($grillefinale[0]);
		return $grillefinale;
	}
	else
	{
		echo "Pas de grille possible. <br/>";
		
	}
}

function aleatab($tab)
{
	for ($i=0;$i<count($tab[0]);$i++)
   {
	   $index[]=$i;
   }
   shuffle($index);
   for ($i=0;$i<count($index);$i++)
   {
	   $tabdefinitive[0][$i]=$tab[0][$index[$i]];
	   $tabdefinitive[1][$i]=$tab[1][$index[$i]];
   }
   return $tabdefinitive;
}

function traitement($fichier)
{
	if (($handle = fopen($fichier, "r")) !== FALSE) 
	{
	   $tab[0] = fgetcsv($handle, 0, ";",'"');
	   $tab[1] = fgetcsv($handle, 0, ";",'"');
	   //on rend aléatoire le placement des mots dans le tableau pour générer plusieurs grilles possibles
	   
	   
		fclose($handle);

	}

	//return(aleatab($tab));
	return($tab);
}
?>

