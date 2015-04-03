<?php 
    $objForm = new Form();
    $objValid = new Validation($objForm);
    $objCatalogue = new Catalogue();
    $category = $objCatalogue->getCategories();
    if($objForm->isPost('name')) {
        $objValid->_expected = array(
            'category',
            'name',
            'description',
            'price',
            'weight',
            'identity',
            'meta_title',
            'meta_description',
            'meta_keywords'
        );
        $objValid->_required = array(
            'category',
            'name',
            'description',
            'price',
            'weight',
            'identity',
            'meta_title',
            'meta_description',
            'meta_keywords'
        );
        if($objValid->isValid()) {
            $objValid->_post['identity'] = Helper::cleanString($objValid->_post['identity']);
            
            if($objCatalogue->isDuplicateProduct($objValid->_post['identity'])) {
                $objValid->add2Errors('duplicate_identity');
            } else {
                if($objCatalogue->addProduct($objValid->_post)) {
                    //$objUpload = new Upload();
                    //echo $objUpload->upload(CATALOGUE_PATH);
                    $objUpload = new Upload();
                    if($objUpload->upload(CATALOGUE_PATH)) {
                        $objCatalogue->updateProduct(array('image' => $objUpload->_names[0]), $objCatalogue->_id);
                        //neu upload duoc anh thanh cong thi cho duong dan cua anh vao trong database
                        Helper::redirect('/ecommerce/'.$this->objURL->getCurrent(array(action, id), false, array('action', 'added')));
                        //tuc la lay phan param page=products, bo cai action=add, id=bao nhieu day
                    } else {
                        Helper::redirect('/ecommerce/'.$this->objURL->getCurrent(array(action, id), false, array('action', 'added-no-upload')));
                    }
                } else {
                    Helper::redirect('/ecommerce/'.$this->objURL->getCurrent(array(action, id), false, array('action', 'added-failed')));
                }
            }
            
                
        }
    }
    require_once('_header.php'); 
?>
<h1>Products :: Add</h1>
<form action="" method="post" enctype="multipart/form-data">
    <?php //echo '<pre>'.print_r($_FILES).'</pre>';  ?>
    <table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
        <tr>
            <th><label for="category">Category: *</label></th>
            <td><?php echo $objValid->validate('category'); ?><select name="category" id="category" class="sel">
                <option value="">Select one&hellip;</option>
                <?php 
                    if(!empty($category)) {
                        foreach($category as $cat) {
                ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $objForm->stickySelect('category', $cat['id']); ?>><?php echo Helper::encodeHTML($cat['name']); ?></option>
                <?php
                        }  
                    } 
                ?>
            </select></td>
        </tr>
        <tr>
            <th><label for="name">Name: *</label></th>
            <td><?php echo $objValid->validate('name'); ?><input type="text" name="name" id="name" value="<?php echo $objForm->stickyText('name'); ?>" class="fld" /></td>
        </tr>
        <tr>
            <th><label for="description">Description: *</label></th>
            <td><?php echo $objValid->validate('description'); ?><textarea name="description" id="description" cols="" rows="" class="tar_fixed" ><?php echo $objForm->stickyText('description'); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="price">Price: *</label></th>
            <td><?php echo $objValid->validate('price'); ?><input type="text" name="price" id="price" value="<?php echo $objForm->stickyText('price', '0.00'); ?>" class="fld_price" /></td>
        </tr>
        <tr>
            <th><label for="weight">Weight: *</label></th>
            <td><?php echo $objValid->validate('weight'); ?><input type="text" name="weight" id="weight" value="<?php echo $objForm->stickyText('weight', '0.00'); ?>" class="fld_price" /></td>
        </tr>
        <tr>
            <th><label for="identity">Identity: *</label></th>
            <td><?php echo $objValid->validate('identity'); $objValid->validate('duplicate_identity'); ?><input type="text" name="identity" id="identity" value="<?php echo $objForm->stickyText('identity'); ?>" class="fld" /></td>
        </tr>
        <tr>
            <th><label for="meta_title">Meta title: *</label></th>
            <td><?php echo $objValid->validate('meta_title'); ?><input type="text" name="meta_title" id="meta_title" value="<?php echo $objForm->stickyText('meta_title'); ?>" class="fld" /></td>
        </tr>
        <tr>
            <th><label for="meta_description">Meta description: *</label></th>
            <td><?php echo $objValid->validate('meta_description'); ?><textarea name="meta_description" id="meta_description" class="tar_fixed" ><?php echo $objForm->stickyText('meta_description'); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="meta_keywords">Meta keywords: *</label></th>
            <td><?php echo $objValid->validate('meta_keywords'); ?><textarea name="meta_keywords" id="meta_keywords" class="tar_fixed" ><?php echo $objForm->stickyText('meta_keywords'); ?></textarea></td>
        </tr>
        <tr>
            <th><label for="image">Image: *</label></th>
            <td><?php echo $objValid->validate('image'); ?><input type="file" name="image" id="image" size="30" /></td>
        </tr>
        <tr>
            <th>&nbsp;</th>
            <td><label for="btn" class="sbm sbm_blue fl_l"><input type="submit" id="btn" class="btn" value="Add" /></label></td>
        </tr>
    </table>
</form>

<?php require_once('_footer.php'); ?>