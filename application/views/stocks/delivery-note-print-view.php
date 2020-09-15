<?php

function PrintHeader($num ="", $cust = "", $date  = "", $shopcode = "", $shopname = "",$employee_code = "", $paymentmethod = "")
{
    print "
        <!-- print header --> 
        <table border='0' style='font-size:12px;'>
            <tbody>
            <tr>
                <td width='20'>&nbsp;</td>
                <td width='750'>&nbsp;</td>
                <td width='450'>&nbsp;</td>
                <td width='200'>&nbsp;</td>
                <td width='200'>&nbsp;</td>
                <td width='150'>&nbsp;</td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td>&nbsp;</td></tr>

            <tr>
                <td></td>
                <td rowspan='3' height='115' valign='top'>
                    ".$cust['name']."<br>
                    ".$cust['delivery_addr']."
                </td>
                <td valign='top'>&nbsp;</td>
                <td>".substr($date,0,-8)."</td>
                <td>&nbsp;</td>
                <td>".$num."</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>".$cust['cust_code']."</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>".$employee_code."</td>
                <td>&nbsp;</td>
                <td>".$paymentmethod."</td>
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
            <tr><td valign='top'>&nbsp;</td></tr>
            <tr>
                <td valign='top'>
    ";
    foreach($items as $k => $v)
    {
        extract($v);
        print "<table style='font-size:12px;'>";
        print "<tr>";
        print "<td width='20' height='50'>&nbsp;</td>";
        print "<td width='750'>".$item_code." ".$chi_name." ".$eng_name ."</td>";
        print "<td width='150'>".$unit."</td>";
        print "<td width='200'>".$qty."</td>";
        print "<td width='200'></td>";
        print "<td width='100'></td>";
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
                <td width='150'></td>
            </tr>
        </table>
    ";
}
?>

<div>
<?php
extract($data);

// echo "<pre>";
// var_dump($data);
// echo "</pre>";
if(isset($items)){
    $page_separate = 0;
    $i = 0;
    
    // divided items per page
    foreach($items as $k => $v)
    { 
        // fixed 8 items for each page, more is not allow
        if($i % 4==0 || $i == 0){
            $page_separate++;
        }
        $page[$page_separate][] = $v;
        $i++;
    }
    //generate the print template
    for($j=1; $j<=$page_separate; $j++)
    {
        $customer['cust_code'] = $cust_code;
        $customer['name'] = $cust_name;
        $customer['delivery_addr'] = $delivery_addr;
        PrintHeader($dn_num, $customer, $date, $shop_code, $shopname, $employee_code, $j);
        PrintBody($page[$j]);
        PrintFooter($total);
        print "<p style=\"page-break-after: always;\"></p>";
    }

    // echo "<pre>";
    // var_dump($page);
    // echo "</pre>";
}    
?>
</div>
<?php
if(!$preview):
?>
    <script type="text/javascript"> 
    window.print();
    </script> 
<?php
endif;
?>