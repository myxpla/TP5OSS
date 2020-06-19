<?php

namespace app\admin\model;

use OSS\OssClient;
use OSS\Core\OssException;

class Oss extends BaseModel
{
    /**
     * 实例化阿里云OSS
     * @return object 实例化得到的对象
     * @return 此步作为共用对象，可提供给多个模块统一调用
     */
   public function new_oss(){
        //获取配置项，并赋值给对象$config
        $config=config('aliyun_oss');
        //实例化OSS
        $oss=new \OSS\OssClient($config['KeyId'],$config['KeySecret'],$config['Endpoint']);
        return $oss;
    }
    /**
     * 上传指定的本地文件内容
     *
     * @author harry
     * @param OssClient $ossClient OSSClient实例
     * @param string $bucket 存储空间名称
     * @param string $object 上传的文件名称
     * @param string $Path 本地文件路径
     * @return img
     */
    public function uploadFile($object,$Path){
        //try 要执行的代码,如果代码执行过程中某一条语句发生异常,则程序直接跳转到CATCH块中,由$e收集错误信息和显示
        try{
            $config=config('aliyun_oss');
            $bucket=$config['Bucket'];
            //没忘吧，new_oss()是我们上一步所写的自定义函数
            $ossClient = $this->new_oss();
            //uploadFile的上传方法
            $result = $ossClient->uploadFile($bucket, $object, $Path);
            if(!empty($result['oss-request-url'])){
                $img=str_replace($config['Ali_OSS_url'], $config['Ali_OSS_seconduel'],$result['oss-request-url']);
            }    
        } catch(OssException $e) {
            //如果出错这里返回报错信息
            return $e->getMessage();
        }
        //否则，完成上传操作
        return $img;
    }

}
