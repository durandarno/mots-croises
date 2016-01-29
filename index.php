<? session_start();
?>
<!DOCTYPE html>
<html>

<head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Mots croisés </title>
	
	<style>
		<?
		include('style.php');
		?>
	
</style>
		
<?php ini_set('display_errors',1);
include('fonction.php');
include('fonction_creer_grille.php');


if (isset($_GET['fichier']))
{
	$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/mots-croises/tmp/';

	$uploadfile = $uploaddir . basename($_FILES['fichier']['name']);

	echo '<pre>';
	if (($_FILES['fichier']['type']=='text/plain')||($_FILES['fichier']['type']=='text/csv'))
	{
		if (move_uploaded_file($_FILES['fichier']['tmp_name'], $uploadfile)) 
		{
			echo "Le fichier est valide, et a été téléchargé avec succès.\n";

				  $tabfichier=traitement($uploadfile);
				  $_SESSION['tabfichier']=$tabfichier;
					$boolfichier=true;
					
		}
		 else 
		{
			echo "Attaque potentielle par téléchargement de fichiers.\n";
			$boolfichier=false;
		}
		
	}
	else
	{
		echo "Ce n'est pas un fichier texte ou cvs mais ".$_FILE['fichier']['type'];
		$boolfichier=false;
	}



	echo '</pre>';
}

	if (isset($_GET['alea']))
	{
		$tabfichier=aleatab($_SESSION['tabfichier']);
		$_SESSION['tabfichier']=[$tabfichier[0],$tabfichier[1]];
		$tabtmp=genererlagrille2($tabfichier[0],$tabfichier[1]);
		$plateau=$tabtmp[0];
		$definitions=$tabtmp[1];
		
	}
	elseif ((isset($_GET['fichier']))&&($boolfichier))
	{
		$tabtmp=genererlagrille2($tabfichier[0],$tabfichier[1]);
		$plateau=$tabtmp[0];
		$definitions=$tabtmp[1];

		 
	}
	elseif ($_GET['grille']!='')
	{
		include('variable'.$_GET["grille"].'.php');
		$tabtmp=genererlagrille2($listemots,$definitions);
		 $_SESSION['tabfichier']=[$listemots,$definitions];
		$plateau=$tabtmp[0];
		$definitions=$tabtmp[1];
	}
	elseif (isset($_GET['export']))
	{
		$tabtmp=genererlagrille2($_SESSION['tabfichier'][0],$_SESSION['tabfichier'][1]);
		$plateau=$tabtmp[0];
		$definitions=$tabtmp[1];
	}
	else
	{
		include('variable.php');
		$tabtmp=genererlagrille2($listemots,$definitions);
		 $_SESSION['tabfichier']=[$listemots,$definitions];
		$plateau=$tabtmp[0];
		$definitions=$tabtmp[1];
	}

	echo '<script type="text/javascript">';
	?>
	
function print_r(printthis, returnoutput) {
    var output = '';

    if($.isArray(printthis) || typeof(printthis) == 'object') {
        for(var i in printthis) {
            output += i + ' : ' + print_r(printthis[i], true) + '\n';
        }
    }else {
        output += printthis;
    }
    if(returnoutput && returnoutput == true) {
        return output;
    }else {
        alert(output);
    }
}

function filtre_clavier(event)
{
	if (((event.keyCode || event.charCode)!=8)&&((event.keyCode || event.charCode)!=37)&&((event.keyCode || event.charCode)!=38)&&((event.keyCode || event.charCode)!=39)&&((event.keyCode || event.charCode)!=40)&&((event.keyCode || event.charCode)!=46))
	{
		return true;
	}
}

