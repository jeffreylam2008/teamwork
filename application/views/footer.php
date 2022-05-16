   
               <!-- </div>-->
            <!--</div>  end of col-sm-10 -->
            <!--<div class="footer"></div> --><!-- footer section -->
        <!--</div> end of row -->
        <?php
        if($this->config->item("DEBUG_MODE")){
           
            echo $this->component_network->GetIP();

            echo "<br>";
            echo "<pre>";
            var_dump($_COOKIE);
            echo "</pre>";

            echo "<pre>";
            var_dump($_SESSION);
            echo "</pre>";
        }
            
        ?>
    </body>
    
</html>