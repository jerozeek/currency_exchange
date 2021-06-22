<?php
namespace App\Services;

class ImageManipulator{

    private $file;

    public function __construct($file){
        $this->file = $file;
    }

    public function handle(){
        $image = \Config\Services::image()
            ->withFile(ROOTPATH.'public/temp_profile/'.$this->file)
            ->fit(100, 100, 'center')
            ->save(ROOTPATH.'public/profile/'.$this->file);
    }


}
