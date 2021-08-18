<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 6:20 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators;


use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\PDFDocumentOptions;
use rednaoformpdfbuilder\htmlgenerator\utils\Formatter;
use rednaoformpdfbuilder\Integration\Processors\Entry\Retriever\EntryRetrieverBase;
use rednaoformpdfbuilder\pr\Utilities\FontManager;


class DocumentGenerator
{
    /**
     * @var PDFDocumentOptions
     */
    public $options;
    /** @var EntryRetrieverBase */
    public $orderValueRetriever;
    /** @var Loader */
    private $loader;
    public $Formatter;
    public function __construct($loader,$options,$orderValueRetriever)
    {
        $this->loader=$loader;
        $this->options = $options;
        $this->orderValueRetriever = $orderValueRetriever;
        $this->Formatter=new Formatter($options);
    }

    public function Generate(){
        $html="<html><body class='pdfBody'>";
        if(isset($this->options->DocumentSettings->BaseStyles))
        {
            $html .= "<style>";
            $html.=$this->options->DocumentSettings->BaseStyles;
            $html .= "</style>";
        }
        $html.='<style>
                                @font-face{
                                    font-family:\'FontAwesome\';
                                    src:url("'.$this->loader->DIR .'css/fontAwesome/fonts/fontawesome-webfont.ttf");
                                    }
                                    
                                    
    .PDFElement.CustomField,.PDFElement.ConditionalField {
                text-align:left;
                min-height: 40px;
             }
             
             .PDFElement.Text p{
                line-height: 1.2em;
             }
             
             .PDFElement.CustomField .FieldLabel,.PDFElement.ConditionalField .FieldLabel{
                font-weight:bold;    
                vertical-align:top;            
             }
             
             .PDFElement.CustomField Table,.PDFElement.ConditionalField Table{
                width:100%;
             }
             
             .PDFElement.CustomField .FieldLabel .Top,.PDFElement.ConditionalField .FieldLabel .Top{              
                margin-bottom:2px;
             }
             .PDFElement.CustomField .FieldLabel .Bottom,,.PDFElement.ConditionalField .FieldLabel .Bottom{              
                margin-top:2px;
             }
             .PDFElement.CustomField .FieldLabel .Left,,.PDFElement.ConditionalField .FieldLabel .Left{              
                margin-right:2px;
             }
             .PDFElement.CustomField .FieldLabel .Right,,.PDFElement.ConditionalField .FieldLabel .Right{              
                margin-left:2px;
             }
                            
                                body{
                                    font-family:\'dejavu sans\';                                  
                                }
                           
                                @page{
                                    margin:0;
                                }                            
                 </style>';
        $html.='<style>'.$this->options->Styles.'</style>';


        if($this->loader->IsPR())
        {
            $fontManager=new FontManager($this->loader);
            $fonts=$fontManager->GetAvailableFonts(false);
            $html.='<style>';
            $fontURL=$fontManager->GetFontPath();
            foreach($fonts as $currentFont){
                $html.= " @font-face{font-family:\"$currentFont\";
              src:url(\"".$fontURL.urlencode($currentFont).".ttf\");
                }";
            }
            $html.='</style>';
        }

        for($i=0;$i<count($this->options->Pages);$i++)
        {
            $pageGenerator=new PageGenerator($this->loader,$this, $this->options->Pages[$i],$this->orderValueRetriever,$i);
            $html.=$pageGenerator->Generate();
        }
        $html.="</body></html>";
        return $html;
    }

    /**
     * @return FieldDTO[]
     */
    public function GetFieldsDictionary(){
        $dictionary=array();

        foreach($this->options->Pages as $page)
        {
            foreach ($page->Fields as $field)
            {
                $field->Page=$page;
                $dictionary['pdfField_' . $field->Id] = $field;
            }
        }

        return $dictionary;
    }




}


