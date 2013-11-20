<?php
/*
 * This script can process tables
 */
$input = input();

$return = processTables($input);

echo json_encode($return);
print_r($return);

function processTables($input)
{
    if (! stristr($input, '<table'))
        return $input;
    
    $layout = array();
    
    $subpattern = array();
    
    // process <table></table>
    $pattern = "~(.*)(?:<table(?:.*)>)(.*)(?:</table(?:.*)>)~isU";
    
    preg_match_all($pattern, $input, $tables, PREG_SET_ORDER);
    
    $i = 0;
    foreach ($tables as $table) {
        
        $tableRows = array();
        
        // remove <table>, </table>, <tbody> </tbody>
        $pattern = "~(<table[^>]*>)|(<tbody[^>]*>)|(</tbody[^>]*>)|(</table[^>]*>)~isU";
        $table[2] = preg_replace($pattern, '', $table[2]);
        
        // text vor tabelle
        if (strlen(trim($table[1])) > 0) {
            $layout[][]['content'] = $table[1];
        }
        
        // <tr></tr> == new row
        $pattern = "~(?:.*)(?:<tr(?:.*)>)(.*)(?:</tr(?:.*)>)~isU";
        preg_match_all($pattern, $table[2], $tableRows, PREG_SET_ORDER);
        
        foreach ($tableRows as $tableRow) {
            $tableColumns = array();
            
            // remove <tr>, </tr>
            $pattern = "~(<tr[^>]*>)|(</tr[^>]*>)~isU";
            $tableRow[0] = preg_replace($pattern, '', $tableRow[0]);
            
            // <td></td> == new column
            $pattern = "~(?:.*)(?:<td(?:.*)>)(.*)(?:</td(?:.*)>)~isU";
            preg_match_all($pattern, $tableRow[0], $tableColumns, PREG_SET_ORDER);
            
            $columns = array();
            
            foreach ($tableColumns as $tableColumn) {
                
                // remove <td>, </td>
                $pattern = "~(<td[^>]*>)|(</td[^>]*>)~isU";
                $tableColumn[0] = preg_replace($pattern, '', $tableColumn[0]);
                
                $columns[] = array(
                    'content' => $tableColumn[0]
                );
            }
            
            $layout[] = $columns;
        }
        $lastTable = $table;
    }
    
    // process non-tables behind last </table>
    $pos = strpos($input, $table[0]);
    $garbage = substr($input, $pos);
    $layout[][]['content'] = str_replace($table[0], '', $garbage);
    
    return $layout;
}

function input()
{
    return "
        text vor tabelle
        
<table border=\"0\" style=\"width:100%;\">
    <tbody>
        <tr>
            <td style=\"width:30%;\">
                <h2>Exponentielles Wachstum</h2>
            </td>
            <td>aberdaf</td>
            <td>aberdaf</td>
        </tr>
    </tbody>
</table>
        text zwischen tabelle
<table border=\"0\" style=\"width:100%;\">
    <tbody>
        <tr>
            <td style=\"width:30%;\">
                <h2>Exponentielles Wachstum</h2>
            </td>
        </tr>
    </tbody>
</table>
<table>
    <tr>
        <td herp=\"derp\">
           <h2>Exponentielles Wachstum</h2>
        </td>
        <td>columnn2</td>
    </tr>
</table>
        
<table>
    <tbody>
        <tr>
            <td>columnn 1.1</td>
            <td>columnn 1.2</td>
            <td>columnn 1.3</td>
        </tr>
        <tr>
            <td colspan=\"2\">columnn 1.1 + 1.2</td>
            <td>columnn 1.3</td>
        </tr>
    </tbody>
</table>   
        text nach tabelle     
        ";
}

