<?php
/**
 * 万能网页小程序模块微站定义
 *
 * @author 灵石互动
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Lshd_xcxjzModuleSite extends WeModuleSite 
{
    public function doWebIndex()
    {
        global $_W, $_GPC;
        $setindexs = pdo_fetchAll("SELECT * FROM " . tablename('lshd_xcxjz_ls') . " where versionid=:versionid", array(':versionid' => $_W['uniacid']));
        if ($_GPC['op'] == 'create') {
            $date = array(
                'Id' => $_GPC['Id'],
                'title' => $_GPC['title'],
                'describe' => $_GPC['describe'],
                'image' => $_GPC['image'],
                'versionid' => $_GPC['versionid'],
            );
            $result = pdo_insert('lshd_xcxjz_ls', $date);
            if (!empty($result)) {
                message('添加成功', $this->createWebUrl('index'));
            }
        }
        if ($_GPC['op'] == 'edit') {
            $edit = pdo_fetchAll("SELECT * FROM " . tablename('lshd_xcxjz_ls') . " where Id='" . $_GPC['Id'] . "' and versionid=" . $_W['uniacid'] . "")[0];
        }
        if ($_GPC['op'] == 'delete') {
            pdo_delete("lshd_xcxjz_ls", array('Id' => $_GPC['Id'], 'versionid' => $_W['uniacid']));
            message(" 删除成功", referer(), 'success');
        }
        if ($_GPC['op'] == 'update') {
            $date = array(
                'Id' => $_GPC['Id'],
                'title' => $_GPC['title'],
                'describe' => $_GPC['describe'],
                'image' => $_GPC['image'],
            );
            $result = pdo_update('lshd_xcxjz_ls', $date, array('Id' => $_GPC['ids'], 'versionid' => $_GPC['versionid']));
            if (!empty($result)) {
                message('更新成功！', $this->createWebUrl('index'));
            } else {
                message('错误！', $this->createWebUrl('index'));
            }
        }
        include $this->template('index');
    }
    public function doWebSlide()
    {
        global $_W, $_GPC;
        $slides = pdo_fetchAll("SELECT * FROM " . tablename('lshd_xcxjz') . " where versionid=:versionid", array(':versionid' => $_W['uniacid']));
        if ($_GPC['op'] == 'create') {
            $date = array(
                'remark' => $_GPC['remark'],
                'image' => $_GPC['image'],
                'versionid' => $_GPC['versionid'],
            );
            $result = pdo_insert('lshd_xcxjz', $date);
            if (!empty($result)) {
                message('添加成功', $this->createWebUrl('slide'));
            } else {
                message('添加失败', $this->createWebUrl('slide'));
            }
        }
        if ($_GPC['op'] == 'edit') {
            $edit = pdo_fetchAll("SELECT * FROM " . tablename('lshd_xcxjz') . " where Id=" . $_GPC['Id'] . " and versionid=" . $_W['uniacid'] . "")[0];
        }
        if ($_GPC['op'] == 'delete') {
            pdo_delete("lshd_xcxjz", array('Id' => $_GPC['Id'], 'versionid' => $_W['uniacid']));
            message(" 删除成功", referer(), 'success');
        }
        if ($_GPC['op'] == 'update') {
            $date = array(
                'remark' => $_GPC['remark'],
                'image' => $_GPC['image'],
            );
            $result = pdo_update('lshd_xcxjz', $date, array('Id' => $_GPC['Id'], 'versionid' => $_GPC['versionid']));
            if (!empty($result)) {
                message('更新成功', $this->createWebUrl('slide'));
            } else {
                message('错误！', $this->createWebUrl('slide'));
            }
        }
        include $this->template('slide');
    }
    public function doWebSetting()
    {
        global $_W, $_GPC;
        header("Content-Security-Policy: upgrade-insecure-requests");
        define('THEME_URL', MODULE_URL . 'template/static/');
        $setting = pdo_fetchAll("SELECT * FROM " . tablename('lshd_xcxjz_set')." where versionid=:versionid",array(':versionid'=>$_W['uniacid']))[0];
        if ($_GPC['op'] == 'update') {
            if (!$setting) {
                $date = array(
                    'versionid' => $_GPC['versionid'],
                    'lstitle' => $_GPC['lstitle'],
                    'lsback' => $_GPC['lsback'],
                );
                $result = pdo_insert('lshd_xcxjz_set', $date);
                if (!empty($result)) {
                    message('添加成功', $this->createWebUrl('setting'));
                } else {
                    message('添加失败', $this->createWebUrl('setting'));
                }
            } else {
                $date = array(
                    'versionid' => $_GPC['versionid'],
                    'lstitle' => $_GPC['lstitle'],
                    'lsback' => $_GPC['lsback'],
                );
                $result = pdo_update('lshd_xcxjz_set', $date, array('Id' => $_GPC['Id'], 'versionid' => $_GPC['versionid']));
                if (!empty($result)) {
                    message('更新成功', $this->createWebUrl('setting'));
                } else {
                    message('更新失败', $this->createWebUrl('setting'));
                }
            }
        }
        include $this->template('setting');
    }

}
