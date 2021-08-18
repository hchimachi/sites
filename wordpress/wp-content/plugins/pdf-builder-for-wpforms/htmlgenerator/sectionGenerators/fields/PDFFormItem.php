<?php

namespace rednaoformpdfbuilder\htmlgenerator\sectionGenerators\fields;



/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 10/6/2017
 * Time: 6:52 AM
 */

class PDFFormItem extends PDFFieldBase
{

    protected function InternalGetHTML()
    {
        $label=$this->GetPropertyValue('Label');
        $value='';

        $style='standard';
        if(isset($this->options->Style))
            $style=$this->options->Style;
        if($this->entryRetriever==null)
            $value='Not available on preview';
        else
            $value=$this->entryRetriever->GetHtmlByFieldId($this->options->FieldId,$style);
        $field='<table>';
        $field.='<tbody>';

        $textColumn='<td class="FieldValue">'.$value.'</td>';
        $labelColumn='';
        if($label=='')
            return $field.$textColumn.'</tbody></table>';



        $position=$this->GetPropertyValue('LabelPosition');
        switch($position)
        {
            case 'Top':
                $labelColumn='<td class="FieldLabel Top"><p>'.$label.'</p></td>';
                return $field.'<tr>'.$labelColumn.'</tr>'.'<tr>'.$textColumn.'</tr></tbody></table>';
                break;
            case 'Bottom':
                $labelColumn='<td class="FieldLabel Top"><p >'.$label.'</p></td>';
                return $field.'<tr>'.$textColumn.'</tr>'.'<tr>'.$labelColumn.'</tr></tbody></table>';
                break;
            case 'Left':
                $labelColumn='';
                if(trim($label)!='')
                    $labelColumn='<td class="FieldLabel Left"><p >'.$label.'</p></td>';
                return $field.'<tr>'.$labelColumn.$textColumn.'</tr></tbody></table>';
                break;
            case 'Right':
                $labelColumn='<td class="FieldLabel Right"><p >'.$label.'</p></td>';
                return  $field.'<tr>'.$field.$textColumn.$labelColumn.'</tr></tbody></table>';
                break;
        }
    }




}