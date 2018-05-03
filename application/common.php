<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 把返回的数据集转换成Tree
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param string $root
 * @return array
 */
function listToTree($list, $pk='id', $pid = 'fid', $child = '_child', $root = '0') {
    $tree = array();
    if(is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            $parentId =  $data[$pid];
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            }else{
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}

function formatTree($list, $lv = 0, $title = 'name', $sort ='sort'){
    $formatTree = array();
    foreach($list as $key => $val){
        $title_prefix = '';
        for( $i=0;$i<$lv;$i++ ){
            $title_prefix .= "|---";
        }
        $val['lv'] = $lv;
        $val['namePrefix'] = $lv == 0 ? '' : $title_prefix;
        $val['showName'] = $lv == 0 ? $val[$title] : $title_prefix.$val[$title];
        $val['showSort'] = $lv == 0 ? $val[$sort] : $title_prefix.$val[$sort];
        if(!array_key_exists('_child', $val)){
            array_push($formatTree, $val);
        }else{
            $child = $val['_child'];
            unset($val['_child']);
            array_push($formatTree, $val);
            $middle = formatTree($child, $lv+1, $title); //进行下一层递归
            $formatTree = array_merge($formatTree, $middle);
        }
    }
    return $formatTree;
}

/**
 * use for:批量保存
 * auth: Joql
 * @param $table
 * @param $update
 * @return int|string
 * @throws \think\Exception
 * @throws \think\exception\PDOException
 * date:2018-05-03 10:27
 */
function dbSaveAll($table, $update){
    foreach ($update as $k=>$v){
        $keys = array_keys($v);
        $result = \think\Db::name($table)->where([$keys[0] => $v[$keys[0]]])->update([$keys[1] => $v[$keys[1]]]);
        $result +=intval($result);
        unset($keys);
    }
    return $result;
}

/**
 * 构建适用前端的权限数据
 * @param $list
 * @param $rules
 * @return array
 * @author zhaoxiang <zhaoxiang051405@gmail.com>
 */
function buildList($list, $rules) {
    $newList = [];
    foreach ($list as $key => $value) {
        $newList[$key]['title'] = $value['cname'];
        $newList[$key]['selected'] = $value['selected'];
        $newList[$key]['cid'] = $value['cid'];
        //$newList[$key]['key'] = $value['url'];
        if (isset($value['_child'])) {
            $newList[$key]['expand'] = true;
            $newList[$key]['children'] = $this->buildList($value['_child'], $rules);
        } else {
            /*if (in_array($value['url'], $rules)) {
                $newList[$key]['checked'] = true;
            }*/
        }
    }

    return $newList;
}