<?php

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;


use rednaoformpdfbuilder\pr\Manager\TagManager;

/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFText extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $label=$this->GetPropertyValue('Label');
        $value=$this->GetPropertyValue('Text');

        if($this->Loader->IsPR())
        {
            $tagManager=new TagManager($this->entryRetriever);
            $value=$tagManager->Process($value);
        }

        $field='<table>';
        $field.='<tbody>';

        $textColumn='<td class="TextValue"><p>'.$value.'</p></td>';
        $labelColumn='';
        if($label=='')
            return $field.$textColumn.'</tbody></table>';



        $position=$this->GetPropertyValue('LabelPosition');
        switch($position)
        {
            case 'Top':
                $labelColumn='<td class="TextLabel Top"><p>'.$label.'</p></td>';
                return $field.'<tr>'.$labelColumn.'</tr>'.'<tr>'.$textColumn.'</tr></tbody></table>';
                break;
            case 'Bottom':
                $labelColumn='<td class="TextLabel Top"><p >'.$label.'</p></td>';
                return $field.'<tr>'.$textColumn.'</tr>'.'<tr>'.$labelColumn.'</tr></tbody></table>';
                break;
            case 'Left':
                $labelColumn='<td class="TextLabel Left"><p >'.$label.'</p></td>';
                return '<tr>'.$field.$labelColumn.$textColumn.'<tr></tbody></table>';
                break;
            case 'Right':
                $labelColumn='<td class="TextLabel Right"><p >'.$label.'</p></td>';
                return '<tr>'.$field.$textColumn.$labelColumn.'<tr></tbody></table>';
                break;
        }











        $text=$this->tagGenerator->StartTag('p','',array('vertical-align'=>'top'),null);
        $text.=' '.$this->GetPropertyValue('Text');
        $text.=' </p>';
        return $text;
    }
}