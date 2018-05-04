<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/5/4
 * Time: 11:37
 */

namespace app\admin\controller;


use think\Db;

class Comment extends Base
{
    public function lists(){
        $list = $this->getDataByState(0);
        $this->buildSuccess($list);
    }

    public function change_status(){

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
            ->field('c.*,a.title,u.nickname')
            ->alias('c')
            ->join('__ARTICLE__ a ON a.aid=c.aid')
            ->join('__USER__ u ON u.id=c.uid')
            ->where(array('c.is_delete'=>$is_delete))
            ->order('date desc')
            ->select();

        return $list;
    }
}