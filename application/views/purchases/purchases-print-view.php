<?php

function PrintHeader($transnum ="", $supp = "", $date  = "", $shopcode = "", $shopname = "",$employee_code = "", $paymentmethod = "")
{
    print "
        <!-- print header --> 
        <table border=1 style='font-size:14px;'>
            <tbody>
            <tr>
                <td valign='top' width='160'>Purchase Number</td>
                <td width='350'>
                    ".$transnum."
                </td>
                <td width='160'>Date</td>
                <td width='400'>
                    ".substr($date,0,-8)."
                </td>
            </tr>
            <tr>
                <td>Supplier Name</td>
                <td>".$employee_code."</td>
                <td>Shop</td>
                <td>".$shopcode." - ".$shopname."</td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td>".$supp."</td>
                <td>Payment Method</td>
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
        <table border='1'>
            <tr>
                <td>#</td>
                <td>Item</td>
                <td>Unit</td>
                <td>QTY</td>
                <td>Price</td>
                <td>Subtotal</td>
            </tr>
    ";
    foreach($items as $k => $v)
    {
        extract($v);
        print "<tr>";
        print "<td width='20' height='70'>".($k+1)."</td>";
        print "<td width='650'>(".$item_code.") ".$chi_name." ".$eng_name ."</td>";
        print "<td width='200'>".$unit."</td>";
        print "<td width='200'>".$qty."</td>";
        print "<td width='200'>$".$price."</td>";
        print "<td width='100'>$".$subtotal."</td>";
        print "</tr>";
    }	
    print "
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
                <td width='240'>Total: </td>
                <td width='150'>$".$total."</td>
            </tr>
        </table>
    ";
}
?>

<div>
<?php
extract($data);
//print_r($data);
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
        PrintHeader($purchases_num, $supp_name, $date, $shopcode, $shopname, $employee_code, $paymentmethodname);
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