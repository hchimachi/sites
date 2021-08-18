<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 6/22/2018
 * Time: 11:25 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators;




use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\DTO\DocumentOptions;
use rednaoformpdfbuilder\DTO\PDFControlBaseOptions;
use rednaoformpdfbuilder\DTO\SectionOption;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\FieldFactory;
use rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields\PDFFieldBase;

class AreaGenerator
{
    /** @var Loader */
    public $Loader;
    /** @var SectionOption */
    private $options;

    /** @var PDFControlBaseOptions[] */
    private $fields;
    public $tagGenerator;
    private $orderValueRetriever;
    /** @var PageGenerator */
    public $PageGenerator;

    public function __construct($pageGenerator,$loader,$options, $fields,$orderValueRetriever)
    {
        $this->PageGenerator=$pageGenerator;
        $this->Loader=$loader;
        $this->tagGenerator=new TagGenerator();
        $this->options = $options;
        $this->fields = $fields;
        $this->orderValueRetriever = $orderValueRetriever;
    }

    public function Generate(){
        $areaStyles=array('height'=>$this->options->Height.'px','width'=>'100%');
        if($this->options->Type=='footer')
        {
            $areaStyles['position'] = 'absolute';
            $areaStyles['overflow'] = 'hidden';
            $areaStyles['bottom'] = '0px';
        }else{
            $areaStyles['position'] = 'relative';
        }
        $html=$this->tagGenerator->StartTag('div',$this->options->Type,$areaStyles,null);
        foreach($this->fields as $field)
        {
            /** @var PDFFieldBase $createdField */
            $createdField=FieldFactory::GetField($this->Loader,$this, $field,$this->orderValueRetriever);
            if($createdField!=null)
                $html.=$createdField->GetHTML();

        }

        $html.='</div>';
        return $html;
    }

}