function js(i,j,event) {
    var key = event.keyCode || event.charCode;
	if( key == 46 )
	{
		lacase=document.getElementById("c"+i+"-"+j);
		lacase.value='';
		
	}
	if( key == 37 )
	{
		var k=j -1;
		var m=i ;
		document.getElementById("sens").value='';
		if (lacase=document.getElementById("c"+m+"-"+k))
		{
			lacase.focus();
			if (lacase.value!='')
			{
				lacase.select();
			}
			colorie(m,k);
			
		}
		else
		{
			lacase=document.getElementById("c"+i+"-"+j);
			lacase.focus();
			lacase.select();
			
		}
	}
	if( key == 38 )
	{
		var k=j;
		var m=i -1 ;
		document.getElementById("sens").value='';
		if (lacase=document.getElementById("c"+m+"-"+k))
		{
			lacase.focus();
			if (lacase.value!='')
			{
				lacase.select();
			}
			colorie(m,k);
		}
		else
		{
			lacase=document.getElementById("c"+i+"-"+j);
			lacase.focus();
			lacase.select();
		}	
	}
	if( key == 39 )
	{
		var k=j+1;
		var m=i  ;
		document.getElementById("sens").value='';
		if (lacase=document.getElementById("c"+m+"-"+k))
		{
			lacase.focus();
			if (lacase.value!='')
			{
				lacase.select();
			}
			colorie(m,k);
		}
		else
		{
			lacase=document.getElementById("c"+i+"-"+j);
			lacase.focus();
			lacase.select();
		}		
	}
	if( key == 40 )
	{
		var k=j;
		var m=i + 1 ;
		document.getElementById("sens").value='';
		if (lacase=document.getElementById("c"+m+"-"+k))
		{
			lacase.focus();
			if (lacase.value!='')
			{
				lacase.select();
			}
			colorie(m,k);
		}
		else
		{
			lacase=document.getElementById("c"+i+"-"+j);
			lacase.focus();
			lacase.select();
		}		
	}	
    if( key == 8 )
    { 
		var k=j - 1;
		var m=i - 1;
		variable=document.getElementById("sens");
		
		if (variable.value!='')
		{
			if (variable.value=="j")
			{
				if (lacase=document.getElementById("c"+i+"-"+k))
				{
					lacase.focus();
					if (lacase.value!='')
					{
						lacase.select();
					}
					variable.value="j";
					colorie(i,k);
				}
				else
				{
					document.getElementById("c"+i+"-"+j).blur();
					variable.value="";
				}
			}
			else
			{
				if (variable.value=="i")
				{
					if (lacase=document.getElementById("c"+m+"-"+j))
					{
						lacase.focus();
						if (lacase.value!='')
						{
							lacase.select();
						}
						variable.value="i";
						colorie(m,j);
					}
					else
					{
						document.getElementById("c"+i+"-"+j).blur();
						variable.value="";
					}
				}
			}
		}
		else
		{
			if (lacase=document.getElementById("c"+i+"-"+k))
			{
				lacase.focus();
				if (lacase.value!='')
				{
					lacase.select();
				}
				variable.value="j";
				colorie(i,k);
			}
			else
			{
				if (lacase=document.getElementById("c"+m+"-"+j))
				{
					lacase.focus();
					if (lacase.value!='')
					{
						lacase.select();
					}
					variable.value="i";
					colorie(m,j);
				}
			}
		}
	}
}

function reset()
{
	<?php
	echo "plateau=[";
		for ($i=0;$i<count($plateau);$i++)
		{
			echo "[";
			for ($j=0;$j<count($plateau[$i]);$j++)
			{
				echo "\"".$plateau[$i][$j]."\"";
				if ($j<(count($plateau[$i])-1))
				{echo ",";}
			}
			echo "]";
			if ($i<(count($plateau)-1))
			{
				echo ",";
			}
		}
		echo "];\n";
		?>
		for (i = 0; i < plateau.length; i++) 
		{		
			for (j = 0; j < (plateau[i]).length; j++) 
			{
				if (!!document.getElementById("c"+i+"-"+j))
				{
					if (document.getElementById("c"+i+"-"+j).value[0]!='_')
					{
						document.getElementById("c"+i+"-"+j).value="";
					}
				}
			}	
		}
}

function effacecouleurtoutescases()
{
	document.getElementById("sens").value='';
		<?php
	echo "plateau=[";
		for ($i=0;$i<count($plateau);$i++)
		{
			echo "[";
			for ($j=0;$j<count($plateau[$i]);$j++)
			{
				echo "\"".$plateau[$i][$j]."\"";
				if ($j<(count($plateau[$i])-1))
				{echo ",";}
			}
			echo "]";
			if ($i<(count($plateau)-1))
			{echo ",";}
		}
		echo "];";
		?>
		for (i = 0; i < plateau.length; i++) 
		{
			for (j = 0; j < (plateau[i]).length; j++) 
			{
				if (!!document.getElementById("c"+i+"-"+j))
				{
					if (document.getElementById("c"+i+"-"+j).value[0]!='_')
					{
						document.getElementById("c"+i+"-"+j).style.backgroundColor="";
					}
				}
			}	
		}
}

