
<?php
function PrintHeader($date  = "", $employee_code = "", $num = "")
{
    print "
        <!-- print header --> 
        <table border='1' style='font-size:16px;'>
            <tbody>
            <tr>
                <td valign='top'>Date </td>
                <td>".substr($date,0,-8)."</td>
                <td>Stocktake Number</td>
                <td>".$num."</td>
            </tr>
            <tr>
                <td>Employee </td>
                <td>".$employee_code."</td>
                <td>&nbsp;</td>
                <td></td>
            </tr>
            </tbody>
        </table>
    ";
   // echo "<p style=\"page-break-after: always;\"></p>";
}
    
function PrintBody($items=[])
{
    print "
        <table border='0' height='620'>
            <tr valign='top'>
                <td>
    ";
    print "
        <table style='font-size:14px;'>
            <tr>
                <td width='20'>#</td>
                <td width='700'>Item Code, name</td>
                <td width='200'>Quantity</td>
                <td width='200'>Unit</td>
            </tr>
        </table>
    ";
    
    foreach($items as $k => $v)
    {
        extract($v);
        print "<table style='font-size:14px;'>";
        print "<tr>";
        print "<td width='20' height='20'>".($k+1)."</td>";
        print "<td width='700'>".$item_code." ".$chi_name." ".$eng_name ."</td>";
        print "<td width='200'>".$qty."</td>";
        print "<td width='200'>".$unit."</td>";
        print "</tr>";
        print "</table>";
    }	
    print "
                </td>
            </tr>
        </table>
    ";
}

function PrintFooter($total="")
{
    print "
        <!-- print footer --> 
        <table border='0'>
            <tr>
                <td width='20'>&nbsp;</td>
                <td width='450'>&nbsp;</td>
                <td width='170'>&nbsp;</td>
                <td width='150'>&nbsp;</td>
                <td width='240'>&nbsp;</td>
                <td width='150'>$".$total."</td>
            </tr>
        </table>
    ";
}
?>

<div>
<?php
extract($data);
// echo "<pre>";
// print_r($data);
// echo "</pre>";
if(isset($items)){
    $page_separate = 1;
    $i = 1;
    // divided items per page
    foreach($items as $k => $v)
    { 
        $page[$page_separate][] = $v;
        // fixed 8 items for each page, more is not allow
        if($i % 8==0){
            $page_separate++;
        }
        $i++;
    }
    //generate the print template
    for($j=1; $j<=$page_separate; $j++)
    {
        PrintHeader( $date, $employee_code,$num);
        PrintBody($page[$j]);
        // PrintFooter($total);
        // print "<p style=\"page-break-after: always;\"></p>";
    }

    // echo "<pre>";
    // var_dump($page);
    // echo "</pre>";
}
?>
</div>