   
               <!-- </div>-->
            <!--</div>  end of col-sm-10 -->
            <!--<div class="footer"></div> --><!-- footer section -->
        <!--</div> end of row -->
        <?php
        if($this->config->item("DEBUG_MODE")){
            echo "<pre>";
            var_dump($_SESSION);
            echo "</pre>";

            echo $this->component_network->GetIP();
        }
            
        ?>
    </body>
    
</html>