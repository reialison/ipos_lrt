    <body class='skin-red' 
            <?php
                if(isset($problem) && $problem != ""){
                    
                    echo "problem='".$problem."'";
                }
            ?>
        >
        <?php if(!isset($noNavbar)): ?>
            
        <?php endif; ?>

        <style>
            #print-rcp{display: none;}
        </style>
        <div id="print-rcp"></div>
        
        <div class="wrapper cashier-wrapper row-offcanvas row-offcanvas-left" style="">
        <?php 
            if(isset($code))
                echo $code; 
        ?>
        </div>       
        
    </body>
    <?php
        if(isset($js))
            echo $js;
        ?> 
        <?php 
            if(isset($add_js)){
                if(is_array($add_js)){
                    foreach ($add_js as $path) {
                        echo '<script src="'.base_url().$path.'" type="text/javascript"  language="JavaScript"></script>';
                    }
                }
                else
                     echo '<script src="'.base_url().$add_js.'" type="text/javascript"  language="JavaScript"></script>';
            }
        ?> 