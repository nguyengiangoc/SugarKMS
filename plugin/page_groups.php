    <?php
        $objPage = new Page();
        if(!isset($data['params'])) {
            $groups = $data['groups'];
        } else {
            $groups = $objPage->getGroups('',array('order' => 'asc'));
        }
        
        
    ?>
    <form id="addPageGroupForm">
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" style="margin-bottom:0px;" data-object="page_group">
            <tr>
                <th class="borderRight" >+</th>
                <th class="borderRight" style="width:110px;">Group Name</th>
                <th>Pages</th>
                <th>Action</th>
            </tr>
            <tbody class="changeOrder" id="order">
            
        <?php 
            foreach($groups as $group) {
        ?>
                <tr data-id="<?php echo $group['id']; ?>" id="row-<?php echo $group['id']; ?>">
                    <td class="borderRight">
                        +
                    </td>
                    <td class="borderRight showPagesInGroup clickable">                        
                        <span style="font-weight:bold"><?php echo $group['name']; ?></span>    
                    </td>
                    <td class="clickable showPagesInGroup">
                        <?php echo count($objPage->getPages(array('group_id' => $group['id']))); ?>
                    </td>
                    <td>
                        <a href="#" class="confirmRemove">Remove</a>
                    </td>
                </tr>    
                           
            
        <?php } ?>
            </tbody>
                <tr class="formRow">
                    <td class="borderRight">
                    </td>
                    <td>
                        <input id="pageGroupName" name="name" type="text" style="width:80px;"/>
                    </td>
                    <td>
                        <input id="pageGroupcPage" name="cPage" type="text" style="width:70px;"/>
                    </td>
                    <td colspan="2">
                        <label for="btn" class="sbm sbm_blue" style="width:60px;">
                            <input type="submit" class="btn" value="Add" id="addPageGroupBtn" style="padding:0 10px;margin:auto;"/>
                        </label>
                    </td>
                    
                </tr> 
        </table> 
    </form>