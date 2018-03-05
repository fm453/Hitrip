<?php
/*
* @remark 后台路径与权限配置文件
* @lasttime 更新时间:2017-Dec-Fri 17:35:00
*/
$ac['ad'] = array();

$op['ad']['index'] = array();
$op['ad']['index']['display'] = array();
$op['ad']['index']['display']['title'] = '广告资源';
$ac['ad']['index'] = array();
$ac['ad']['index']['title'] = '默认入口';
$ac['ad']['index']['icon'] = 'fa fa-inbox';
$ac['ad']['index']['sn'] = '0';
$ac['ad']['index']['hide'] = array();
$ac['ad']['index']['op'] = $op['ad']['index'];

$op['ad']['adbox'] = array();
$op['ad']['adbox']['display'] = array();
$op['ad']['adbox']['display']['title'] = '列表清单';
$op['ad']['adbox']['modify'] = array();
$op['ad']['adbox']['modify']['title'] = '编辑';
$op['ad']['adbox']['add'] = array();
$op['ad']['adbox']['add']['title'] = '新增';
$op['ad']['adbox']['copy'] = array();
$op['ad']['adbox']['copy']['title'] = '复制编辑';
$op['ad']['adbox']['export'] = array();
$op['ad']['adbox']['export']['title'] = '导出';
$op['ad']['adbox']['import'] = array();
$op['ad']['adbox']['import']['title'] = '导入';
$ac['ad']['adbox'] = array();
$ac['ad']['adbox']['title'] = '广告位';
$ac['ad']['adbox']['icon'] = 'fa fa-tasks';
$ac['ad']['adbox']['sn'] = '10';
$ac['ad']['adbox']['hide'] = array();
$ac['ad']['adbox']['op'] = $op['ad']['adbox'];

$op['ad']['ads'] = array();
$op['ad']['ads']['display'] = array();
$op['ad']['ads']['display']['title'] = '清单';
$op['ad']['ads']['modify'] = array();
$op['ad']['ads']['modify']['title'] = '编辑';
$op['ad']['ads']['add'] = array();
$op['ad']['ads']['add']['title'] = '新增';
$op['ad']['ads']['copy'] = array();
$op['ad']['ads']['copy']['title'] = '复制编辑';
$op['ad']['ads']['export'] = array();
$op['ad']['ads']['export']['title'] = '导出';
$op['ad']['ads']['import'] = array();
$op['ad']['ads']['import']['title'] = '导入';
$ac['ad']['ads'] = array();
$ac['ad']['ads']['title'] = '广告列表';
$ac['ad']['ads']['icon'] = 'fa fa-eraser';
$ac['ad']['ads']['sn'] = '20';
$ac['ad']['ads']['hide'] = array();
$ac['ad']['ads']['op'] = $op['ad']['ads'];

$op['ad']['ppt'] = array();
$op['ad']['ppt']['display'] = array();
$op['ad']['ppt']['display']['title'] = '清单';
$op['ad']['ppt']['modify'] = array();
$op['ad']['ppt']['modify']['title'] = '编辑';
$op['ad']['ppt']['add'] = array();
$op['ad']['ppt']['add']['title'] = '新增';
$op['ad']['ppt']['copy'] = array();
$op['ad']['ppt']['copy']['title'] = '复制编辑';
$op['ad']['ppt']['export'] = array();
$op['ad']['ppt']['export']['title'] = '导出';
$op['ad']['ppt']['import'] = array();
$op['ad']['ppt']['import']['title'] = '导入';
$ac['ad']['ppt'] = array();
$ac['ad']['ppt']['title'] = '分类页Banner';
$ac['ad']['ppt']['icon'] = 'fa fa-file-image-o';
$ac['ad']['ppt']['sn'] = '30';
$ac['ad']['ppt']['hide'] = array();
$ac['ad']['ppt']['op'] = $op['ad']['ppt'];

$op['ad']['adv'] = array();
$op['ad']['adv']['display'] = array();
$op['ad']['adv']['display']['title'] = '清单';
$op['ad']['adv']['modify'] = array();
$op['ad']['adv']['modify']['title'] = '编辑';
$op['ad']['adv']['add'] = array();
$op['ad']['adv']['add']['title'] = '新增';
$op['ad']['adv']['copy'] = array();
$op['ad']['adv']['copy']['title'] = '复制编辑';
$op['ad']['adv']['export'] = array();
$op['ad']['adv']['export']['title'] = '导出';
$op['ad']['adv']['import'] = array();
$op['ad']['adv']['import']['title'] = '导入';
$ac['ad']['adv'] = array();
$ac['ad']['adv']['title'] = '幻灯片';
$ac['ad']['adv']['icon'] = 'fa fa-image';
$ac['ad']['adv']['sn'] = '40';
$ac['ad']['adv']['hide'] = array();
$ac['ad']['adv']['op'] = $op['ad']['adv'];

$op['ad']['ajax'] = array();
$op['ad']['ajax']['update'] = array();
$op['ad']['ajax']['update']['title'] = '更新';
$op['ad']['ajax']['delete'] = array();
$op['ad']['ajax']['delete']['title'] = '软删除';
$op['ad']['ajax']['clear'] = array();
$op['ad']['ajax']['clear']['title'] = '物理删除';
$ac['ad']['ajax'] = array();
$ac['ad']['ajax']['title'] = 'Ajax操作';
$ac['ad']['ajax']['icon'] = 'fa fa fa-font';
$ac['ad']['ajax']['sn'] = '1000';
$ac['ad']['ajax']['hide'] = array();
$ac['ad']['ajax']['op'] = $op['ad']['ajax'];

$do['ad'] = array();
$do['ad']['title'] = '广告资源';
$do['ad']['icon'] = 'fa fa-flag-checkered';
$do['ad']['sn'] = 'ad';
$do['ad']['hide'] = array();
$do['ad']['hide'][0] = '1';
$do['ad']['show'] = array();
$do['ad']['ac'] = $ac['ad'];