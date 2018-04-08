<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/4/5
 * Time: 22:21
 */

namespace app\admin\controller;


use app\model\BlogTag;
use app\util\Tools;
use think\Db;

class Tag extends Base
{
    public function lists(){
        $list = (new BlogTag())->field('tid, tname as value')->select();
        $count = Db::table('blog_tag')->count();
        $list = Tools::buildArrFromObj($list);
        return $this->buildSuccess(['list'=>$list,'count'=>$count]);

    }

    public function addTag(){
        $tname = $this->request->post('tag_name');
        (new BlogTag())->insert([
            'tname'=>$tname
        ]);
        $tid = (new BlogTag())->getLastInsID();
        return $this->buildSuccess(['tid'=>$tid]);
    }

    /**
     * use for:获取文章所选标签
     * auth: Joql
     * @return array
     * date:2018-04-06 18:16
     */
    public function getSelectedTag(){
        $aid = $this->request->get('id');
        $where = ['aid'=>$aid];
        $tids = Db::table('blog_article_tag')->field('tid')->where($where)->select();
        $back = array();
        foreach ($tids as $k=>$v){
            $back[] = $v['tid'];
        }
        return $this->buildSuccess($back);
    }
}