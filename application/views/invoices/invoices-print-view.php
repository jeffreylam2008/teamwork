
<?php
function PrintHeader($customer = "", $date  = "", $quotation = "", $cust_code = "", $employee_code = "", $paymentmethod = "", $invoicenum = "")
{
    print "
        <!-- print header --> 
        <BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
        <table border='0' width='1100' height='170' style='font-family:PMingLiU;'>
            <tbody>
                <tr>
                    <td width='380'><font size=5><b>".$customer['name']."</b></font></td>
                    <td width='180'>&nbsp;</td>
                    <td width='130'><font size=5><b>".substr($date,0,-8)."</b></font></td>
                    <td width='150'>&nbsp;</td>
                    <td width='150'><font size=5><b>".$invoicenum."</b></font></td>
                </tr>

                <tr>
                    <td><font size=5><b>".$customer['delivery_addr']."</b></font></td>
                    <td>&nbsp;</td>
                    <td><font size=5><b>".$quotation."</b></font></td>
                    <td>&nbsp;</td>
                    <td><font size=5><b>".$cust_code."</b></font></td>
                </tr>
                <tr>
                    <td><font size=5><b>&nbsp;</b></FONT></td>
                    <td>&nbsp;</td>
                    <td><font size=5><b>".$employee_code."</b></FONT></td>
                    <td>&nbsp;</td>
                    <td><font size=5><b>".$paymentmethod."</b></FONT></td>
                </tr>
            </tbody>
        </table>
    ";
   // echo "<p style=\"page-break-after: always;\"></p>";
}
    
function PrintBody($items = [])
{
    print "-<br>-<br>-<br>-<br>";
    print "
        <table border='0' width='1100' height='750'>
            <tr valign='top'>
                <td width='100%'>
    ";
    foreach($items as $k => $v)
    {
        extract($v);
        print "<table height='50'>";
        print "<tr>";
        print "<td width='50'>&nbsp;</td>";
        print "<td width='500'><font size=5><b>".$item_code." ".$chi_name."<br>".$eng_name ."</b></font></td>";
        print "<td width='150'><font size=5><b>".$unit."</b></font></td>";
        print "<td width='120'><font size=5><b>".$qty."</b></font></td>";
        print "<td width='150'><font size=5><b>$".$price."</b></font></td>";
        print "<td width='130'><font size=5><b>$".$subtotal."</b></font></td>";
        print "</tr>";
        print "</table>";
    }	
    print "
                </td>
            </tr>
        </table>
    ";
}

function PrintFooter($total = "")
{
    print "
        <!-- print footer --> 
        <table border='0' width='1100'>
            <tr>
                <td width='860'>&nbsp;</td>
                <td><font size=5><b>$".$total."</b></font></td>
            </tr>
        </table>
    ";
}

function PrintRemark($body = "")
{
    print "
        <!-- deliver remark and invoice remark -->
        <table border='0' width='1100' height='100'>
            <tr valign='top'>
                <td width='100%'><font size=5><b>
                    ".$body."
                </b></font></td>
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
        if($i % 6==0 || $i == 0){
            $page_separate++;
        }
        $page[$page_separate][] = $v;
        // fixed 8 items for each page, more is not allow        
        $i++;      
    }
    //generate the print template
    for($j=1; $j<=$page_separate; $j++)
    {
        PrintHeader($customer, $date, $quotation, $cust_code, $employee_code, $paymentmethodname, $invoicenum);
        PrintBody($page[$j]);
        PrintRemark($customer['statement_remark']);
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