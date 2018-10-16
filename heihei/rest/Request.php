<?php
namespace heihei\rest;

class Request extends \yii\web\Request
{
    public $formatParam = '_format';
    
	public $formatContentTypes = [
		'json' => 'application/json',
		'xml' => 'application/xml',
	];

	/**
     * @inheritdoc
     */
	public function getContentType()
    {
    	$contentType = parent::getContentType();
    	if(($format = $this->get($this->formatParam)) 
    		&& isset($this->formatContentTypes[$format])){
    		$contentType = $this->formatContentTypes[$format];
    	}
        return $contentType;
    }

    /**
     * @inheritdoc
     */
    public function getAcceptableContentTypes()
    {
        $accept = $this->getHeaders()->get('Accept');
        $this->getHeaders()->set('Accept', str_replace('text/html', 'application/json; version=1.0', $accept));
        return parent::getAcceptableContentTypes();
    }
}
