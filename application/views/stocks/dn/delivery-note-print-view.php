<?php

function PrintHeader($num ="", $cust = "", $date  = "", $shopcode = "", $shopname = "",$employee_code = "", $page = "")
{
    print "
        <!-- print header --> 
        <BR><BR><BR><BR><BR><BR><BR><BR><BR><BR><BR>
        <table border='0' style='font-family:PMingLiU;' width='950' height='20'>
            <tbody>
            <tr>
                <td width='150'>&nbsp;</td>
                <td width='250'>&nbsp;</td>
                <td width='200'>&nbsp;</td>
                <td width='300'></td>
                <td width='100'></td>
            </tr>
            <tr>
                <td></td>
                <td><font size=3>".$cust['delivery_addr']."</td>
                <td>&nbsp;</td>
                <td><font size=3>".substr($date,0,-8)."</td>
                <td valign='top'><font size=3>".$num."</font></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td valign='top'>&nbsp;</td>
            </tr>
            <tr>
                <td colspan='2'>".$cust['delivery_addr']."</td>
                <td>&nbsp;</td>
                <td valign='top'></td>
                <td valign='top'>".$cust['cust_code']."</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td><font size=3></td>
                <td>&nbsp;</td>
                <td>".$employee_code."</td>
                <td>".$page."</td>
            </tr>
            </tbody>
        </table>
    ";
   // echo "<p style=\"page-break-after: always;\"></p>";
}





function PrintBody($items=[])
{
    print "
        <table border='0' width='950'>
            <tr>
                <td valign='top'>
                <BR><BR><BR><BR>
    ";
    print "<table style='font-family:PMingLiU; font-size:14px;'>";
    foreach($items as $k => $v)
    {
        extract($v);
        print "<tr>";
        print "<td width='440'><font size=3>".$item_code." ".$chi_name."<br><br> ".$eng_name ."</td>";
        print "<td width='50'></td>";
        print "<td width='10'></td>";
        print "<td width='100' valign='top'>".$unit."</td>";
        print "<td width='100'></td>";
        print "<td width='100' valign='top'>".$qty."</td>";
        print "</tr>";
        print "<tr>";
        print "<td><br></td>";
        print "</tr>";
    }	
    print "</table>";
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

function PrintRemark($remark = "")
{
    print "
    <!-- print footer --> 
    <table border='0' style='font-family:PMingLiU;'  width='950'>
        <tr>
            <td width='20'>".$remark."</td>
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
        if($i % 3==0 || $i == 0){
            $page_separate++;
        }
        $page[$page_separate][] = $v;
        $i++;
    }
    //generate the print template
    for($j=1; $j<=$page_separate; $j++)
    {
        $customer['cust_code'] = $cust_code;
        $customer['delivery_addr'] = $delivery_addr;
        $customer['name'] = $cust_name;
        PrintHeader($dn_num, $customer, $date, $shopcode, $shopname, $employee_code, $j);
        PrintBody($page[$j]);
        PrintFooter($total);
        PrintRemark($remark);
        PrintRemark($delivery_remark);
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