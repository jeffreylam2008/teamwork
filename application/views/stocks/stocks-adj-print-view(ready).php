
<?php
function PrintHeader($customer = "", $date  = "", $quotation = "", $cust_code = "", $employee_code = "", $paymentmethod = "", $invoicenum = "")
{
    print "
        <!-- print header --> 
        <table border='1' style='font-size:16px;'>
            <tbody>
            <tr>
                <td width='20'>&nbsp;</td>
                <td width='500'>&nbsp;</td>
                <td width='350'>&nbsp;</td>
                <td width='150'>&nbsp;</td>
                <td width='200'>&nbsp;</td>
                <td width='150'>&nbsp;</td>
            </tr>

            <tr>
                <td></td>
                <td rowspan='3' height='120' valign='top'>
                    ".$customer['name']."<br>
                    ".$customer['delivery_addr']."
                </td>
                <td>&nbsp;</td>
                <td>
                    ".substr($date,0,-8)."
                </td>
                <td>&nbsp;</td>
                <td>
                    ".$invoicenum."
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    ".$quotation."
                </td>
                <td>&nbsp;</td>
                <td>
                    ".$cust_code."
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>
                    ".$employee_code."
                </td>
                <td>&nbsp;</td>
                <td>
                    ".$paymentmethod."
                </td>
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
    foreach($items as $k => $v)
    {
        extract($v);
        print "<table style='font-size:14px;'>";
        print "<tr>";
        print "<td width='20' height='70'>&nbsp;</td>";
        print "<td width='650'>".$item_code." ".$chi_name." ".$eng_name ."</td>";
        print "<td width='200'>".$unit."</td>";
        print "<td width='200'>".$qty."</td>";
        print "<td width='200'>$".$price."</td>";
        print "<td width='100'>$".$subtotal."</td>";
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
echo "<pre>";
print_r($data);
echo "</pre>";
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
        // PrintHeader($customer, $date, $quotation, $cust_code, $employee_code, $paymentmethodname, $invoicenum);
        // PrintBody($page[$j]);
        // PrintFooter($total);
        // print "<p style=\"page-break-after: always;\"></p>";
    }

    // echo "<pre>";
    // var_dump($page);
    // echo "</pre>";
}
?>
</div>