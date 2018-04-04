<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/4/2
 * Time: 21:56
 */

namespace app\admin\controller;


use app\model\BlogArticl;
use app\model\BlogArticleTag;
use app\util\Tools;
use app\util\ReturnCode;
use think\Db;

class Article extends Base
{
    /**
     * use for: 获取文章列表
     * auth: Joql
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * date:2018-04-04 16:29
     */
    public function lists(){
        $limit = $this->request->get('size', config('apiAdmin.ADMIN_LIST_DEFAULT'));
        $start = $limit * ($this->request->get('page', 1) - 1);
        $type = $this->request->get('type', '');
        $keywords = $this->request->get('keywords', '');
        $status = $this->request->get('status', '');

        $where = [];
        if ($status === '1' || $status === '0') {
            $where['status'] = $status;
        }
        if ($type) {
            switch ($type) {
                case 1:
                    $where['username'] = ['like', "%{$keywords}%"];
                    break;
                case 2:
                    $where['nickname'] = ['like', "%{$keywords}%"];
                    break;
            }
        }

        $listInfo = (new BlogArticl())
            ->alias('ba')
            ->field('ba.aid, ba.title as article_title, ba.author as auth_name, ba.keywords, ba.is_show, ba.is_delete, ba.is_top, ba.is_original as is_origin, ba.click, ba.addtime as push_time, ba.cid, bcg.cname as category_name')
            ->join('blog_category bcg','ba.cid=bcg.cid','left')
            ->where($where)
            ->order('addtime', 'DESC')
            ->limit($start, $limit)->select();
        $count = (new BlogArticl())->where($where)->count();
        $listInfo = Tools::buildArrFromObj($listInfo);
        /*$idArr = array_column($listInfo, 'aid');

        $userData = AdminUserData::all(function($query) use ($idArr) {
            $query->whereIn('uid', $idArr);
        });
        $userData = Tools::buildArrFromObj($userData);
        $userData = Tools::buildArrByNewKey($userData, 'uid');

        $userGroup = AdminAuthGroupAccess::all(function($query) use ($idArr) {
            $query->whereIn('uid', $idArr);
        });
        $userGroup = Tools::buildArrFromObj($userGroup);
        $userGroup = Tools::buildArrByNewKey($userGroup, 'uid');*/

        foreach ($listInfo as $key => $value) {
            $where_tags['aid'] = $value['aid'];
            $tags = (new BlogArticleTag())
                ->alias('bat')
                ->field('bt.tname')
                ->join('blog_tag bt','bat.tid=bt.tid','left')
                ->where($where_tags)
                ->select();
            $tmp = Tools::buildArrFromObj($tags);
            $listInfo[$key]['tag_name']='';
            foreach ($tmp as $v){
                $listInfo[$key]['tag_name'] .= $v['tname'].' ';
            }
        }

        return $this->buildSuccess([
            'list'  => $listInfo,
            'count' => $count
        ]);
    }

    /**
     * use for:原创状态
     * auth: Joql
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * date:2018-04-04 17:07
     */
    public function changeOriginState(){
        $aid = $this->request->get('id');
        $state = $this->request->get('status');

        $where['aid']=$aid;
        $save['is_original'] = $state;

        $result = Db::table('blog_articl')->where($where)->update($save);
        if($result == 0){
            $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '操作失败');
        }
        return $this->buildSuccess(['result'=>$result]);
    }

    /**
     * use for: 置顶
     * auth: Joql
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * date:2018-04-04 17:08
     */
    public function changeTopState(){
        $aid = $this->request->get('id');
        $state = $this->request->get('status');

        $where['aid']=$aid;
        $save['is_top'] = $state;

        $result = Db::table('blog_articl')->where($where)->update($save);
        if($result == 0){
            $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '操作失败');
        }
        return $this->buildSuccess(['result'=>$result]);
    }

    /**
     * use for:展示
     * auth: Joql
     * @return array
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     * date:2018-04-04 17:08
     */
    public function changeShowState(){
        $aid = $this->request->get('id');
        $state = $this->request->get('status');

        $where['aid']=$aid;
        $save['is_show'] = $state;

        $result = Db::table('blog_articl')->where($where)->update($save);
        if($result == false){
            $this->buildFailed(ReturnCode::DB_SAVE_ERROR, '操作失败');
        }
        return $this->buildSuccess(['result'=>$result]);
    }

}