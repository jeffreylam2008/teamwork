
<div style="width: 100%; padding-top:50px; margin: auto; border: 1px solid green;">

    <table style="width: 100%;" >
        <tr>
            <td style="width: 45%;">
                <?=$customer['name']?><br>
                <?=$customer['delivery_addr']?>
            </td>
            <td style="width: 10%;">
                
            </td>
            <td style="width: 10%;">
                <?=substr($invoicedate,0,-8)?>
            </td>
            <td style="width: 10%;"></td>
            <td style="width: 10%;">
                <?=$invoicenum?>
            </td>
        </tr>
        <tr style='height: 250px;'>
            <td></td>
        </tr>
    </table>
    <table style="width: 100%;" >
        <?php

        foreach($items as $k => $v)
        {
            extract($v);
            echo "<tr style='height: 70px;'>";
            echo "<td style='width: 1560px;' >".$item_code." ".$chi_name." ".$eng_name ."</td>";
            echo "<td style='width: 300px;'>".$unit."</td>";
            echo "<td style='width: 150px;'>".$qty."</td>";
            echo "<td style='width: 200px;'>$".$price."</td>";
            echo "<td style='width: 200px;'>$".$subtotal."</td>";
            echo "</tr>";
        }

        echo "<tr style='height: 70px;'>";
        echo "<td colspan='4'></td>";
        echo "<td style='width: 200px;'>$".number_format($total,2)."</td>";
        echo "</tr>";
        ?> 
    </table>

   
</div>
<script>
window.print();
</script>