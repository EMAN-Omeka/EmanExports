<?php
// Collect all items shown in order and add their sub-items
$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
    
$allItems =  get_records('Item', $params, 10000);
  
$teiXml = new Output_ItemContainerTeiXml($allItems, 'itemContainer');
echo $teiXml->getDoc()->saveXML();
