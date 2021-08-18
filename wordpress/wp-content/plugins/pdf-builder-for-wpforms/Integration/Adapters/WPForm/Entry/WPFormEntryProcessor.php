<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/22/2019
 * Time: 5:03 AM
 */

namespace rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry;


use DateTime;
use DateTimeZone;
use Exception;
use rednaoformpdfbuilder\htmlgenerator\generators\FileManager;
use rednaoformpdfbuilder\htmlgenerator\generators\PDFGenerator;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormAddressEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormDateTimeEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormFileUploadEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\EntryItems\WPFormNameEntryItem;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\Entry\Retriever\WPFormEntryRetriever;
use rednaoformpdfbuilder\Integration\Adapters\WPForm\FormProcessor\WPFormFormProcessor;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\CheckBoxEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\DropDownEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\EntryItemBase;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\MultipleSelectionEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\RadioEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryItems\SimpleTextEntryItem;
use rednaoformpdfbuilder\Integration\Processors\Entry\EntryProcessorBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\Fields\FieldSettingsBase;
use rednaoformpdfbuilder\Integration\Processors\Settings\Forms\FormSettings;
use stdClass;

class WPFormEntryProcessor extends EntryProcessorBase
{
    public function __construct($loader)
    {
        parent::__construct($loader);


        \add_action('wpforms_post_insert_',array($this,'UpdateOriginalEntryId'),10,2);
        \add_action('wpforms_process_entry_save',array($this,'SaveEntry'),10,4);
        \add_action('wpforms_entry_email_process',array($this,'ProcessEmail'),10,5);
        \add_filter('wpforms_emails_send_email_data',array($this,'AddAttachmentNew'),10,2);
        add_action('wpforms_pro_admin_entries_edit_submit_completed',array($this,'EditEntry'),10,4);

        //\add_action('wpforms_email_attachments',array($this,'AddAttachment'),10,2);

        \add_filter(
            'wpforms_tasks_entry_emails_trigger_send_same_process',array($this,'SendSameProcess'));
        \add_shortcode('bpdfbuilder_download_link',array($this,'AddPDFLink'));
        \add_action('init',array($this,'MaybeStartSession'));
    }



