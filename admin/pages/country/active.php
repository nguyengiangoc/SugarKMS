<?php
    $active = $country['include'] == 1 ? 0 : 1;
    if($objCountry->update(array('include' => $active), $country['id'])) {
        $replace = '<a href="#" data-url="/ecommerce/'.$this->objURL->getCurrent().'" class="clickReplace">';
        $replace .= $active == 1 ? 'Yes' : 'No';
        $replace .= '</a>';
        echo Helper::json(array('error' => false, 'replace' => $replace));
    } else {
        throw new Exception('Record could not be updated');
    }
?>