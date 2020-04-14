/**
* Mysql数据结构
* 
* @author syh <794syh940@gmail.com>
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for chat_record
-- ----------------------------
DROP TABLE IF EXISTS `chat_record`;
CREATE TABLE `chat_record` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `FromId` int(11) NOT NULL COMMENT '发送用户Id',
  `ToId` int(11) NOT NULL COMMENT '接收用户Id',
  `Content` text NOT NULL COMMENT '聊天内容',
  `Type` tinyint(3) NOT NULL DEFAULT '1' COMMENT '类型 1:文本 2:图片',
  `Status` tinyint(3) NOT NULL DEFAULT '0' COMMENT '状态 0:未读 1:已读',
  `CreateTime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=146 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `Id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `UserName` varchar(20) NOT NULL COMMENT '名称',
  `Mobile` char(11) NOT NULL COMMENT '手机号',
  `Password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `Avatar` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '头像',
  `Email` varchar(20) NOT NULL COMMENT '邮箱',
  `LoginIP` varchar(20) NOT NULL COMMENT '最后登录IP地址',
  `LoginTime` int(10) NOT NULL COMMENT '最后登录时间',
  `CreateTime` int(10) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Mobile` (`Mobile`),
  KEY `UserName` (`UserName`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8 COMMENT='用户信息表';

SET FOREIGN_KEY_CHECKS = 1;
