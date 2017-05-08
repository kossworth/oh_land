<?php

namespace app\components\behavior;

use yii\base\Behavior;
use yii\helpers\Url;

/**
 * Description of ThumbBehavior
 *
 * @author kossworth
 */
class ImgBehavior extends Behavior {
    
    /**
    * Get image path
    */    
    public function getImgPath()
    {
        $table_name=$this->owner->tableName();
        $path = __DIR__ . "/../../../images/$table_name/{$this->owner->id}.1.b.jpg";
        if(file_exists($path)) {
            return $path;
        } else {
            return false;
        }
    }
    
    //
    //big images urls array
    //
    public function getBImgs() {
        $i = 1;
        $res = [];
        $table_name = $this->owner->tableName();
        while($i <= $this->owner->img_count_const()){
            if(is_file(__DIR__ . '/../../../images/'. $table_name.'/'.$this->owner->id.".$i.b.jpg"))
            {
                $res[] =  '/images/'. $table_name.'/'.$this->owner->id.".$i.b.jpg";
            }
            elseif($i == 1) 
            {
                $res[] = '/images/no-img.png';
            }
            $i++;
        }
        return $res;
    }
    
    //
    //small images urls array
    //
    public function getSImgs() {
        $i = 1;
        $res = [];
        $table_name = $this->owner->tableName();
        while($i <= $this->owner->img_count_const()){
            if(is_file(__DIR__ . '/../../../images/'. $table_name.'/'.$this->owner->id.".$i.s.jpg"))
            {
                $res[] =  '/images/'. $table_name.'/'.$this->owner->id.".$i.s.jpg";
            }
            elseif($i == 1) 
            {
                $res[] =  '/images/no-img.png';
            }
            $i++;
        }
        return $res;
    }
    //
    //small first image url
    //
    public function getSImg() 
    {   
        $table_name = $this->owner->tableName();
        if(is_file(__DIR__ . '/../../../images/'. $table_name.'/'.$this->owner->id.".1.s.jpg")){
            return '/images/'.$table_name.'/'.$this->owner->id.".1.s.jpg";
        }
        else{
            return '/images/no-img.png';
        }
           
    }
    
    public function getBImg() 
    {   
        $table_name = $this->owner->tableName();
        if(is_file(__DIR__ . '/../../../images/'. $table_name.'/'.$this->owner->id.".1.b.jpg")){
//            return Url::to(['/images/'.$table_name.'/'.$this->owner->id.".1.b.jpg"]) ;
            return '/images/'.$table_name.'/'.$this->owner->id.".1.b.jpg";
        }
        else{
            return '/images/no-img.png';
        }
           
    }
}