    public function EditEntry($formData,$response,$uploadedfields,$entry){
        $formProcessor=new WPFormFormProcessor($this->Loader);
        $formSettings=$formProcessor->SerializeForm(array(
            "ID"=>$formData['id'],
            'post_title'=>'',
            'post_content'=>\json_encode(array('fields'=>$formData['fields']))
        ));
        global $wpdb;
        $formSettings->Id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable." where original_id=%d",$formSettings->OriginalId));
        if($formSettings->Id==null)
            return;

        if(!isset($entry->fields))
            return;

        $fields=\json_decode($entry->fields,true);
        foreach($uploadedfields as $key=>$value)
        {
            $fields[$key]=$value;
        }
        $serializeEntry=$this->SerializeEntry($fields,$formSettings);


        $pdfTemplates=array();
        if(isset($formData['meta']['pdfTemplates']))
            $pdfTemplates=$formData['meta']['pdfTemplates'];


        $entryId=$entry->entry_id;
        if(!\rednaoformpdfbuilder\Utils\Sanitizer::SanitizeBoolean(get_option($this->Loader->Prefix.'_skip_save',false)))
            $entryId=$this->SaveEntryToDB($formData['id'],$serializeEntry,$entryId,array('Fields'=>$fields));


    }

    public function MaybeStartSession(){
        if (!session_id()&&!headers_sent()) {
            session_start();
        }
    }

    public function AddPDFLink($attrs,$content){


        $message='Click here to download';
        if(isset($attrs['message']))
            $message=$attrs['message'];

        $templateId=null;
        $entryId=null;

        if(isset($attrs['templateid']))
            $templateId=$attrs['templateid'];

        if(isset($attrs['entryid']))
        {
            $entryId=$attrs['entryid'];
        }

        if($entryId==null||$templateId==null)
        {
            if(!isset($_SESSION['WPForm_Generated_PDF']))
                return;

            $pdfData=$_SESSION['WPForm_Generated_PDF'];

            if($entryId==null)
            {
                if(!isset($pdfData['EntryId']))
                    return;

                $entryId=$pdfData['EntryId'];
            }

            if($templateId==null)
            {
                if(!isset($pdfData['TemplateId']))
                    return;

                $templateId=$pdfData['TemplateId'];
            }

        }









        $nonce=\wp_create_nonce('view_'.$entryId.'_'.$templateId);
        $url=admin_url('admin-ajax.php').'?action='.$this->Loader->Prefix.'_view_pdf'.'&nonce='.\urlencode($nonce).'&templateid='.$templateId.'&entryid='.$entryId;
        return "<a target='_blank' href='$url'>".\esc_html($message)."</a>";

    }


    public function ProcessEmail($proces,$fields,$formdata,$notificationId,$context){
        global $WPFormEmailBeingProcessed;
        $WPFormEmailBeingProcessed=$notificationId;
        return $proces;
    }

    public function SendSameProcess($sameProcess)
    {
        return true;
    }
    public function UpdateOriginalEntryId($entryId,$formData)
    {
        if(!isset($formData['fields']))
            return;
        global $RNWPCreatedEntry;
        if(!isset($RNWPCreatedEntry)||!isset($RNWPCreatedEntry['Entry']))
            return;

        global $wpdb;
        $wpdb->update($this->Loader->RECORDS_TABLE,array(
            'original_id'=>$entryId
        ),array('id'=>$RNWPCreatedEntry['EntryId']));

    }

    public function SaveLittleEntry($fields,$entry,$formId,$formData,$entryId=0)
    {
        $this->SaveEntry($fields,$entry,$formId,$formData,0);
    }

    public function SaveEntry($fields,$entry,$formId,$formData,$entryId=0){
        $formProcessor=new WPFormFormProcessor($this->Loader);
        $formSettings=$formProcessor->SerializeForm(array(
            "ID"=>$formData['id'],
            'post_title'=>'',
            'post_content'=>\json_encode(array('fields'=>$formData['fields']))
        ));
        global $wpdb;
        $formSettings->Id=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->FormConfigTable." where original_id=%d",$formSettings->OriginalId));
        if($formSettings->Id==null)
            return;

        $serializeEntry=$this->SerializeEntry($fields,$formSettings);


        $pdfTemplates=array();
        if(isset($formData['meta']['pdfTemplates']))
            $pdfTemplates=$formData['meta']['pdfTemplates'];


        $entryId=$entry['id'];
        if(!\rednaoformpdfbuilder\Utils\Sanitizer::SanitizeBoolean(get_option($this->Loader->Prefix.'_skip_save',false)))
            $entryId=$this->SaveEntryToDB($formData['id'],$serializeEntry,$entryId,array('Fields'=>$fields));



        $pdfTemplates[]=array('EntryId'=>$entryId);
        $formData['meta']['pdfTemplates']=$pdfTemplates;
        global $RNWPCreatedEntry;
        $RNWPCreatedEntry=array(
            'Entry'=>$serializeEntry,
            'FormId'=>$formData['id'],
            'EntryId'=>$entryId,
            'Raw'=>json_decode( \json_encode(array('Fields'=>$fields))),
            "RawEntry" => $entry,
            "RawFormData" => $formData
        );
    }

    public function AddAttachmentNew($emailData,$wpform)
    {
        $emailData['attachments']=$this->AddAttachment($emailData['attachments'],null,$wpform);
        try
        {
            $emailData['message'] = $this->MaybeUpdateEmailBody($emailData['message'], $wpform->form_data['id'], $wpform->entry_id, $wpform->fields);
        }catch (Exception $e)
        {
        }
        return $emailData;
    }

    public function AddAttachment($attachment,$target,$wpFormSettings)
    {
        global $RNWPCreatedEntry;
        if(!isset($RNWPCreatedEntry)||!isset($RNWPCreatedEntry['Entry']))
        {
            if($wpFormSettings!=null&&isset($wpFormSettings->fields)&&isset($wpFormSettings->form_data['fields']))
            {
                global $WPFormEmailBeingProcessed;
                $WPFormEmailBeingProcessed=$wpFormSettings->notification_id;
                if (!isset($RNWPCreatedEntry))
                    $RNWPCreatedEntry = array();
                $formProcessor=new WPFormFormProcessor($this->Loader);
                $formSettings=$formProcessor->SerializeForm(array(
                    "ID"=>$wpFormSettings->form_data['id'],
                    'post_title'=>'',
                    'post_content'=>\json_encode(array('fields'=>$wpFormSettings->form_data['fields']))
                ));

                global $wpdb;
                $RNWPCreatedEntry['Entry'] = $this->SerializeEntry($wpFormSettings->fields,$formSettings);
                $RNWPCreatedEntry['FormId']=$wpFormSettings->form_data['id'];
                $RNWPCreatedEntry['EntryId']='';
                $RNWPCreatedEntry['Raw']=json_encode($wpFormSettings->fields);
                $RNWPCreatedEntry['EntryId']=$wpdb->get_var($wpdb->prepare('select id from '.$this->Loader->RECORDS_TABLE.' where original_id=%d',$wpFormSettings->entry_id));

            }else
                return $attachment;
        }

        $fm=new FileManager($this->Loader);
        $fm->RemoveTempFolders();

        global $wpdb;
        $fields=$wpdb->get_var($wpdb->prepare('select fields from '.$this->Loader->FormConfigTable.' where original_id=%s',$RNWPCreatedEntry['FormId']));

        $entryRetriever=new WPFormEntryRetriever($this->Loader);



        $entryRetriever->InitializeByEntryItems($RNWPCreatedEntry['Entry'],$RNWPCreatedEntry['Raw'],$fields);

        global $wpdb;
        $result=$wpdb->get_results($wpdb->prepare(
            "select template.id Id,template.pages Pages, template.document_settings DocumentSettings,styles Styles,form_id FormId
                    from ".$this->Loader->FormConfigTable." form
                    join ".$this->Loader->TEMPLATES_TABLE." template
                    on form.id=template.form_id
                    where original_id=%s"
            ,$RNWPCreatedEntry['FormId']));
        $files=[];



        if(!isset($RNWPCreatedEntry['CreatedDocuments'])){
            $RNWPCreatedEntry['CreatedDocuments']=[];
        }
        foreach($result as $templateSettings)
        {
            $templateSettings->Pages=\json_decode($templateSettings->Pages);
            $templateSettings->DocumentSettings=\json_decode($templateSettings->DocumentSettings);

            if(isset($templateSettings->DocumentSettings->Notifications)&&count($templateSettings->DocumentSettings->Notifications)>0)
            {
                global $WPFormEmailBeingProcessed;
                if(isset($WPFormEmailBeingProcessed))
                {
                    $found=false;
                    foreach($templateSettings->DocumentSettings->Notifications as $attachToNotificationId)
                    {
                        if($attachToNotificationId==$WPFormEmailBeingProcessed)
                            $found=true;
                    }

                    if(!$found)
                        continue;
                }
            }


            $generator=(new PDFGenerator($this->Loader,$templateSettings,$entryRetriever));
            $path=$generator->SaveInTempFolder();

            $RNWPCreatedEntry['CreatedDocuments'][]=array(
                'TemplateId'=>$generator->options->Id,
                'Name'=>$generator->options->DocumentSettings->FileName
            );

            $path=apply_filters($this->Loader->Prefix.'_pdf_attached_to_email',$path,$RNWPCreatedEntry,$templateSettings->Id);


            $_SESSION['test']='1';
            $_SESSION['WPForm_Generated_PDF']=array(
                'TemplateId'=>$generator->options->Id,
                'EntryId'=>$RNWPCreatedEntry['EntryId']
            );

            if($path!='')
            {
                $this->MaybeSendToDrive($templateSettings,$generator);
                $attachment[] = $path;
            }

        }

        return $attachment;

    }

    public function SerializeEntry($entry, $formSettings)
    {
        /** @var EntryItemBase $entryItems */
        $entryItems=array();
        foreach($entry as $key=>$value)
        {
            if(isset($value['visible'])&&!$value['visible'])
                continue;
            $currentField=null;
            foreach($formSettings->Fields as $field)
            {
                if($field->Id==$key)
                {
                    $currentField=$field;
                    break;
                }
            }

            if($currentField==null)
                continue;

            switch($currentField->SubType)
            {
                case 'text':
                case 'email':
                case 'password':
                case "phone":
                case "hidden":
                case 'textarea':
                case 'url':
                case 'number':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['value']);

                    break;
                case 'payment-single':
                case 'payment-total':
                    $entryItems[]=(new SimpleTextEntryItem())->Initialize($currentField)->SetValue($value['amount']);
                    break;
                case 'radio':
                    $value=$value['value'];
                    $value=\explode("\n",$value);
                    $entryItems[]=(new RadioEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'checkbox':
                case 'payment-checkbox':
                    $value=$value['value'];
                    $value=\explode("\n",$value);
                    $entryItems[]=(new CheckBoxEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'select':
                    $value=$value['value'];
                    $value=\explode("\n",$value);
                    $entryItems[]=(new DropDownEntryItem())->Initialize($currentField)->SetValue($value);
                    break;
                case 'payment-select':
                    if(!\is_array($value))
                    {
                        $value=[$value];
                    }
                    $amount=0;
                    if(isset($value['amount']))
                        $amount=$value['amount'];
                    $entryItems[]=(new DropDownEntryItem())->Initialize($currentField)->SetValue($value['value_choice'],$amount);
                    break;
                case 'payment-multiple':
                    if(!\is_array($value))
                    {
                        $value=[$value];
                    }
                    $amount=0;
                    if(isset($value['amount']))
                        $amount=$value['amount'];
                    $entryItems[]=(new RadioEntryItem())->Initialize($currentField)->SetValue($value['value_choice'],$amount);
                    break;

                case 'credit-card':

                    break;
                case 'name':
                    switch ($currentField->Format)
                    {
                        case 'simple':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['value'],'');
                            break;
                        case 'first-last':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['first'],$value['last']);
                            break;
                        case 'first-middle-last':
                            $entryItems[]=(new WPFormNameEntryItem())->InitializeWithValues($currentField,$value['first'],$value['last'],$value['middle']);
                            break;
                    }
                    break;
                case 'address':
                    $country='';
                    if(isset($value['country']))
                        $country=$value['country'];
                    $entryItems[]=(new WPFormAddressEntryItem())->InitializeWithValues($currentField,$value['address1'],
                        $value['address2'],$value['city'],$value['state'],$value['postal'],$country);
                    break;
                case 'date-time':

                    $time='';
                    $date='';
                    $unix=0;
                    if(isset($value['time'])&&$value['time']!='')
                    {
                        $time=$value['time'];
                        $dateObject=DateTime::createFromFormat('m/d/Y '.$currentField->TimeFormat,'1/1/1970 ' .$time,new DateTimeZone('UTC'));
                        $unix=$value['unix'];

                    }else{
                        $time='';
                    }
                    if(isset($value['date'])&&$value['date']!='')
                    {
                        $date=$value['date'];
                        $dateObject=DateTime::createFromFormat($currentField->DateFormat.' H:i:s:u',$value['date'] . "0:00:00:0",new DateTimeZone('UTC'));
                        if($dateObject!=false)
                        {
                            $unix+=$dateObject->getTimestamp();
                        }

                        $unix=$value['unix'];

                    }else{
                        $date='';
                    }

                    $entryItems[]=(new WPFormDateTimeEntryItem())->InitializeWithValues($currentField,$value['value'],$date,$time,$unix);


                    break;
                case 'file-upload':
                    $mime='';
                    $name='';
                    if(isset($value['value_raw'])&&isset($value['value_raw'][0])&&isset($value['value_raw'][0]['name']))
                        $name=$value['value_raw'][0]['name'];

                    $entryItems[]=(new WPFormFileUploadEntryItem())->InitializeWithValues($currentField, $value['value'],'','',$name);
                    break;
            }
        }


        return $entryItems;

    }

    public function InflateEntryItem(FieldSettingsBase $field,$entryData)
    {
        $entryItem=null;
        switch($field->SubType)
        {
            case 'text':
            case 'email':
            case 'password':
            case "phone":
            case "hidden":
            case 'payment-single':
            case 'textarea':
            case 'payment-total':
            case 'url':
            case 'number':
                $entryItem= new SimpleTextEntryItem();
                break;
            case 'radio':
                $entryItem= new RadioEntryItem();
                break;
            case 'checkbox':
            case 'payment-checkbox':
                $entryItem= new CheckBoxEntryItem();
                break;
            case 'payment-multiple':
                $entryItem= new RadioEntryItem();
                break;
            case 'select':
                $entryItem= new DropDownEntryItem();
                break;
            case 'payment-select':
                $entryItem= new DropDownEntryItem();
                break;
            case 'credit-card':
                break;
            case 'name':
                $entryItem= new WPFormNameEntryItem();
                break;
            case 'address':
                $entryItem=  new WPFormAddressEntryItem();
                break;

            case 'date-time':
                $entryItem= new WPFormDateTimeEntryItem();
                break;
            case 'file-upload':
                $entryItem= new WPFormFileUploadEntryItem();
                break;
        }

        if($entryItem==null)
            throw new Exception("Invalid entry sub type ".$field->SubType);
        $entryItem->InitializeWithOptions($field,$entryData);
        return $entryItem;
    }


}