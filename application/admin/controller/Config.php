<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/4/28
 * Time: 10:58
 */

namespace app\admin\controller;


use app\model\AdminConfig;
use app\util\ReturnCode;
use think\Db;
use think\Request;

class Config extends Base
{
    /**
     * use for:获取基本信息
     * auth: Joql
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * date:2018-04-28 16:14
     */
    public function basic(){
        if(Request::instance()->isPost()){

        }else{
            $info = (new AdminConfig())->field("name, value")->select();
            foreach ($info as $k=>$v){
                $data[$v['name']] = $v['value'];
            }
            return $this->buildSuccess($data);
        }
    }

    public function updateBase(){
        $email = $this->request->post('email','');
        $icp_number = $this->request->post('icpNumber','');
        $web_close_word = $this->request->post('webCloseWord','');
        $web_state = $this->request->post('webState',0);

        $update = [
            ['name'=>'WEB_STATUS','value'=>$web_state],
            ['name'=>'WEB_CLOSE_WORD','value'=>$web_close_word],
            ['name'=>'WEB_ICP_NUMBER','value'=>$icp_number],
            ['name'=>'ADMIN_EMAIL','value'=>$email],
        ];
        $result = (new AdminConfig())->saveAll($update);
        if($result === false){
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR,'操作失败');
        }else{
            return $this->buildSuccess(['result'=>$result]);
        }
    }
    public function updateShare(){
        $author = $this->request->post('author','');
        $copyright_word = $this->request->post('copyrightWord','');
        $imt_title_alt_word = $this->request->post('imgTltleAltWord','');
        $web_desc = $this->request->post('webDesc','');
        $web_key_words = $this->request->post('webKeyWords','');
        $web_name = $this->request->post('webName','');


        $update = [
            ['name'=>'WEB_NAME','value'=>$web_name],
            ['name'=>'WEB_KEYWORDS','value'=>$web_key_words],
            ['name'=>'WEB_DESCRIPTION','value'=>$web_desc],
            ['name'=>'AUTHOR','value'=>$author],
            ['name'=>'COPYRIGHT_WORD','value'=>$copyright_word],
            ['name'=>'IMAGE_TITLE_ALT_WORD','value'=>$imt_title_alt_word]
        ];
        $result = (new AdminConfig())->saveAll($update);
        if($result === false){
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR,'操作失败');
        }else{
            return $this->buildSuccess(['result'=>$result]);
        }
    }

    public function updateArticls(){
        $img_water_alpha = $this->request->post('imgWaterAlpha','');
        $img_water_locate = $this->request->post('imgWaterLocate','1');
        $img_water_path = $this->request->post('imgWaterPath','');
        $text_water_angle = $this->request->post('textWaterAngle','');
        $text_water_color = $this->request->post('textWaterColor','');
        $text_water_locate = $this->request->post('textWaterLocate','1');
        $text_water_path = $this->request->post('textWaterPath','');
        $text_water_size = $this->request->post('textWaterSize','');
        $text_water_word = $this->request->post('textWaterWord','');
        $water_type = $this->request->post('waterType','0');


        $update = [
            ['name'=>'WATER_TYPE','value'=>$water_type],
            ['name'=>'TEXT_WATER_WORD','value'=>$text_water_word],
            ['name'=>'TEXT_WATER_TTF_PTH','value'=>$text_water_path],
            ['name'=>'TEXT_WATER_FONT_SIZE','value'=>$text_water_size],
            ['name'=>'TEXT_WATER_COLOR','value'=>$text_water_color],
            ['name'=>'TEXT_WATER_ANGLE','value'=>$text_water_angle],
            ['name'=>'TEXT_WATER_LOCATE','value'=>$text_water_locate],
            ['name'=>'IMAGE_WATER_PIC_PTAH','value'=>$img_water_path],
            ['name'=>'IMAGE_WATER_LOCATE','value'=>$img_water_locate],
            ['name'=>'IMAGE_WATER_ALPHA','value'=>$img_water_alpha],
        ];
        $result = (new AdminConfig())->saveAll($update);
        if($result === false){
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR,'操作失败');
        }else{
            return $this->buildSuccess(['result'=>$result]);
        }
    }

    public function updateEmail(){
        $name = $this->request->post('emailFormNmae','');
        $password = $this->request->post('emailPassword','');
        $username = $this->request->post('emailUsername','');
        $stmp = $this->request->post('stmp','');

        $update = [
            ['name'=>'EMAIL_SMTP','value'=>$stmp],
            ['name'=>'EMAIL_USERNAME','value'=>$username],
            ['name'=>'EMAIL_PASSWORD','value'=>$password],
            ['name'=>'EMAIL_FROM_NAME','value'=>$name]
        ];
        $result = (new AdminConfig())->saveAll($update);
        if($result === false){
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR,'操作失败');
        }else{
            return $this->buildSuccess(['result'=>$result]);
        }
    }

    public function updateComment(){
        $review = $this->request->post('commentReview','0');
        $send_email = $this->request->post('commentSendEmail','0');
        $email_receive = $this->request->post('emailReceive','');
        $update = [
            ['name'=>'COMMENT_REVIEW','value'=>$review],
            ['name'=>'COMMENT_SEND_EMAIL','value'=>$send_email],
            ['name'=>'EMAIL_RECEIVE','value'=>$email_receive]
        ];
        $result = (new AdminConfig())->saveAll($update);
        if($result === false){
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR,'操作失败');
        }else{
            return $this->buildSuccess(['result'=>$result]);
        }
    }

}