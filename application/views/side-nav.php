    <nav id="diy-sidebar">
        <ul class="list-unstyled components">
            <?php 
            echo traversal($sideNav);
            ?>
        </ul>
    </nav>
    <?php

    // echo "<pre>";
    // var_dump($sideNav);
    // echo "</pre>";


    //echo traversal($sideNav);
    function traversal($sideNav){
        $result = "";
        foreach($sideNav as $key => $val)
        {
            // one and many item on menu with Parent and Children
            if(isset($val["child"]))
            {
                $result .= "<li data-role='root'><a href='#".$val["name"]."' data-toggle='collapse' aria-expanded='false' >";
                $result .= $val["name"];
                $result .= "</a>";
                $result .= "<ul class='list-unstyled collapse' id='".$val["name"]."'>";
                $result .= traversal($val["child"]);
                $result .= "</ul>";
                $result .= "</li>";
            }
            // Single item on menu with no Children
            else{
                $result .= "<li data-role='child'>";
                $result .= "<a href='".base_url($val["slug"])."'>";
                $result .= $val["name"];
                $result .= "</a>";
            }
            $result .= "</li>";
        }
        return $result;
    }
    ?>
