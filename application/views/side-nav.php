    <nav id="diy-sidebar">
        <ul class="list-unstyled components">
            <?php 
            echo traversal($sideNav,$path, $param);
            ?>
        </ul>
    </nav>
    <?php

    // echo "<pre>";
    // var_dump($path);
    // echo "</pre>";
    // echo "<pre>";
    // var_dump($slug);
    // echo "</pre>";

    //echo traversal($sideNav);

    function traversal($sideNav, $path, $param=""){
        $result = "";
        foreach($sideNav as $key => $val)
        {
            // one and many item on menu with Parent and Children
            if(isset($val["child"]))
            {
                if(in_array($val["name"],$path))
                {
                    $result .= "<li><a href='#".$val["name"]."' data-toggle='collapse' aria-expanded='true' >";
                }
                else{
                    $result .= "<li><a href='#".$val["name"]."' data-toggle='collapse' aria-expanded='false' >";
                }
                
                $result .= $val["name"];
                $result .= "</a>";
                if(in_array($val["name"],$path))
                {
                    $result .= "<ul class='list-unstyled collapse show' id='".$val["name"]."'>";
                }
                else
                {
                    $result .= "<ul class='list-unstyled collapse' id='".$val["name"]."'>";
                }
                $result .= traversal($val["child"],$path, $param );
                $result .= "</ul>";
                $result .= "</li>";
            }
            // Single item on menu with no Children
            else{
                
                if(strcmp($val["param"],$param) == 0)
                {
                    $result .= "<li class='active'>";
                }
                else
                {
                    $result .= "<li>";
                }
                $result .= "<a href='".base_url($val["slug"])."'>";
                $result .= $val["name"];
                $result .= "</a>";
            }
            $result .= "</li>";
        }
        return $result;
    }
    ?>
