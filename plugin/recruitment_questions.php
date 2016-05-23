        <?php 
            if(isset($data['params'])) {
                $params = $data['params'];
                $id = $params['id'];
            } else {
                $id = $data['id'];
            }
            
            
            
        ?>
        <div class="sectionParams" data-params="id=<?php echo $id; ?>"></div>
    
        