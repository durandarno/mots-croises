<?php function generetab()
{
	global $plateau;
	global $definitions;
	
	//redefinition du plateau pour avoir la selection des mots
	
	echo "<table>";
	echo "<tr>";
	echo "<td>";
	
		echo "<table style=' border-collapse: collapse;border:none;'>";
		for ($i=0;$i<count($plateau);$i++)
		{
			echo "<tr>";
			for ($j=0;$j<count($plateau[$i]);$j++)
			{
				echo "<td style='text-align:center; vertical-align: bottom;'>";
				if ($plateau[$i][$j]!="")
				{
					if ($plateau[$i][$j][0]=='_')
					{
						echo "<input type='hidden' value='_".(substr($plateau[$i][$j],1))."' id='c".$i."-".$j."'><span style='cursor:  pointer;' onclick='effacecouleurtoutescases();coloriemot(".(substr($plateau[$i][$j],1)).");focusdefinition(".(substr($plateau[$i][$j],1)).");' class='num'>".(substr($plateau[$i][$j],1))."</span>";
					}
					else
					{
						echo "<input id='c".$i."-".$j."'  maxlength='1' onkeyup='if (filtre_clavier(event)){this.value=this.value.toUpperCase();passeauvoisin(".$i.",".$j.");}' onclick='reset_sens();this.select();colorie(".$i.",".$j.");' onkeydown='js(".$i.",".$j.",event);' type='text' size='1' value=''>";
					}
				}
				echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		echo "</td>";
		echo "<td>";
		echo "<table>";
		for ($i=0;$i<count($definitions);$i++)
		{
			echo "<tr class='def' style='cursor:  pointer;' onClick='focusdefinition(".($i+1).");' onMouseOver='coloriemot(".($i+1).");' onMouseOut='effacecouleurtoutescases();'><td class='num2'>".($i+1)."</td><td >".($definitions[$i])."</td></tr>";
		}
		echo "</table>";
		echo "</td></tr></table>";
		echo "<input class='myButton'  type='button' onclick='verification()' value='Corriger'/>";
		echo "<input class='myButton'  type='button' onclick='reset()' value='Reset'/>";
		
}
function generetab2()
{
	global $plateau;
	global $definitions;
	
	//redefinition du plateau pour avoir la selection des mots
	
	echo "<table>";
	echo "<tr>";
	echo "<td>";
	
		echo "<table style=' border-collapse: collapse;border:none;'>";
		for ($i=0;$i<count($plateau);$i++)
		{
			echo "<tr>";
			for ($j=0;$j<count($plateau[$i]);$j++)
			{
				echo "<td style='text-align:center; vertical-align: bottom;'>";
				if ($plateau[$i][$j]!="")
				{
					if ($plateau[$i][$j][0]=='_')
					{
						echo "<input type='hidden' value='_".(substr($plateau[$i][$j],1))."' id='c".$i."-".$j."'><span class='num'>".(substr($plateau[$i][$j],1))."</span>";
					}
					else
					{
						echo "<input id='c".$i."-".$j."'  maxlength='1'  type='text' size='1' value=''>";
					}
				}
				echo "</td>";
			}
			echo "</tr>";
		}
		echo "</table>";
		echo "</td></tr><tr>";
		echo "<td>";
		echo "<table>";
		for ($i=0;$i<count($definitions);$i++)
		{
			echo "<tr style='cursor:  pointer;' onClick='focusdefinition(".($i+1).");' onMouseOver='coloriemot(".($i+1).");' onMouseOut='effacecouleurtoutescases();'><td class='num2'>".($i+1)."</td><td >".($definitions[$i])."</td></tr>";
		}
		echo "</table>";
		echo "</td></tr></table>";
		
		
}
?>
