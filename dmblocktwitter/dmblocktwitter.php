<?php

/* -----------------------------------------------------------
 * Simple Twitter Block for PrestaShop 1.6
 * 
 * David Martín
 * -----------------------------------------------------------     
 */

if (!defined('_PS_VERSION_'))
    exit;

class DmBlockTwitter extends Module {

    public function __construct() {

        $this->name = 'dmblocktwitter';
        $this->tab = 'social_networks';
        $this->version = '0.4';
        $this->author = 'David Martín';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Simple Twitter Block');
        $this->description = $this->l('A simple Twitter Block for PrestaShop 1.6');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');                       
    }

    /* -----------------------------------------------------------
     * Install
     * -----------------------------------------------------------
     * Default: displayHeader, displayFooter    
     */
    public function install() {

        return parent::install() &&
           $this->registerHook('displayHeader') &&
           $this->registerHook('displayFooter') &&
           $this->initConfig();
    }

    /* -----------------------------------------------------------
     * Uninstall
     * -----------------------------------------------------------     
     */
    public function uninstall() {

        return Configuration::deleteByName($this->name) &&
               parent::uninstall();
    }

   /* -----------------------------------------------------------
     * Set the default values.
     * -----------------------------------------------------------     
     */
    protected function initConfig() {

        $config = array();
        
        $config['user'] = '';
        $config['widget_id'] = '';
        $config['tweets_limit'] = 3;
        $config['follow_btn'] = 'on';
        
        return Configuration::updateValue($this->name, json_encode($config));
    }

    /* -----------------------------------------------------------
     * 
     * -----------------------------------------------------------     
     */
    public function getConfigFieldsValues() {

        return json_decode( Configuration::get($this->name), true );
    }
    
    /* -----------------------------------------------------------
     * DisplayHeader Hook
     * -----------------------------------------------------------
     * Fetch your twitter posts without using the new Twitter 1.1 API. Pure JavaScript! By Jason Mayes 
     * https://github.com/jasonmayes/Twitter-Post-Fetcher     
     */
    public function hookHeader() {
        
        $this->context->controller->addCSS(($this->_path) . 'views/css/dm-block-twitter.css');                
        $this->context->controller->addJS(($this->_path)  . 'views/js/twitterFetcher.js');
        $this->context->controller->addJS(($this->_path) . 'views/js/dm-block-twitter.js');
    }

    /* -----------------------------------------------------------
     * DisplayFooter Hook
     * -----------------------------------------------------------     
     */
    public function hookFooter() {

        $config = json_decode(Configuration::get($this->name), true);

        $this->context->smarty->assign(array(
            'user'          => $config['user'],
            'widget_id'     => $config['widget_id'],
            'tweets_limit'  => $config['tweets_limit'],
            'follow_btn'    => $config['follow_btn']
        ));

        return $this->display(__FILE__, 'dm-block-twitter.tpl');
    } 

    /* -----------------------------------------------------------
     * 
     * -----------------------------------------------------------     
     */
    public function getContent() {

        $output = '';

        $output .= $this->display(__FILE__, 'info.tpl');

        if (Tools::isSubmit('saveBtn')){

            $config = array();
            
            $config['user'] = Tools::getValue('user');
            $config['widget_id'] = Tools::getValue('widget_id');
            $config['tweets_limit']  = Tools::getValue('tweets_limit');
            $config['follow_btn'] = Tools::getValue('follow_btn');
        
            Configuration::updateValue($this->name, json_encode($config));
            
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . $this->displayForm();
    }
    
    /* -----------------------------------------------------------
     * 
     * -----------------------------------------------------------     
     */
    public function displayForm() {

       $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $fields_form = array(

            'form' => array(
                'legend' => array(
                    'title' => $this->l('Simple Twitter Block'),
                    'icon'  => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type'  => 'text',
                        'label' => $this->l('User'),
                        'name'  => 'user',
                        'class' => 'fixed-width-lg'
                    ),
                    array(
                        'type'  => 'text',
                        'label' => $this->l('Widget ID'),
                        'name'  => 'widget_id',
                        'class' => 'fixed-width-lg'
                    ),
                    array(
                        'type'  => 'text',
                        'label' => $this->l('Tweets limit'),
                        'name'  => 'tweets_limit',
                        'class' => 'fixed-width-lg'
                    ),
                    array(
                        'type'      => 'switch',
                        'label'     => $this->l('Show Follow button'),
                        'name'      => 'follow_btn',
                        'values'    => array(
                            array(
                                'id'    => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Yes')
                            ),
                            array(
                                'id'    => 'active_off',
                                'value' => 0,
                                'label' => $this->l('No')
                            )
                        ),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'pull-right'
                )
            )
        );

        $helper = new HelperForm();
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;

        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        $helper->title = $this->displayName;
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->submit_action = 'saveBtn';
        
        $this->fields_form = array();
        $helper->identifier = $this->identifier;        
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id
        );

        return $helper->generateForm(array($fields_form));
    }
}