function focusdefinition(num)
{
	<?php
	echo "plateau=[";
		for ($i=0;$i<count($plateau);$i++)
		{
			echo "[";
			for ($j=0;$j<count($plateau[$i]);$j++)
			{
				echo "\"".$plateau[$i][$j]."\"";
				if ($j<(count($plateau[$i])-1))
				{echo ",";}
			}
			echo "]";
			if ($i<(count($plateau)-1))
			{echo ",";}
		}
		echo "];";
		?>
		for (i = 0; i < plateau.length; i++) 
		{
			for (j = 0; j < (plateau[i]).length; j++) 
			{
				if (!!document.getElementById("c"+i+"-"+j))
				{
					if (document.getElementById("c"+i+"-"+j).value=="_"+num)
					{
						var a=i;
						var b=j
					};
				}
			}	
		}
		var k=b + 1;
		var m=a + 1;
		if (lacase=document.getElementById("c"+a+"-"+k))
		{
			lacase.focus();
		}
		else
		{
			if (lacase=document.getElementById("c"+m+"-"+b))
			{
				lacase.focus();
			}
		}
}


function coloriemot(num)
{variable=document.getElementById("sens");
	<?php
	echo "plateau=[";
		for ($i=0;$i<count($plateau);$i++)
		{
			echo "[";
			for ($j=0;$j<count($plateau[$i]);$j++)
			{
				echo "\"".$plateau[$i][$j]."\"";
				if ($j<(count($plateau[$i])-1))
				{echo ",";}
			}
			echo "]";
			if ($i<(count($plateau)-1))
			{echo ",";}
		}
		echo "];";
		?>
		for (i = 0; i < plateau.length; i++) 
		{
			for (j = 0; j < (plateau[i]).length; j++) 
			{
				if (!!document.getElementById("c"+i+"-"+j))
				{
					if (document.getElementById("c"+i+"-"+j).value=="_"+num)
					{
						var a=i;
						var b=j
					};
				}
			}	
		}
		var k=b + 1;
		var m=a + 1;
		if (lacase=document.getElementById("c"+a+"-"+k))
		{
			variable.value="j";
			colorie(a,k);
		}
		else
		{
			if (lacase=document.getElementById("c"+m+"-"+b))
			{
			variable.value="i";
			couleur(lacase);
			colorie(m,b);
			}
		}
}
function couleur(lacase)
{
	lacase.style.backgroundColor='#F2F2F2';
}

function colorie(i,j)
{
	var k=j + 1;
	var m=i + 1;
	variable=document.getElementById("sens");
	couleur(document.getElementById("c"+i+"-"+j));
	if (variable.value!='')
	{
		if (variable.value=="j")
		{
			if (lacase=document.getElementById("c"+i+"-"+k))
			{
				if (lacase.value.charAt(0)!='_')
				{
					couleur(lacase);
					colorie(i,k);
				}
			}
		}
		else
		{
			if (variable.value=="i")
			{
				if (lacase=document.getElementById("c"+m+"-"+j))
				{
					if (lacase.value.charAt(0)!='_')
					{
						couleur(lacase);
						colorie(m,j);
					}
				}
			}
		}
	}
	else
	{
		// ici, c'est l'étape d'initialisation de colorie, donc il faut d'abord effacer toutes les cases colorées
		effacecouleurtoutescases();
		couleur(document.getElementById("c"+i+"-"+j));
		if (lacase=document.getElementById("c"+i+"-"+k))
		{
			if (lacase.value.charAt(0)!='_')
			{
				variable.value="j";
				couleur(lacase);
				colorie(i,k);
			}
		}
		else
		{
			if (lacase=document.getElementById("c"+m+"-"+j))
			{
				if (lacase.value.charAt(0)!='_')
				{
					variable.value="i";
					couleur(lacase);
					colorie(m,j);
				}
			}
		}
	}
}

