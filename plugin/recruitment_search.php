    <?php
        $rows = $data['rows'];
        $objPage = new Page();
    ?>
    <div id="searchResult">
        <div class="dev borderTop">&#160;</div>
        <h2>Recruitment Search Result</h2>
        <table cellpadding="0" cellspacing="0" border="0" class="tbl_repeat" id="member_list">
            <tr>
                <th>Project</th>
                <th>Position</th>
                <th>Team</th>
                <th>Action</th>
                
            </tr>
            <?php 
                    if(!empty($rows)) { 
                        foreach($rows as $recruitment) { ?>
                            <tr> 
                                <td><?php echo $recruitment['project']; ?></a></td>
                                <td><?php echo $recruitment['position']; ?></td>
                                <td><?php echo $recruitment['team'] ?></td>
                                <td>
                                    <a href="<?php echo $objPage->generateURL('recruitment',array('id' => $recruitment['id'])); ?>" target="_blank">View</a>  
                                </td>
                                
                            </tr>
                <?php 
                        } 
                    } else { ?>
                            <tr> 
                                <td colspan="4" style="text-align:center;">
                                    There are no recruitments that match your criteria.
                                </td>
                            </tr>
            <?php } ?>
        </table>
    </div>