<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/5/4
 * Time: 11:37
 */

namespace app\admin\controller;


use app\util\ReturnCode;
use think\Db;
use think\Request;

class Comment extends Base
{
    public function lists(){
        $list = $this->getDataByState(0);
        return $this->buildSuccess(['list'=>$list]);
    }

    public function changeStatus(){
        $cmtid = $this->request->get('cmid');
        $state = $this->request->get('status',0);

        $result = Db::name('comment')->where(['cmtid'=>$cmtid])->update(['status'=>$state]);
        if($result == 0){
            return $this->buildFailed(ReturnCode::DB_SAVE_ERROR,'操作失败');
        }
        return $this->buildSuccess([]);
    }

    public function del(){
        $cmid = $this->request->get('cmid');
        Db::name('comment')->where(['cmtid'=>$cmid])->delete();
        return $this->buildSuccess([]);
    }

    /**
     * use for:获取分页数据供后台使用
     * auth: Joql
     * @param $is_delete 是否删除
     * @return array  评论数据
     * date:2018-05-04 11:40
     */
    private function getDataByState($is_delete){

        $list=Db::name('comment')
            ->field('c.cmtid as comment_id, a.title as comment_article, u.nickname as comment_user, c.date as comment_time, c.content as comment_content, c.status as comment_state')
            ->alias('c')
            ->join('__ARTICL__ a','a.aid=c.aid')
            ->join(['admin_user'=>'u'],'u.id=c.uid')
            ->where(array('c.is_delete'=>$is_delete))
            ->order('date desc')
            ->select();

        foreach ($list as $k=>$v){
            $list[$k]['comment_content'] = htmlspecialchars_decode($v['comment_content']);
        }

        return $list;
    }
}