function verification()
{
	bool=true;
<?
	echo "plateau=[";
		for ($i=0;$i<count($plateau);$i++)
		{
			echo "[";
			for ($j=0;$j<count($plateau[$i]);$j++)
			{
				echo "\"".$plateau[$i][$j]."\"";
				if ($j<(count($plateau[$i])-1))
				{echo ",";}
			}
			echo "]";
			if ($i<(count($plateau)-1))
			{echo ",";}
		}
		echo "];";
		?>
		for (i = 0; i < plateau.length; i++) 
		{
			for (j = 0; j < plateau[i].length; j++) 
			{
				if (!!document.getElementById("c"+i+"-"+j))
				{
					if (document.getElementById("c"+i+"-"+j).value[0]!='_')
					{
						var lettre=document.getElementById("c"+i+"-"+j).value[0];
						bool=bool&&(plateau[i][j]==lettre);
					}
				}
			}	
		}
		if (bool)
		{
			alert('BRAVO!!!');
		}
		else
		{
			alert('Il y a des erreurs!');
		}
}


function reset_sens()
{
	variable=document.getElementById("sens");
	variable.value='';
	
}



function passeauvoisin(i,j)
{
	var k=j + 1;
	var m=i + 1;
	variable=document.getElementById("sens");
	document.getElementById("c"+i+"-"+j).style.backgroundColor="";
	
	if (variable.value!='')
	{
		if (variable.value=="j")
		{
			if (lacase=document.getElementById("c"+i+"-"+k))
			{
				lacase.focus();
				if (lacase.value!='')
				{
					lacase.select();
				}
				variable.value="j";
			}
			else
			{
				//document.getElementById("c"+i+"-"+j).blur();
				//variable.value="";
				lacase=document.getElementById("c"+i+"-"+j);
				lacase.select();
			}
		}
		else
		{
			if (variable.value=="i")
			{
				if (lacase=document.getElementById("c"+m+"-"+j))
				{
					lacase.focus();
					if (lacase.value!='')
					{
						lacase.select();
					}
					variable.value="i";
				}
				else
				{
					//document.getElementById("c"+i+"-"+j).blur();
					//variable.value="";
					lacase=document.getElementById("c"+i+"-"+j);
					lacase.select();
				}
			}
		}
	}
	else
	{
		if (lacase=document.getElementById("c"+i+"-"+k))
		{
			lacase.focus();
			if (lacase.value!='')
			{
				lacase.select();
			}
			variable.value="j";
		}
		else
		{
			if (lacase=document.getElementById("c"+m+"-"+j))
			{
				lacase.focus();
				if (lacase.value!='')
				{
					lacase.select();
				}
				variable.value="i";
			}
		}
	}
}
<?
	echo '</script>';

if (!isset($_GET['export']))
{?>
<script src="clipboard.min.js"></script>
<script>
    function printdiv(divID)
    {
      var headstr = "<html><head><title>Mots Croisés</title></head><body>";
      var footstr = "</body>";
      var newstr = document.all.item(divID).innerHTML;
      var oldstr = document.body.innerHTML;
      document.body.innerHTML = headstr+newstr+footstr;
      window.print();
      document.body.innerHTML = oldstr;
      return false;
    }
});

</script>

<?
}?>
</head>

<body>
	<div class="info" >
		
<?	
	if (!isset($_GET['export']))
	{?>		
		<a class="myButton" href="index.php">Grille : vocabulaire de géométrie</a>
		<a class="myButton" href="index.php?grille=1">Grille : vocabulaire de calcul</a><br/>
		<?

		if (  isset($_SESSION['tabfichier']))
		{
				echo '<a class="myButton" href="index.php?alea=1">Une autre configuration de la grille</a>';
		}
		?>
		<a class="myButton" href="creer_grille.php">Créer votre grille</a>
		
		<input  class="myButton" name="b_print" type=button value="Imprimer " style="font-weight:bold;font-family:times_new_roman;font-size:12px"
	onclick="printdiv('grilletot');">
	<a class="myButton" href="index.php?export=1" target="_blank">Export HTML</a>


		<?
	}
	generetab();
	?>
		<input type='hidden' value='' id='sens' name='sens'/>
	</div><br/>
<i>Application créée par Arnaud DURAND.<br/>
Licence Creatives Commons BY-NC-SA v2.<br/>
<a href='http://mathix.org'>Mathix.org</a></i>

	<?
	if (!isset($_GET['export']))
	{?>	
		
	<div id="grilletot" style="display:none">
		<?
	generetab2();
	?><br/><br/><i>Application créée par Arnaud DURAND.<br/>
Licence Creatives Commons BY-NC-SA v2.<br/>
<a href='http://mathix.org'>Mathix.org</a></i>
		</div>
		<?}?>
</body>

</html>
