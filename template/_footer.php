</div>
            <div class="cl">&#160;</div>
        </div>
    </div>
    <div id="footer">
        <div id="footer_in">
            &copy; Nguyen Gia Ngoc
        </div>
    </div>
    <script src="/sugarkms/js/jquery.js"></script>
    <script src="/sugarkms/js/jquery-ui.js"></script>    
    <!--<script src="/sugarkms/js/jquery.livequery.js" type="text/javascript"></script>-->
    <?php 
        if(file_exists(ROOT_PATH.DS.'js'.DS.$this->objURL->cpage.'.js')) {
    ?>
        <script src="/sugarkms/js/<?php echo $this->objURL->cpage; ?>.js" type="text/javascript"></script>
    <?php
        }
    ?>
    <?php if($this->objURL->cpage == 'teams') { ?>
        <script src="/sugarkms/js/jquery.livequery.js" type="text/javascript"></script>
        <script src="/sugarkms/js/jquery.tablednd.0.7.min.js" type="text/javascript"></script>
    <?php } ?>
    
</body>


</html>