<?php
$sql="
CREATE TABLE `ims_lshd_xcxjz_ls` (
  `Id` varchar(36) NOT NULL,
  `title` varchar(64) NOT NULL,
  `describe` varchar(128) NOT NULL,
  `image` varchar(64) NOT NULL,
  `versionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `ims_lshd_xcxjz` (
  `Id` int(11) NOT NULL,
  `remark` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL,
  `versionid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE TABLE `ims_lshd_xcxjz_set` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `lstitle` varchar(255) NOT NULL DEFAULT '',
  `lsback` varchar(64) DEFAULT NULL,
  `versionid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";
pdo_run($sql);
?>