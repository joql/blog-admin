<?php
/**
 * Created by PhpStorm.
 * User: Joql
 * Date: 2018/4/2
 * Time: 21:56
 */

namespace app\admin\controller;


use app\model\BlogArticl;
use app\util\Tools;

class Article extends Base
{
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

        /*foreach ($listInfo as $key => $value) {
            if (isset($userData[$value['id']])) {
                $listInfo[$key]['lastLoginIp'] = long2ip($userData[$value['id']]['lastLoginIp']);
                $listInfo[$key]['loginTimes'] = $userData[$value['id']]['loginTimes'];
                $listInfo[$key]['lastLoginTime'] = date('Y-m-d H:i:s', $userData[$value['id']]['lastLoginTime']);
            }
            $listInfo[$key]['regIp'] = long2ip($listInfo[$key]['regIp']);
            if (isset($userGroup[$value['id']])) {
                $listInfo[$key]['groupId'] = explode(',', $userGroup[$value['id']]['groupId']);
            } else {
                $listInfo[$key]['groupId'] = [];
            }
        }*/

        return $this->buildSuccess([
            'list'  => $listInfo,
            'count' => $count
        ]);
    }


}