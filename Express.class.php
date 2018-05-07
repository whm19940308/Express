<?php

/**
 * Express.class.php 快递查询类
 *
 * @author 王浩铭
 * @date 2017/09/27
 */

class Express {

    
    /**
     * @desc 采集网页内容的方法，建议使用curl，效率更高
     * @param $url
     * @return mixed|string
     */
    private function getContent($url){

        if(function_exists("file_get_contents")){
            $file_contents = file_get_contents($url);
        }else{
            $ch = curl_init();
            $timeout = 5;   // 设置5秒超时
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $file_contents = curl_exec($ch);
            curl_close($ch);
        }
        return $file_contents;

    }

    /**
     * @desc 得到目前物流单号可能存在的快递公司
     * @param string $order_no
     * @return mixed
     */
    public function getOrder($order_no=''){

        $result = $this->getContent("http://www.kuaidi100.com/autonumber/autoComNum?text=".$order_no);
        $data = json_decode($result,true);

        return $data;

    }



    /**
     * @desc http://www.kuaidi100.com/query?type=zhongtong&postid=453371918456&id=1&valicode=&temp=0.40349807080624434
     * @desc 返回的数据结果参考官方文档：https://www.kuaidi100.com/openapi/api_post.shtml
     * @desc 直接调用该方法,传入物流单号即可查询物流信息
     * @param string $order_no
     * @return bool|mixed
     */
    public function getLogisticsInfo($order_no=''){

        $result = $this->getOrder($order_no);
        $auto_arr = $result['auto'];

        if(count($auto_arr)>0){
            foreach ($auto_arr as $key => $value){
                $temp = $this->randFloat();
                $comCode = $value['comCode'];
                $url = "http://www.kuaidi100.com/query?type=$comCode&postid=$order_no&id=1&valicode=&temp=$temp";// $temp 随机数,防止缓存
                $json = $this->getContent($url);
                $data = json_decode($json,true);
                if($data['message']=='ok'){
                    return $data;
                }
            }
        }

        return false;

    }


    /**
     * 生成0~1随机小数
     * @param Int  $min
     * @param Int  $max
     * @return Float
     */
    function randFloat($min=0, $max=1){

        return $min + mt_rand()/mt_getrandmax() * ($max-$min);

    }


}