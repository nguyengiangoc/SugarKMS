<?php
    $objPage = new Page();
    $objTeam = new Team();
    $objPosition = new Position();
    if(!isset($data['params'])) {
        $page = $data['action'];
    } else {
        $params = $data['params'];
        $id = $params['id'];
        $page = $objPage->getPages(array('id' => $id))[0];
        $page_params = $objPage->getPageParams(array('page_id' => $id),array('order' => 'asc'));
    }
    
    
    $group_name = $objPage->getGroups(array('id' => $page['group_id']))[0]['name'];
    
    
?>  
        <div class="sectionParams" data-params="id=<?php echo $id; ?>"></div>
        <h2 class="borderBottom"><?php echo ucfirst($group_name); ?> :: <?php echo ucwords($page['name']); ?></h2>
        
        <table  cellpadding="0" cellspacing="0" border="0" style="width:100%;" class="panelTable horizontalTable" data-object="page">
                    
            <tr>
                <td style="width:50%;">
                    <br />
                    <h4>JavaScript Includes</h4>
                    <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat horizontalTable" style="display: inline-block;" data-object="page"  data-id="<?php echo $page['id']; ?>">
                        <tr>
                             
                        </tr>
                        <tr>
                            <th class="borderTop">jQuery</th>
                            <td class="borderRight borderTop">
                                <a href="#" class="toggleYesNo" data-field="jquery" data-value="<?php echo $page['jquery']; ?>"><?php echo $page['jquery'] == 1 ? 'Yes' : 'No'; ?></a>
                            </td>  
                        </tr>
                        <tr>
                            <th>jQueryUI</th>
                            <td class="borderRight">
                                <a href="#" class="toggleYesNo" data-field="jquery_ui" data-value="<?php echo $page['jquery_ui']; ?>"><?php echo $page['jquery_ui'] == 1 ? 'Yes' : 'No'; ?></a>
                            </td> 
                        </tr>
                         <tr>
                            <th class="borderBottomDark">Common.js</th>        
                            <td class="borderRight borderBottomDark" style="width:40px;">
                                <a href="#" class="toggleYesNo" data-field="common_js" data-value="<?php echo $page['common_js']; ?>"><?php echo $page['common_js'] == 1 ? 'Yes' : 'No'; ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>TableDnD</th>
                            <td class="borderRight">
                                <a href="#" class="toggleYesNo" data-field="tablednd" data-value="<?php echo $page['tablednd']; ?>"><?php echo $page['tablednd'] == 1 ? 'Yes' : 'No'; ?></a>
                            </td> 
                        </tr>
                        <tr>
                            <th>LiveQuery</th>
                            <td class="borderRight">
                                <a href="#" class="toggleYesNo" data-field="livequery" data-value="<?php echo $page['livequery']; ?>"><?php echo $page['livequery'] == 1 ? 'Yes' : 'No'; ?></a>
                            </td> 
                        </tr>
                        
                        <tr>
                            <th>changeOrder.js</th>        
                            <td class="borderRight" style="width:40px;">
                                <a href="#" class="toggleYesNo" data-field="change_order" data-value="<?php echo $page['change_order']; ?>"><?php echo $page['change_order'] == 1 ? 'Yes' : 'No'; ?></a>
                            </td>
                            
                        </tr>
                    </table>
                </td>
                
                <td style="width:50%">
                    <br />
                    <h4>JavaScript Main Source</h4>
                    <?php $folders = scandir(ROOT_PATH.DS.'js'); ?>
                    <strong>Current directory</strong>: <?php echo empty($page['js_file_directory']) ? 'No file selected yet.' : $page['js_file_directory']; ?>
                    <p></p>
                    <strong>Change directory</strong>: <br />
                    <form>
                        <table>
                            <tr>
                                <td>Folder:</td>
                                <td>
                                    <select style="width:130px;" class="selectFolder" data-url="/sugarkms/mod/getFolderContent.php" data-type="js">
                                        <option></option>
                                        <?php foreach($folders as $folder) {
                                            if(strpos($folder, '.') == 0 && $folder != '.' && $folder !='..') {
                                                  ?>
                                                    <option><?php echo $folder; ?></option>
                                                <?php
                                                
                                            }
                                            
                                            ?>
                                            
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>File:</td>
                                <td>
                                    <select style="width:200px;" disabled="" class="selectFile">
                                        
                                    </select>
                                </td>
                            </tr>
                        </table>     
                        <label for="btn" class="sbm sbm_blue" style="width:80px; ">
                            <input type="submit" class="btn" value="Change" id="changeJSDirectory" style="padding:0 10px;" data-ds="<?php echo DS; ?>"/>  
                        </label>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <br />
                    <h4>PHP Params</h4>
                        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" style="display: inline-block;" data-object="page_params">
                            <tr>
                                <th style="width:10px;" class="borderRight">+</th>
                                <th style="width:50px;">Param</th>
                                <th style="width:130px;">Required Value</th>
                                <th>Action</th>
                            </tr>
                            <tbody id="order" class="changeOrder">
                            <?php 
                                if(!empty($page_params)) {
                                    foreach($page_params as $param) {
                                        ?>
                                            <tr data-id="<?php echo $param['id']; ?>" id="row-<?php echo $param['id']; ?>">
                                                <td class="borderRight">
                                                    +
                                                </td>
                                                <td class="showInputField" data-field="param" >
                                                    <?php echo $param['param']; ?>
                                                </td>                
                                                <td class="showInputField" data-field="required_value" >
                                                    <?php echo $param['required_value']; ?>
                                                </td>               
                                                <td >
                                                    <a href="#" class="confirmRemove">Remove</a>
                                                </td>                 
                                            </tr>
                                        <?php
                                    }
                                } else {
                                    ?>
                                        <tr>
                                            <td style="text-align:center" colspan="4">
                                                No params have been prodvided for this page.
                                            </td>                  
                                        </tr>
                                    <?php
                                }
                            ?>
                            </tbody>
                            <tr id="addParamsForm" >
                                <td class="borderRight">
                                </td>
                                <td >
                                    <input id="paramField" name="param" type="text" style="width:100%;box-sizing: border-box;"/>
                                </td>
                                <td >
                                    <input id="requiredField" name="required_value" type="text" style="width:100%;box-sizing: border-box;"/>
                                </td>
                                <td >
                                    <label for="btn" class="sbm sbm_blue">
                                        <input type="submit" class="btn" value="Add" id="addParamBtn" style="padding:0 10px;"/>
                                    </label>
                                </td>
                            </tr>
                        </table>   
                </td>
                <td>
                    <br />
                    <h4>PHP Main Source</h4>
                    <?php $folders = scandir(ROOT_PATH.DS.'pages'); ?>
                    <strong>Current directory</strong>: <?php echo empty($page['php_file_directory']) ? 'No file selected yet.' : $page['php_file_directory']; ?>
                    <p></p>
                    <strong>Change directory</strong>: <br />
                    <form>
                        <table>
                            <tr>
                                <td>Folder:</td>
                                <td>
                                    <select style="width:130px;" class="selectFolder" data-url="/sugarkms/mod/getFolderContent.php" data-type="php">
                                        <option></option>
                                        <?php foreach($folders as $folder) {
                                            if(strpos($folder, '.') == 0 && $folder != '.' && $folder !='..') {
                                                  ?>
                                                    <option><?php echo $folder; ?></option>
                                                <?php
                                                
                                            }
                                            
                                            ?>
                                            
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>File:</td>
                                <td>
                                    <select style="width:200px;" disabled="" class="selectFile">
                                        
                                    </select>
                                </td>
                            </tr>
                        </table>     
                        <label for="btn" class="sbm sbm_blue" style="width:80px; ">
                            <input type="submit" class="btn" value="Change" id="changePHPDirectory" style="padding:0 10px;" data-ds="<?php echo DS; ?>"/>  
                        </label>
                    </form>
                    <br />
                </div>
                
                    
                </td>
            </tr>
        </table>
        <br />
        
        <h4>Access Mode: </h4>
                <?php if($page['everyone']) {?>
                    Open to <strong>all</strong>. (<a href="#" class="changeAccessMode" data-everyone="1">Switch</a>)
                <?php } else {  ?>
                    Limited to <strong>few</strong>. 
                    <?php if($group_name != 'system') { ?>
                        (<a href="#" class="changeAccessMode" data-everyone="0">Switch</a>)
                    <?php } ?>
                <?php } ?>
                <p></p>
                <?php 
                if(!$page['everyone']) {
                    $inv_criteria = $objPage->getCriteria(array('page_id' => $page['id'], 'by_involvement' => 1));            
                ?>
                    <h4>Involvement Criteria:</h4> 
                    <form id="inv_criteria">                            
                        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" style="display: inline-block;" data-object="page_criteria">
                            <tr>
                                <th>EXCO / Project</th>
                                <th>Currency</th>
                                <th>Position</th>
                                <th>Team</th>
                                <th>Action</th>
                                
                                
                            </tr>
                            <tr style="background:#fff;">
                                <td >EXCO</td>
                                <td >Current</td>
                                <td >Admin</td>
                                <td >Technology</td>
                                <td >
                                    <a href="#" class="disabled">Remove</a>
                                </td>
                            </tr>
                            
                            <?php 
                                if(!empty($inv_criteria)) {
                                    foreach($inv_criteria as $criteria) {
                            ?>
                                <tr style="background:#fff;"  data-id="<?php echo $criteria['id']; ?>">
                                    <td ><?php echo $criteria['exco_project'] ? 'EXCO' : 'Project'; ?></td>
                                    <td ><?php echo $criteria['currency'] ? 'Current' : 'Former'; ?></td>
                                    <td ><?php echo $objPosition->getPositionById($criteria['position_id'])['name']; ?></td>
                                    <td ><?php echo $objTeam->getTeamById($criteria['team_id'])['name']; ?></td>
                                    <td >
                                        <a href="#" class="confirmRemove">Remove</a>
                                    </td>  
                                </tr>
                            <?php 
                                    } 
                                }
                            ?>
                            
                            <tr style="background:#fff;">
                                <td class=" ">
                                    <select name="exco_project" class="exco_project"  style="width: 75px;">
                                        <option class="typeBlank"></option>
                                        <option value="5">EXCO</option>
                                        <option value="6">Project</option>
                                    </select>
                                </td>
                               
                                <td class=" ">
                                    <select name="currency" class="currency" style="width: 140px;" >
                                        <option></option>
                                        <option value="1">Current</option>
                                        <option value="0">Former</option>
                                    </select>
                                </td>
                                <td class=" ">
                                    <select name="position" class="position" style="width: 140px;" disabled="">
                                        <option class="positionBlank"></option>
                                    </select>
                                </td>
                                <td class=" ">
                                    <select name="team" class="team" style="width: 130px;" disabled="" >
                                        <option class="teamBlank"></option>
                                    </select>
                                </td>
                                
                                <td class=" ">
                                    <label for="btn" class="sbm sbm_blue">
                                        <input type="submit" class="btn addInvolvementCriteria" value="Add" style="padding:0 10px;"/>
                                    </label>
                                </td>
                            </tr>
                        </table>
                    </form>            
                <?php
                    switch($group_name) {
                        case 'Members':
                            $other_criteria = $objPage->getCriteria(array('page_id' => $page['id'], 'by_involvement' => 0)); 
                ?>
                    
                
                
                                     
                                
                                <strong>Other Criteria</strong>:     
                                <form id="other_criteria">
                                    <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" style="display: inline-block">
                                        <tr>
                                            <th class="">Criteria</th>
                                            <th class="">Action</th>   
                                        </tr>
                                        <?php 
                                            if(!empty($other_criteria)) {
                                                foreach($other_criteria as $criteria) {
                                        ?>
                                            <tr  style="background:#fff;"  data-id="<?php echo $criteria['id']; ?>">
                                                <td class="">
                                                    <?php
                                                        switch($criteria['other']) {
                                                            case 1:
                                                                echo "The viewer is the same person as the subject.";
                                                            break;
                                                        }
                                                    ?>
                                                </td>
                                                <td class="">
                                                    <a href="#" class="confirmRemove">Remove</a>
                                                </td>  
                                            </tr>   
                                        <?php 
                                                }
                                            } else {?>
                                        <tr style="background:#fff;">
                                            <td class="" colspan="2" style="text-align:center">
                                                There are no other criteria.
                                            </td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <tr  style="background:#fff;">
                                            <td class="" >
                                                <select name="other" class="other" style="width: 300px;"  >
                                                    <option></option>
                                                    <option value="1">The viewer is the same person as the subject.</option>
                                                    
                                                </select>
                                            </td>
                                            
                                            <td class="" style="width:20px;">
                                                <label for="btn" class="sbm sbm_blue" style="width:50px;">
                                                    <input type="submit" class="btn" value="Add" id="addMemberOtherCriteria" style="padding:0 10px;"/>
                                                </label>
                                            </td>   
                                        </tr>              
                                    </table>   
                                </form>                     
                
                
                <?php
                        break;
                        
                
                ?>
                
        
                
            <?php
                    }
            ?>
            <?php
                }
            ?>

        

        

    
    
    
    
            
            
               
                                       
                        
                    
                         


        
    