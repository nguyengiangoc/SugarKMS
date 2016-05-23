        <?php
            if(!isset($data['params'])) {
                
            } else {
                $params = $data['params'];
                if(isset($params['id']) && !empty($params['id'])) {
                    $id = $params['id']; 
                    $objPage = new Page();
                    $groups = $objPage->getGroups(array('id' => $id));
                    if(!empty($groups)) {
                        $group = $groups[0];
                        $pages = $objPage->getPages(array('group_id' => $id), array('everyone' => 'desc', 'order' => 'asc'));
                    ?>
                    
                    <div class="sectionParams" data-params="id=<?php echo $id; ?>"></div>
                        <h2 class="borderBottom">Group :: <?php echo $group['name']; ?></h2>
                        <br />
                        <strong>Details</strong><br />
                        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" data-object="page_group" style="width:auto;">
                            <tr>
                                <th class="borderRight" style="width:110px;">Group Name</th>
                                <th style="width:90px;">URL cPage</th>
                            </tr>
                            <tr data-id="<?php echo $id; ?>">
                                <td class="borderRight showInputField" data-field="name" >                        
                                    <span><?php echo $group['name']; ?></span>    
                                </td>
                                <td class="showInputField" data-field="cPage">
                                    <?php echo $group['cPage']; ?> 
                                </td>
                            </tr>
                        </table>
                        <br />
                        <strong>List of Pages</strong><br />
                        <form id="addPageForm">
                            <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" data-object="page" style="width:auto;">
                                <tr>
                                    <th class="borderRight" style="width:auto;" >+</th>
                                    <th class="borderRight" style="width:35%;">Page Name</th>
                                    <th style="width:auto;">Default</th>
                                    <th style="width:auto;">Move</th>
                                    <th style="width:auto;" >Remove</th>
                                </tr>
                                <?php if(count($pages) == 0) {?>
                                    <tr>
                                        <td colspan="3" style="text-align:center">There are no pages in this group yet.</td>
                                    </tr>
                                <?php } else {?>
                                    <tbody class="changeOrder" id="order">
                                        <?php 
                                            
                                            foreach($pages as $page) {
                                                ?>
                                                <tr data-id="<?php echo $page['id']; ?>" id="row-<?php echo $page['id']; ?>">
                                                    <td class="borderRight">
                                                        +
                                                    </td>
                                                    <td class="borderRight showInputField" data-field="name">
                                                        <?php echo $page['name']; ?>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="toggleYesNo" data-field="default" data-value="<?php echo $page['default']; ?>">
                                                            <?php echo $page['default'] ? 'Yes' : 'No'; ?>
                                                        </a> 
                                                    </td>
                                                    <td>
                                                        <select class="chooseGroup" style="width:90px;">
                                                            <option></option>
                                                            <?php 
                                                                $groups = $objPage->getGroups('',array('order' => 'asc'));
                                                                foreach($groups as $group) {
                                                                    ?>
                                                                        <option value="<?php echo $group['id']; ?>"><?php echo $group['name'] ?></option>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </select>  
                                                    </td>
                                                    <td >
                                                        <a href="#" class="confirmRemove">Remove</a>
                                                        
                                                    </td>
                                                </tr>
                                        <?php } ?>
                                    </tbody>
                                <?php }?>
                                    <tr class="formRow">
                                        <td class="borderRight">
                                        </td>
                                        <td>
                                            <input id="pageName" name="name" type="text" style="width:90%;"/>
                                        </td>
                                        <td colspan="4">
                                            <label for="btn" class="sbm sbm_blue" style="width:60px;">
                                                <input type="submit" class="btn" value="Add" id="addPageBtn" style="padding:0 10px;margin:auto;"/>
                                                <input type="hidden" name="group_id" value="<?php echo $id; ?>" />
                                            </label>
                                        </td>
                                        
                                    </tr> 
                                
                                
                    
                            </table> 
                        </form>
                        <?php
                        } else {
                        ?>
                            Click a group to see the pages in it.
                        <?php    
                        }
                        
                        ?>
                    <?php
                } else {
                    ?>
                    Click a group to see the pages in it.
                    <?php
                }
                               
            }
            
            
        ?>
        