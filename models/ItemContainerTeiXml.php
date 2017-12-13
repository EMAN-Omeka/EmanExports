<?php
/**
 * Omeka
 * 
 * @copyright Copyright 2007-2012 Roy Rosenzweig Center for History and New Media
 * @license http://www.gnu.org/licenses/gpl-3.0.txt GNU GPLv3
 */

/**
 * Generates the container element for items in the omeka-xml output format.
 * 
 * @package Omeka\Output
 */
class Output_ItemContainerTeiXml extends Omeka_Output_OmekaXml_AbstractOmekaXml
{
    /**
     * XML Schema instance namespace URI.
     */
    const XMLNS_XSI            = 'http://www.w3.org/2001/XMLSchema-instance';
    
    /**
     * Omeka-XML namespace URI.
     */
    const XMLNS                = 'http://omeka.org/schemas/omeka-xml/v5';
    
    /**
     * Omeka-XML XML Schema URI.
     */
    const XMLNS_SCHEMALOCATION = 'http://omeka.org/schemas/omeka-xml/v5/omeka-xml-5-0.xsd';
    
    
    /**
     * @param Omeka_Record_AbstractRecord|array $record
     * @param string $context The context of this DOM document.
     */
    public function __construct($record, $context)
    {
        $this->_record = $record;
        $this->_context = $context;
        $this->_doc = new DOMDocument('1.0', 'UTF-8');
        $this->_doc->formatOutput = true;
        $this->_buildNode();
    }

    /**
     * Set an element as root.
     * 
     * @param DOMElement $rootElement
     * @return DOMElement The root element, including required attributes.
     */
    protected function _setRootElement($rootElement)
    {
        $rootElement->setAttribute('xmlns', self::XMLNS);
        $rootElement->setAttribute('xmlns:xsi', self::XMLNS_XSI);
        $rootElement->setAttribute('xsi:schemaLocation', self::XMLNS . ' ' . self::XMLNS_SCHEMALOCATION);
        $rootElement->setAttribute('uri', $this->_buildUrl());
        $rootElement->setAttribute('accessDate', date('c'));
        return $rootElement;
    }
        
    /**
     * Create a node to contain Item nodes.
     *
     * @see Output_ItemOmekaXml
     * @return void
     */
    protected function _buildNode()
    {
        $itemContainerElement = $this->_createElement('itemContainer');
        
        $this->_setContainerPagination($itemContainerElement);
        
        foreach ($this->_record as $item) {
            $itemTeiXml = new Output_ItemTeiXml($item, $this->_context);
            $itemElement = $this->_doc->importNode($itemTeiXml->_node, true);
            $itemContainerElement->appendChild($itemElement);
        }
        $this->_node = $itemContainerElement;
    }
}
