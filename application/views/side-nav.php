    <div id="diy-sidebar">

        <?php 
        echo traversal($sideNav, $path, $param);
        ?>
    </div>
    <?php

    // echo "<pre>";
    // var_dump($path);
    // echo "</pre>";
    // echo "<pre>";
    // var_dump($slug);
    // echo "</pre>";



    function traversal($sideNav, $path, $param=""){
        $result = "";
        foreach($sideNav as $key => $val)
        {
            // one and many item on menu with Parent and Children
            if(isset($val["child"]))
            {
                // children
                if(in_array($val["name"],$path))
                {
                    $result .= "<div class='sub1'><a href='#".$val["name"]."' data-toggle='collapse' aria-expanded='true' >";
                }
                else{
                    $result .= "<div class='sub1'><a href='#".$val["name"]."' data-toggle='collapse' aria-expanded='false' >";
                }
                $result .= $val["name"];
                $result .= "</a>";
                $result .= "</div>";
                if(in_array($val["name"],$path))
                {
                    $result .= "<div class='sub collapse show' id='".$val["name"]."'>";
                }
                else
                {
                    $result .= "<div class='sub collapse' id='".$val["name"]."'>";
                }
                $result .= traversal($val["child"],$path, $param);
                $result .= "</div>";
            }
            // Single item on menu with no Children
            else{
                
                if(strcmp($val["param"],$param) == 0)
                {
                    $result .= "<div class='sub1 active'>";
                }
                else
                {
                    $result .= "<div class='sub1'>";
                }
                $result .= "<a href='".base_url($val["slug"])."'> # ";
                $result .= $val["name"];
                $result .= "</a>";
                $result .= "</div>";
            }
        }
        return $result;
    }
    ?>
