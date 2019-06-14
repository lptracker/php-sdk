<?php

namespace LPTracker\models;

use LPTracker\exceptions\LPTrackerSDKException;

class LeadFile extends Model
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $ext;

    /**
     * @var string
     */
    protected $data;

    public function __construct(array $fileData = [])
    {
        if (!empty($fileData['id'])) {
            $this->id = (int) $fileData['id'];
        }
        if (!empty($fileData['name'])) {
            $this->name = $fileData['name'];
        }
        if (!empty($fileData['ext'])) {
            $this->ext = $fileData['ext'];
        }
        if (!empty($fileData['data'])) {
            $this->data = $fileData['data'];
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $result = [];
        if (!empty($this->id)) {
            $result['id'] = $this->getId();
        }
        if (!empty($this->name)) {
            $result['name'] = $this->getName();
        }
        if (!empty($this->ext)) {
            $result['ext'] = $this->getExt();
        }
        if (!empty($this->data)) {
            $result['data'] = $this->getData();
        }
        return $result;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return (int) $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $path (Full path to file with name and ext)
     *
     * @return void
     */
    public function saveFile($path)
    {
        try{
            $content = $this->getData();
            if(!empty($content)){
                @file_put_contents($path, base64_decode($content));
            }
        }catch(\Exception $e){}
    }

    public function validate(){
        return true;
    }
}
