<?php
// Collect all items shown in order and add their sub-items
$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    
$allItems =  get_records('Item', $params, 10000);

/*
foreach ($items as $item) {
    $allItems = array_merge($allItems, CsvExport_ItemAttachUtil::getThisAndAnnotations($item));
}
*/
// Render CSV
printCsvExport($allItems);
