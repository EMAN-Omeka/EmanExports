<?php
class EmanExportsPlugin extends Omeka_Plugin_AbstractPlugin
{
    protected $_hooks = array(
        'install',
        'uninstall',
        'config',
        'config_form',
    );
    
    protected $_filters = array(
        'response_contexts',
        'action_contexts',
    );
    
    /**
     * HOOK install: Set initial plugin configurations.
     */
    public function hookInstall() {
        set_option('csv_export_canonical_file_urls', 0);
    }
    
    /**
     * HOOK uninstall: Remove plugin configuration entries.
     */
    public function hookUninstall() {
        delete_option('csv_export_canonical_file_urls');
    }
    
    /**
     * HOOK config: Process configuration submissions.
     * @param array $args
     */
    public function hookConfig($args) {
        $post = $args['post'];
        set_option('csv_export_canonical_file_urls', $post['canonical_file_urls']);
    }
    
    /**
     * HOOK config_form: Render the plugin's configuration form.
     */
    public function hookConfigForm() {
        $form = new CsvExport_Form_Config();
        $form->removeDecorator('Form');
        $form->removeDecorator('Zend_Form_Decorator_Form');
        echo $form;
    }
    
    /**
     * FILTER respond_contexts: Adds the response MIME types for the CSV export format
     * @param array $contexts
     * @return array
     */
    public function filterResponseContexts($contexts) {
        $contexts['csv'] = array(
            'suffix' => 'csv',
            'headers' => array(
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename=export.csv'
            ),
        );
        $contexts['tei'] = array(
            'suffix' => 'tei',
            'headers' => array(
                'Content-Type' => 'text/xml; charset=utf-8',
                'Content-Type: application/xml'
            ),
        );
        return $contexts;
    }
    
    /**
     * FILTER action_contexts: Add CSV as an export on Items browse/show and Collections show actions
     * @param array $contexts
     * @param array $args
     * @return array
     */
    public function filterActionContexts($contexts, $args) {
        // Browse and show views for Items
        if ($args['controller'] instanceof ItemsController) {
            $contexts['browse'][] = 'csv';
            $contexts['browse'][] = 'tei';
            $contexts['show'][] = 'csv';
            $contexts['show'][] = 'tei';
        // Show view for Collections
        } elseif ($args['controller'] instanceof CollectionsController) {
            $contexts['show'][] = 'csv';
            $contexts['show'][] = 'tei';
        }
        return $contexts;
    }
}

// Plugin-wide setup
if (!defined('EMAN_EXPORTS_PLUGIN_DIR')) {
    define('EMAN_EXPORTS_PLUGIN_DIR', dirname(__FILE__));
}
require_once(EMAN_EXPORTS_PLUGIN_DIR . '/helpers/CsvExportFunctions.php');
require_once(EMAN_EXPORTS_PLUGIN_DIR . '/models/ItemTeiXml.php');
require_once(EMAN_EXPORTS_PLUGIN_DIR . '/models/ItemContainerTeiXml.php');


