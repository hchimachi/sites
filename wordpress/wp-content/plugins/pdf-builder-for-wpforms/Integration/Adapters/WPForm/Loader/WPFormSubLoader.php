<?php


namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Loader;
use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rednaoformpdfbuilder\pr\PRLoader;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;

class WPFormSubLoader extends Loader
{

    public function __construct($rootFilePath,$config)
    {
        $this->ItemId=12;
        $prefix='rednaopdfwpform';
        $formProcessorLoader=new WPFormProcessorLoader($this);
        $formProcessorLoader->Initialize();
        parent::__construct($prefix,$formProcessorLoader,$rootFilePath,$config);
        $this->AddMenu('WPForm PDF Builder',$prefix.'_pdf_builder','manage_options','','Pages/BuilderList.php');
        \add_filter('wpforms_frontend_confirmation_message',array($this,'AddPDFLink'),10,2);
        if($this->IsPR())
        {
            $this->PRLoader=new PRLoader($this);
        }else{
            $this->AddMenu('Entries',$prefix.'_pdf_builder_entries','manage_options','','Pages/EntriesFree.php');
        }
    }

    public function AddPDFLink($message,$formData)
    {
        global $RNWPCreatedEntry;
        if(!isset($RNWPCreatedEntry['CreatedDocuments']))
            return $message;

        if(\strpos($message,'[wpformpdflink]')===false)
            return $message;

        $links=array();
        foreach($RNWPCreatedEntry['CreatedDocuments'] as $createdDocument)
        {
            $data=array(
              'entryid'=>$RNWPCreatedEntry['EntryId'],
              'templateid'=>$createdDocument['TemplateId'],
              'nonce'=>\wp_create_nonce($this->Prefix.'_'.$RNWPCreatedEntry['EntryId'].'_'.$createdDocument['TemplateId'])
            );
            $url=admin_url('admin-ajax.php').'?data='.\json_encode($data).'&action='.$this->Prefix.'_view_pdf';
            $links[]='<a target="_blank" href="'.esc_attr($url).'">'.\esc_html($createdDocument['Name']).'.pdf</a>';
        }

        $message=\str_replace('[wpformpdflink]',\implode($links),$message);

        return $message;


    }

    /**
     * @return WPFormEntryRetriever
     */
    public function CreateEntryRetriever()
    {
        return new WPFormEntryRetriever($this);
    }



    public function AddAdvertisementParams($params)
    {
        if(\get_option($this->Prefix.'never_show_add',false)==true)
        {
            $params['Text']='';

        }else
        {
            $params['Text'] = 'Already have a pdf and just want to fill it with the form information?';
            $params['LinkText'] = 'Try PDF Importer for WPForms';
            $params['LinkURL'] = 'https://wordpress.org/plugins/pdf-importer-for-wpform/';
            $params['Icon'] = $this->URL . 'images/adIcons/wpform.png';
        }
        return $params;
    }

    public function AddBuilderScripts()
    {
        $this->AddScript('wpformbuilder','js/dist/WPFormBuilder_bundle.js',array('jquery', 'wp-element','@builder'));
    }

    public function GetPurchaseURL()
    {
        return 'http://pdfbuilder.rednao.com/getit/';
    }
}


