<?php
/**
 * Uploader class
 * @author:  anthony
 * @version: v1.0
 * ---------------------------------------------
 * $Date: 2013-09-22
 * $Id: comm_fileuploader.php
*/
include_once dirname(__FILE__)."/../../config/comm_config.php";

class comm_uploader{
    private $_file;             //提交文件的表单
    private $_name;             //提交的文件名
    private $_size;             //提交文件的大小
    private $_ext;              //提交文件的后缀
    private $_new_file_name;    //输出的文件名
    
    public function __construct($file_field)
    {
        $this->_file = $file_field;
        $this->_name = $this->_file['name'];
        $this->_size = $this->_file['size'];
        $this->_ext = $this->getFileExt();        
    }
    
    
    /**
     * 上传图片
     */
    public function uploadImg($user_id, &$img_name)
    {
        if(!$this->checkImgExt())
        {
            return -1;
        }
        
        //如果图片名称不为空，则按照这个名字使用
        if($img_name == "")
            $this->_new_file_name = $this->createFullName($user_id);
        else
            $this->_new_file_name = $img_name;
        
        /**
         * 后续这个函数可能会修改为文件存储系统的接口
         */
        if(!move_uploaded_file($this->_file['tmp_name'], IMG_PATH."/".$this->_new_file_name))
        {
            return -2;
        }       
        
        $img_name = $this->_new_file_name;
        
        return 0;
    }
    
    
    /**
     * 返回生成的图片名称
     */
    public function getImgName()
    {
        return $this->_new_file_name;
    }
    
    /**
     * 上传普通文件
     */
    public function upload($file_field)
    {
        return 0;
    }
    

    /**
     * 判断文件后缀，对于图片文件，后续可能需要根据文件内容判断文件是否合法
     */
    private function getFileExt()
    {
        return strtolower( strrchr( $this->_name , '.' ) );
    }
    
    private function checkImgExt()
    {
        switch ($this->_ext)
        {
        case ".jpg":
        case ".jpeg":
        case ".gif":
        case ".png":
            return true;
        default:
            return false;
        }
    }

    /**
     * 按照规则生成文件名称，具体规则待定
     */
    private function createFullName($user_id)
    {
        $name_new = sprintf("%s-%016X%016X%s", "gkyh", ukey_next_id(), $user_id, $this->_ext);
        return $name_new;
    }
}
?>