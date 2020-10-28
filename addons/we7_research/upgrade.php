<?php 
$sql="CREATE TABLE IF NOT EXISTS `ims_trace` (
  `reid` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `title` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL,
  `content` text NOT NULL,
  `information` varchar(500) NOT NULL DEFAULT '',
  `thumb` varchar(200) NOT NULL DEFAULT '',
  `inhome` tinyint(4) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `starttime` int(10) NOT NULL DEFAULT '0',
  `endtime` int(10) unsigned NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `pretotal` int(10) unsigned NOT NULL DEFAULT '1',
  `alltotal` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '预约总人数',
  `noticeemail` varchar(50) NOT NULL DEFAULT '',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '通知手机号码',
  PRIMARY KEY (`reid`),
  KEY `weid` (`weid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_research_data` (
  `redid` bigint(20) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `rerid` int(11) NOT NULL,
  `refid` int(11) NOT NULL,
  `data` varchar(800) NOT NULL,
  PRIMARY KEY (`redid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_research_fields` (
  `refid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL DEFAULT '0',
  `title` varchar(200) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `essential` tinyint(4) NOT NULL DEFAULT '0',
  `bind` varchar(30) NOT NULL DEFAULT '',
  `value` varchar(300) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `displayorder` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`refid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_research_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL,
  `reid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `ims_research_rows` (
  `rerid` int(11) NOT NULL AUTO_INCREMENT,
  `reid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rerid`),
  KEY `reid` (`reid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
";
pdo_run($sql);
if(!pdo_fieldexists("research", "reid")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `reid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("research", "weid")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `weid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("research", "title")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `title` varchar(100) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research", "description")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `description` varchar(1000) NOT NULL;");
}
if(!pdo_fieldexists("research", "content")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `content` text NOT NULL;");
}
if(!pdo_fieldexists("research", "information")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `information` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research", "thumb")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `thumb` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research", "inhome")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `inhome` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("research", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `createtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("research", "starttime")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `starttime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("research", "endtime")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `endtime` int(10) unsigned NOT NULL;");
}
if(!pdo_fieldexists("research", "status")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `status` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("research", "pretotal")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `pretotal` int(10) unsigned NOT NULL DEFAULT '1';");
}
if(!pdo_fieldexists("research", "alltotal")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `alltotal` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '预约总人数';");
}
if(!pdo_fieldexists("research", "noticeemail")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `noticeemail` varchar(50) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research", "mobile")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '通知手机号码';");
}
if(!pdo_fieldexists("research", "weid")) {
 pdo_query("ALTER TABLE ".tablename("research")." ADD   KEY `weid` (`weid`);");
}
if(!pdo_fieldexists("research_data", "redid")) {
 pdo_query("ALTER TABLE ".tablename("research_data")." ADD   `redid` bigint(20) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("research_data", "reid")) {
 pdo_query("ALTER TABLE ".tablename("research_data")." ADD   `reid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("research_data", "rerid")) {
 pdo_query("ALTER TABLE ".tablename("research_data")." ADD   `rerid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("research_data", "refid")) {
 pdo_query("ALTER TABLE ".tablename("research_data")." ADD   `refid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("research_data", "data")) {
 pdo_query("ALTER TABLE ".tablename("research_data")." ADD   `data` varchar(800) NOT NULL;");
}
if(!pdo_fieldexists("research_fields", "refid")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `refid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("research_fields", "reid")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `reid` int(11) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("research_fields", "title")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `title` varchar(200) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research_fields", "type")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `type` varchar(20) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research_fields", "essential")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `essential` tinyint(4) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("research_fields", "bind")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `bind` varchar(30) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research_fields", "value")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `value` varchar(300) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research_fields", "description")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `description` varchar(500) NOT NULL DEFAULT '';");
}
if(!pdo_fieldexists("research_fields", "displayorder")) {
 pdo_query("ALTER TABLE ".tablename("research_fields")." ADD   `displayorder` int(11) unsigned NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("research_reply", "id")) {
 pdo_query("ALTER TABLE ".tablename("research_reply")." ADD   `id` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("research_reply", "rid")) {
 pdo_query("ALTER TABLE ".tablename("research_reply")." ADD   `rid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("research_reply", "reid")) {
 pdo_query("ALTER TABLE ".tablename("research_reply")." ADD   `reid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("research_rows", "rerid")) {
 pdo_query("ALTER TABLE ".tablename("research_rows")." ADD   `rerid` int(11) NOT NULL AUTO_INCREMENT;");
}
if(!pdo_fieldexists("research_rows", "reid")) {
 pdo_query("ALTER TABLE ".tablename("research_rows")." ADD   `reid` int(11) NOT NULL;");
}
if(!pdo_fieldexists("research_rows", "openid")) {
 pdo_query("ALTER TABLE ".tablename("research_rows")." ADD   `openid` varchar(50) NOT NULL;");
}
if(!pdo_fieldexists("research_rows", "createtime")) {
 pdo_query("ALTER TABLE ".tablename("research_rows")." ADD   `createtime` int(10) NOT NULL DEFAULT '0';");
}
if(!pdo_fieldexists("research_rows", "reid")) {
 pdo_query("ALTER TABLE ".tablename("research_rows")." ADD   KEY `reid` (`reid`);");
}

 ?>