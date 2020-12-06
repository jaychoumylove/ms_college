<?php
define("TOKEN", "weixin");//自己定义的token 就是个通信的私钥
define("ACCESS_TOKEN", "5_R19CNOkUHwmka5xZqQ_PpNC9PzirBWIaTzNcuIxaLvNreFTlDIOpa86KnGttgJFbebueMs00pIRvrgkeY-s2nQCNk_Btc8Il-PH9T1kB_BgkCOcoSBePTZi1_ZAOPBjAAAHIU");
$wechatObj = new wechatCallbackapiTest();
/*开发者通过检验signature对请求进行校验（下面有校验方式）。若确认此次GET请求来自微信服务器，请原样返回echostr参数内容，则接入生效，否则接入失败。
signature结合了开发者填写的token参数和请求中的timestamp参数、nonce参数。
加密/校验流程：
1. 将token、timestamp、nonce三个参数进行字典序排序
2. 将三个参数字符串拼接成一个字符串进行sha1加密
3. 开发者获得加密后的字符串可与signature对比，标识该请求来源于微信*/

if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
	//$wechatObj->responseMsg();
	$wechatObj->uplodeTmp('can.png','image');
}

class wechatCallbackapiTest
{  
	//定义消息回复模板
	private $_msg_template = array(
        'text' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[%s]]></Content></xml>',//文本回复XML模板
        'image' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[image]]></MsgType><Image><MediaId><![CDATA[%s]]></MediaId></Image></xml>',//图片回复XML模板
        'music' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[music]]></MsgType><Music><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><MusicUrl><![CDATA[%s]]></MusicUrl><HQMusicUrl><![CDATA[%s]]></HQMusicUrl><ThumbMediaId><![CDATA[%s]]></ThumbMediaId></Music></xml>',//音乐模板
        'news' => '<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[news]]></MsgType><ArticleCount>%s</ArticleCount><Articles>%s</Articles></xml>',// 新闻主体
        'news_item' => '<item><Title><![CDATA[%s]]></Title><Description><![CDATA[%s]]></Description><PicUrl><![CDATA[%s]]></PicUrl><Url><![CDATA[%s]]></Url></item>',//某个新闻模板
    );
	public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
		ob_clean();
            echo $echoStr;
            exit;
        }
    }   
	private function checkSignature()//签名验证
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];//接收微信发来的XML数据   

        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //解析post来的XML为一个对象$postObj 
            $fromUsername = $postObj->FromUserName;//请求消息的用户  
            $toUsername = $postObj->ToUserName;//"我"的公众号id 
            $keyword = trim($postObj->Content);//消息内容
            $time = time();
            $textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                        <FuncFlag>0</FuncFlag>
                        </xml>";
 
									
            if($keyword!='')
            {
                $msgType = "text";
				$keyword_arr=explode('-',$keyword);
				require('../application/config/MYSQLPDO.class.php');
				require('../application/config/model.class.php');
				function __autoload($n){
					$fileName=$n;
					$direName=substr($fileName,-10);
					if($direName=="Controller"){
						require "..\application\admin\controller\\".$fileName.".class.php";
					}else{
						require "..\application\admin\model\\".$fileName.".class.php";
					}
				}
				//转化成大写
				$keyword_arr[0]=strtoupper($keyword_arr[0]);
				$keyword_arr[1]=strtoupper($keyword_arr[1]);
				if($keyword_arr[0]=='XF'){
					if(!preg_match("/^(SN.\d{8})$/",$keyword_arr[1])){
						$the_s=new studentModel();
						$result_arr_s=$the_s->searchCor($keyword_arr[1],$keyword_arr[2]);
						if($result_arr_s==''){
							$contentStr="同学，你的学号是不是输入错了，教务找不到啊";
						}else{
							$contentStr="学号为".$keyword_arr[1]."的".$result_arr_s['stu_realname']."同学，你的学分为：".$result_arr_s['stu_credits'];
						}
					}else{
						$contentStr="同学，你的学号格式输入错了，教务找不到啊";
					}
				}elseif($keyword_arr[0]=='CJ'){
					$keyword_arr[2]=strtoupper($keyword_arr[2]);
					if(!preg_match("/^(SN.\d{8})$/",$keyword_arr[1])){
						if($keyword_arr[2]=='ALL'){
							$the=new gradeModel();
							$result_arr=$the->searchGradeAll($keyword_arr[1]);
							if($result_arr==''){
								$contentStr="同学，你的学号是不是输入错了，教务找不到啊";
							}else{
								$contentStr="学号为".$keyword_arr[1]."的".$result_arr[1]['stu_realname']."同学，
";
								foreach($result_arr as $v){
									$contentStr.="你的".$v['classCoursesName']."成绩为：".$v['grade'].";
";
								}
							}
						}else{
							if(!preg_match("/^(CN.\d{8})$/",$keyword_arr[2])){
								$the=new gradeModel();
								$result_arr=$the->searchGrade($keyword_arr[1],$keyword_arr[2]);
								if($result_arr==''){
									$contentStr="同学，你的学号和课程编号是不是输入错了，教务找不到啊";
								}else{
									$contentStr="学号为".$keyword_arr[1]."的".$result_arr['stu_realname']."同学，你的".$result_arr['classCoursesName']."成绩为：".$result_arr['grade'];
								}
							}else{
								$contentStr="同学，你的学号格式或者课程编号格式输入错了，教务找不到啊";
							}
						}
					}
				}else{
					$contentStr =  "欢迎进入选课系统，查询个人学分请输入：XF-你的学号-你的密码；查询个人单门选课成绩请输入：CJ-你的学号-课程编号；查询个人所有选课成绩请输入：CJ-你的学号-ALL。";
				}
                 $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);//把格式化的数据写入某个字符串
                echo $resultStr;
               
            }else{
                $msgType = "text";
                $contentStr =  "欢迎进入选课系统，查询个人学分请输入：XF-你的学号-你的密码；查询个人单门选课成绩请输入：CJ-你的学号-课程编号；查询个人所有选课成绩请输入：CJ-你的学号-ALL。";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;				
			}
        }else{
            echo "";
            exit;
        }
    }
	public function createMenu($data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".ACCESS_TOKEN);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		if (curl_errno($ch)) {
		  return curl_error($ch);
		}
		curl_close($ch);
		return $tmpInfo;
	}
	//获取菜单
	public function getMenu(){
		return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".ACCESS_TOKEN);
	}
	//删除菜单
	public function deleteMenu(){
		return file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".ACCESS_TOKEN);
	}
	//测试创建菜单
	/*$data = '{
		 "button":[
		 {
			  "type":"click",
			  "name":"首页",
			  "key":"home"
		  },
		  {
			   "type":"click",
			   "name":"简介",
			   "key":"introduct"
		  },
		  {
			   "name":"菜单",
			   "sub_button":[
				{
				   "type":"click",
				   "name":"hello word",
				   "key":"V1001_HELLO_WORLD"
				},
				{
				   "type":"click",
				   "name":"赞一下我们",
				   "key":"V1001_GOOD"
				}]
		   }]
	}';
	echo createMenu($data);*/
	/*public function responseMsg(){
        
        //获得请求时POST:XML字符串
        //不能用$_POST获取，因为没有key
         
        $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];
        if(empty($xml_str)){
            die('');
        }
        if(!empty($xml_str)){
            // 解析该xml字符串，利用simpleXML
            libxml_disable_entity_loader(true);
            //禁止xml实体解析，防止xml注入
              $request_xml = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);
            //判断该消息的类型，通过元素MsgType
            switch ($request_xml->MsgType){
                case 'event':
                    //判断具体的时间类型（关注、取消、点击）
                    $event = $request_xml->Event;
                      if ($event=='subscribe') { // 关注事件
                          $this->_doSubscribe($request_xml);
                      }elseif ($event=='CLICK') {//菜单点击事件
                          $this->_doClick($request_xml);
                      }elseif ($event=='VIEW') {//连接跳转事件
                          $this->_doView($request_xml);
                      }else{

                      }
                    break;
                case 'text'://文本消息
                    $this->_doText($request_xml);
                    break;
                case 'image'://图片消息
                    $this->_doImage($request_xml);
                    break;
                case 'voice'://语音消息
                    $this->_doVoice($request_xml);
                    break;
                case 'video'://视频消息
                    $this->_doVideo($request_xml);
                    break;
                case 'shortvideo'://短视频消息
                    $this->_doShortvideo($request_xml);
                    break;
                case 'location'://位置消息
                    $this->_doLocation($request_xml);
                    break;
                case 'link'://链接消息
                    $this->_doLink($request_xml);
                    break;
            }        
        }        
    }*/
	/*private function _msgText($to, $from, $content) {
        $response = sprintf($this->_msg_template['text'], $to, $from, time(), $content);
        die($response);
    }*/
	//关注后做的事件
	/*private function _doSubscribe($request_xml){
		//处理该关注事件，向用户发送关注信息
		$content = '你好';
		$this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $content);
	}*/
	//自定义方法：上传文件，并获取地址
	public function uplodeTmp($file,$type){
        $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.ACCESS_TOKEN.'&type='.$type;
        $data = array(
            'media' => '@'.$file,
            );
        $result = $this->_request('post',$url,$data);
        $result_obj = json_decode($result);
        return $result_obj;
    }
}

?>