function input1()
{
    return "        
        <table border=\"0\" style=\"width:100%;\"><tbody><tr><td style=\"width:30%;\">
<h2>Exponentielles Wachstum</h2>
</td>
<td><a href=\"/math/wiki/article/view/exponentielles-wachstum\" class=\"frontbox button\">Artikel zum Thema</a></td>
</tr><tr><td>
<p>allg. Formel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; =<img align=\"middle\" alt=\"showimage.php?formula=ceac0c1f13a5c2cceb\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=ceac0c1f13a5c2cceb643becd0271269.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mi»a«/mi»«mo»§#183;«/mo»«msup»«mi»b«/mi»«mi»t«/mi»«/msup»«mo»=«/mo»«mi»y«/mi»«/math»\"></p>
<p>Wachstumsfaktor b =<img align=\"middle\" alt=\"showimage.php?formula=c83dc017b84f2402a2\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=c83dc017b84f2402a26385b36e8cb2c1.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»1«/mn»«mo»,«/mo»«mn»025«/mn»«/math»\"></p>
<p>Anfangswert a&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = <img align=\"middle\" alt=\"showimage.php?formula=cfce097cb39643961f\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=cfce097cb39643961f46fe8ec38ebfce.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»500«/mn»«mo»§#160;«/mo»«/math»\"></p>
<p>Exponent=<img align=\"middle\" alt=\"showimage.php?formula=d7f83c6d9209da3fdd\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=d7f83c6d9209da3fdd8a0981dda1c114.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»t«/mi»«/mfenced»«/math»\"> in Jahren</p>
<p><img align=\"middle\" alt=\"showimage.php?formula=511a82028aed5d3de9\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=511a82028aed5d3de98ad2b33d3ea902.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»y«/mi»«/mfenced»«/math»\"> in Euro</p>
</td>
<td>Erhält Hans jährlich <img align=\"middle\" alt=\"showimage.php?formula=ca5ab6225079288b9c\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=ca5ab6225079288b9cbd827b62b00db2.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»2«/mn»«mo»,«/mo»«mn»5«/mn»«mo»%«/mo»«/math»\"> Zinsen auf sein Kapital so beträgt sein Kontostand das 1,025-fache des vorherigen Betrags von 500 Euro.</td>
</tr><tr><td>
<h1>&nbsp;&nbsp;&nbsp;</h1>
</td>
<td></td>
</tr><tr><td>
<h1>Teilaufgabe a</h1>
</td>
<td></td>
</tr><tr><td>
<h3>&nbsp;&nbsp;&nbsp;</h3>
</td>
<td></td>
</tr><tr><td>
<h3>Gesucht ist y</h3>
</td>
<td></td>
</tr><tr><td>
<p>Exponent=<img align=\"middle\" alt=\"showimage.php?formula=d7f83c6d9209da3fdd\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=d7f83c6d9209da3fdd8a0981dda1c114.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»t«/mi»«/mfenced»«/math»\"> in Jahren</p>
<p>=<img align=\"middle\" alt=\"showimage.php?formula=ec248b58f958001abd\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=ec248b58f958001abded15bf958b4793.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»1«/mn»«/math»\"></p>
</td>
<td>Nach einem Jahr ist sein Kontostand um das genau <img align=\"middle\" alt=\"showimage.php?formula=c687fa274b5d191064\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=c687fa274b5d1910642d526232a99c90.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»1«/mn»«mo»,«/mo»«msup»«mn»025«/mn»«mn»1«/mn»«/msup»«/math»\">-fache des vorherigen Betrages gestiegen.</td>
</tr><tr><td>
<p><img align=\"middle\" alt=\"showimage.php?formula=23a61b14c6d5612710\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=23a61b14c6d5612710e98d4ffc0ac5e7.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»500«/mn»«mo»§#183;«/mo»«mn»1«/mn»«mo»,«/mo»«msup»«mn»025«/mn»«mn»1«/mn»«/msup»«mo»=«/mo»«mn»512«/mn»«mo»,«/mo»«mn»50«/mn»«/math»\"> Euro</p>
</td>
<td></td>
</tr><tr><td>
<p>Exponent=<img align=\"middle\" alt=\"showimage.php?formula=d7f83c6d9209da3fdd\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=d7f83c6d9209da3fdd8a0981dda1c114.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»t«/mi»«/mfenced»«/math»\"> in Jahren</p>
<p>=2</p>
</td>
<td>Nach zwei Jahren wird auf den vorherigen bezinsten Betrag erneut das 1,025 fache aufgschlagen.Ein Zuwachs von&nbsp;<img align=\"middle\" alt=\"showimage.php?formula=b6880420fb5cb545c5\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=b6880420fb5cb545c569c5e9b9855521.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»1«/mn»«mo»,«/mo»«mn»025«/mn»«mo»§#183;«/mo»«mn»1«/mn»«mo»,«/mo»«mn»025«/mn»«mo»§#160;«/mo»«/math»\">also&nbsp;<img align=\"middle\" alt=\"showimage.php?formula=a8dd00e605e8ff6456\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=a8dd00e605e8ff64564b34d4610c3382.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»5«/mn»«mo»,«/mo»«mn»1«/mn»«mo»%«/mo»«/math»\">.</td>
</tr><tr><td>
<p><img align=\"middle\" alt=\"showimage.php?formula=2ea7d43213363f8498\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=2ea7d43213363f84983c79378404fc07.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»500«/mn»«mo»§#183;«/mo»«mn»1«/mn»«mo»,«/mo»«msup»«mn»025«/mn»«mn»2«/mn»«/msup»«mo»=«/mo»«mn»525«/mn»«mo»,«/mo»«mn»31«/mn»«/math»\"> Euro</p>
</td>
<td></td>
</tr><tr><td>
<p>Exponent=<img align=\"middle\" alt=\"showimage.php?formula=d7f83c6d9209da3fdd\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=d7f83c6d9209da3fdd8a0981dda1c114.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»t«/mi»«/mfenced»«/math»\"> in Jahren</p>
<p>=5</p>
</td>
<td></td>
</tr><tr><td>
<p><img align=\"middle\" alt=\"showimage.php?formula=d63e34725a4bd1a207\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=d63e34725a4bd1a207581bbef0b40651.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»500«/mn»«mo»§#183;«/mo»«mn»1«/mn»«mo»,«/mo»«msup»«mn»025«/mn»«mn»5«/mn»«/msup»«mo»=«/mo»«mn»565«/mn»«mo»,«/mo»«mn»70«/mn»«/math»\"> Euro</p>
</td>
<td></td>
</tr><tr><td>
<p>Exponent=<img align=\"middle\" alt=\"showimage.php?formula=d7f83c6d9209da3fdd\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=d7f83c6d9209da3fdd8a0981dda1c114.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»t«/mi»«/mfenced»«/math»\"> in Jahren</p>
<p>=10</p>
</td>
<td></td>
</tr><tr><td>
<p><img align=\"middle\" alt=\"showimage.php?formula=21d646275ace64479d\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=21d646275ace64479d66d71fcda12713.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»500«/mn»«mo»§#183;«/mo»«mn»1«/mn»«mo»,«/mo»«msup»«mn»025«/mn»«mn»10«/mn»«/msup»«mo»=«/mo»«mn»640«/mn»«mo»,«/mo»«mn»04«/mn»«/math»\"> Euro</p>
</td>
<td></td>
</tr><tr><td>
<h1>&nbsp;</h1>
</td>
<td></td>
</tr><tr><td>
<h1>Teilaufgabe b</h1>
</td>
<td></td>
</tr><tr><td>
<p>&nbsp;&nbsp;&nbsp;&nbsp;</p>
</td>
<td></td>
</tr><tr><td>
<p>allg. Formel&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; =<img align=\"middle\" alt=\"showimage.php?formula=ceac0c1f13a5c2cceb\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=ceac0c1f13a5c2cceb643becd0271269.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mi»a«/mi»«mo»§#183;«/mo»«msup»«mi»b«/mi»«mi»t«/mi»«/msup»«mo»=«/mo»«mi»y«/mi»«/math»\"></p>
<p>Wachstumsfaktor b =<img align=\"middle\" alt=\"showimage.php?formula=c83dc017b84f2402a2\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=c83dc017b84f2402a26385b36e8cb2c1.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»1«/mn»«mo»,«/mo»«mn»025«/mn»«/math»\"></p>
<p>Anfangswert a&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; = <img align=\"middle\" alt=\"showimage.php?formula=cfce097cb39643961f\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=cfce097cb39643961f46fe8ec38ebfce.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»500«/mn»«mo»§#160;«/mo»«/math»\"></p>
<p>y</p>
<p>Exponent=<img align=\"middle\" alt=\"showimage.php?formula=d7f83c6d9209da3fdd\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=d7f83c6d9209da3fdd8a0981dda1c114.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»t«/mi»«/mfenced»«/math»\"> in Jahren</p>
<p><img align=\"middle\" alt=\"showimage.php?formula=511a82028aed5d3de9\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=511a82028aed5d3de98ad2b33d3ea902.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»y«/mi»«/mfenced»«/math»\"> in Euro</p>
</td>
<td></td>
</tr><tr><td>
<h3>Gesucht ist t</h3>
</td>
<td>Gesucht ist hierbei die Variable <img align=\"middle\" alt=\"showimage.php?formula=d7f83c6d9209da3fdd\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=d7f83c6d9209da3fdd8a0981dda1c114.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mfenced open=¨[¨ close=¨]¨»«mi»t«/mi»«/mfenced»«/math»\"> in Jahren, also die Anzahl der Jahre die verstreichen müssen, bis Hans 1000 Euro auf seinem Konto hat.</td>
</tr><tr><td>
<h3><img align=\"middle\" alt=\"showimage.php?formula=f4cf340afa2aab626b\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=f4cf340afa2aab626bf3845516371510.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»500«/mn»«mo»§#183;«/mo»«mn»1«/mn»«mo»,«/mo»«msup»«mn»025«/mn»«mi»t«/mi»«/msup»«mo»=«/mo»«mn»1000«/mn»«/math»\"></h3>
</td>
<td><img align=\"middle\" alt=\"showimage.php?formula=4c36f640692b62fbda\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=4c36f640692b62fbdabfc928104b185c.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«menclose notation=¨left¨»«mo»:«/mo»«mo»§#160;«/mo»«mn»500«/mn»«/menclose»«/math»\"></td>
</tr><tr><td>
<h3><img align=\"middle\" alt=\"showimage.php?formula=cdaec7b2a9489fb7a4\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=cdaec7b2a9489fb7a4d93f3c8af8116c.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mn»1«/mn»«mo»,«/mo»«msup»«mn»025«/mn»«mi»t«/mi»«/msup»«mo»=«/mo»«mn»2«/mn»«/math»\"></h3>
</td>
<td><img align=\"middle\" alt=\"showimage.php?formula=c0a891b044b31a5881\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=c0a891b044b31a588168ec5b0c71e3f1.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«menclose notation=¨left¨»«mo»§#183;«/mo»«mi»log«/mi»«mfenced»«mrow/»«/mfenced»«/menclose»«/math»\"> <a href=\"/math/wiki/article/view/logarithmus\" class=\"frontbox\">Logarithmiere.</a></td>
</tr><tr><td>
<h3><img align=\"middle\" alt=\"showimage.php?formula=ae9b29e1dc930bae19\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=ae9b29e1dc930bae1994accc50d98f25.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«msub»«mi»log«/mi»«mrow»«mn»1«/mn»«mo»,«/mo»«mn»025«/mn»«/mrow»«/msub»«mfenced»«mn»2«/mn»«/mfenced»«mo»=«/mo»«mi»t«/mi»«/math»\"></h3>
</td>
<td></td>
</tr><tr><td><img align=\"middle\" alt=\"showimage.php?formula=2e0b668d5b47dc070b\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=2e0b668d5b47dc070b4b09c32596d687.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mi»t«/mi»«mo»§#8776;«/mo»«mn»28«/mn»«mo»,«/mo»«mn»2«/mn»«mo»§#160;«/mo»«/math»\">Jahre</td>
<td></td>
</tr><tr><td colspan=\"2\"><img align=\"middle\" alt=\"showimage.php?formula=f8ff39db72b5abe07f\" class=\"Wirisformula\" src=\"http://www.serlo.org/scripts/libs/tiny_mce/plugins/tiny_mce_wiris/integration/showimage.php?formula=f8ff39db72b5abe07fe58838cd882049.png\" data-mathml=\"«math xmlns=¨http://www.w3.org/1998/Math/MathML¨»«mo»§#8658;«/mo»«/math»\">Im 29 Jahr besitzt Hans 1000 Euro auf seinem Konto.</td>
</tr></tbody></table>";
}