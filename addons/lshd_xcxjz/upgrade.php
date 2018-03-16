<?php
pdo_query("CREATE TABLE `ims_lshd_xcxjz_set` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `lstitle` varchar(255) NOT NULL DEFAULT '',
  `lsback` varchar(64) DEFAULT NULL,
  `versionid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$update=pdo_fetchAll("select uniacid from " . tablename('wxapp_versions')." where modules like '%lshd_xcxjz%' LIMIT 1")[0];
pdo_query("update " . tablename('lshd_xcxjz') . " set versionid=".$update['uniacid']."");
pdo_query("update " . tablename('lshd_xcxjz_ls') . " set versionid=".$update['uniacid']."");
pdo_query("update " . tablename('lshd_xcxjz_set') . " set versionid=".$update['uniacid']."");
if (pdo_tableexists('lshd_xcxjz_ls')) {
    if (!pdo_fieldexists('lshd_xcxjz_ls', 'versionid')) {
        pdo_query("ALTER TABLE " . tablename('lshd_xcxjz_ls') . " ADD `versionid` int(11) NOT NULL;update " . tablename('lshd_xcxjz_ls') . " set `versionid`='".$update['uniacid']."';");
    }
}
if (pdo_tableexists('lshd_xcxjz')) {
    if (!pdo_fieldexists('lshd_xcxjz', 'versionid')) {
        pdo_query("ALTER TABLE " . tablename('lshd_xcxjz') . " ADD `versionid` int(11) NOT NULL;update " . tablename('lshd_xcxjz') . " set `versionid`='".$update['uniacid']."';");
    }
}

