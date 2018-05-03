<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/4/6
 * Time: 11:45
 */

namespace app\admin\controller;


use app\model\BlogCategory;
use app\util\Tools;
use think\Db;

class ArticleDir extends Base
{

    public function lists(){
        $listinfo = Db::name('category')
            ->field('cid as category_id, cname as category_name, keywords as category_key, description as category_desc, pid, sort')
            ->order('sort', 'ASC')->select();
        $listinfo = formatTree(listToTree($listinfo,'category_id','pid'),0,'category_name');
        return $this->buildSuccess([
            'list'  => $listinfo
        ]);
    }

    /**
     * use for:推送文章
     * auth: Joql
     * @return array
     * date:2018-04-07 12:18
     */
    public function push(){
        $cid = $this->request->post('id');
        $cname = $this->request->post('name');
        $keywords = $this->request->post('keyw');
        $description = $this->request->post('desc');
        $sort = $this->request->post('sort');
        $pid = $this->request->post('fid');

        $save = array(
            'cname'     => $cname,
            'keywords'  => $keywords,
            'description'=>$description,
            'sort'      => $sort,
            'pid'       => $pid
        );
        if(empty($cid)){
            Db::name('category')->insert($save);
            $insert_id = Db::name('category')->getLastInsID();
            if(empty($insert_id)){
                return $this->buildFailed(ReturnCode::DB_SAVE_ERROR,'分类添加失败');
            }
        }else{
            Db::name('category')->where(['cid'=>$cid])->update($save);
        }
        return $this->buildSuccess([]);
    }

    public function del(){
        $cid = $this->request->get('id');
        $where = ['cid'=>$cid];
        Db::name('category')->where($where)->limit(1)->delete();
        return $this->buildSuccess([]);
    }
    /**
     * use for:文章目录树
     * auth: Joql
     * @return array
     * date:2018-04-05 22:17
     */
    public function getDir(){

        $aid = $this->request->get('id');
        $where = ['aid'=>$aid];

        $cid = Db::name('articl')->field('cid')->where($where)->find();

        $list = Db::name('category')->order('sort', 'ASC')->select();
        //$list = Tools::buildArrFromObj($list);
        foreach ($list as $k=>$v){
            if($v['cid'] == $cid['cid']){
                $list[$k]['selected'] = true;
            }else{
                $list[$k]['selected'] = false;
            }
        }

        $list = listToTree($list,'cid','pid');
        $rules = [];
        $newList = buildList($list, $rules);

        return $this->buildSuccess([
            'list' => $newList
        ]);
    }

}