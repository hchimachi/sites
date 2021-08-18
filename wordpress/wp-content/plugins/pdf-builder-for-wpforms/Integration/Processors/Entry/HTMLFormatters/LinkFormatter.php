<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 3/28/2019
 * Time: 7:33 AM
 */

namespace rednaoformpdfbuilder\Integration\Processors\Entry\HTMLFormatters;


class LinkFormatter extends PHPFormatterBase
{


    private $url;
    private $title;

    public function __construct($url, $title)
    {
        $this->url = $url;
        $this->title = $title;
    }


    public function __toString()
    {

        $urls=preg_split('/\r\n|\r|\n/', $this->url);

        if(is_array($urls))
        {
            $text='';
            foreach ($urls as $currentUrl)
            {

                if (!\filter_var($currentUrl, \FILTER_VALIDATE_URL))
                    continue;

                if ($this->endsWith($currentUrl, '.jpg') || $this->endsWith($currentUrl, '.jpeg') || $this->endsWith($currentUrl, '.png'))
                {

                    $text .= '<img style="max-width:400px;" src="' . $currentUrl . '"/>';
                } else
                {
                    $linkName=$this->title!=''?$this->title:$currentUrl;
                    $text .= '<a target="_blank" href="' . $currentUrl . '">' . \esc_html($linkName) . '</a>';
                }

            }
            return $text;
        }


        if($this->endsWith($this->url,'.jpg')||$this->endsWith($this->url,'.jpeg')||$this->endsWith($this->url,'.png'))
        {
            return '<img style="max-width:400px;" src="'.esc_attr($this->url).'"/>';
        }

        return '<a target="_blank" href="'.$this->url.'">'.\esc_html($this->title).'</a>';
    }

    public function endsWith($haystack, $needle) {
        return substr_compare($haystack, $needle, -strlen($needle)) === 0;
    }


    public function IsEmpty(){
        return trim($this->url)=='';
    }


    public function ToText()
    {
        return $this->url;
    }
}