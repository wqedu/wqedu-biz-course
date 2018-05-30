<?php

use Phpmig\Migration\Migration;

class Course extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];
        $connection->exec("
            CREATE TABLE IF NOT EXISTS `course` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `title` varchar(1024) NOT NULL COMMENT '课程标题',
              `subtitle` varchar(1024) NOT NULL DEFAULT '' COMMENT '副标题',
              `status` enum('draft','published','closed') NOT NULL DEFAULT 'draft' COMMENT '课程状态',
              `type` varchar(255) NOT NULL DEFAULT 'normal' COMMENT '课程类型',
              `lessonNum` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '课时数量',
              `category` text DEFAULT NULL COMMENT '分类',
              `tags` text DEFAULT NULL COMMENT '标签',
              `keypoints` text DEFAULT NULL COMMENT '知识点',
              `smallPicture` varchar(255) NOT NULL DEFAULT '' COMMENT '小图片',
              `middlePicture` varchar(255) NOT NULL DEFAULT '' COMMENT '中图片',
              `largePicture` varchar(255) NOT NULL DEFAULT '' COMMENT '大图片',
              `about` text DEFAULT NULL COMMENT '课程介绍',
              `goals` text DEFAULT NULL COMMENT '课程目标',
              `audiences` text DEFAULT NULL COMMENT '适用人群',
              `parentId` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT '课程的父Id',
              `createdTime` int(10) unsigned NOT NULL COMMENT '创建时间',
              `updatedTime` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '最后更新时间',
              PRIMARY KEY (`id`),
              KEY `updatedTime` (`updatedTime`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $connection->exec("
            CREATE TABLE IF NOT EXISTS `course_chapter` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `courseId` bigint(20) unsigned NOT NULL COMMENT '课程ID',
              `type` enum('chapter','unit') NOT NULL DEFAULT 'chapter' COMMENT '章节类型：chapter为章节，unit为单元。',
              `parentId` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'parentId大于０时为单元',
              `number` int(10) unsigned NOT NULL COMMENT '同级别排序',
              `seq` int(10) unsigned NOT NULL COMMENT '同课程中章节总排序',
              `title` varchar(255) NOT NULL COMMENT '章节名称',
              `keypoints` text DEFAULT NULL COMMENT '知识点',
              `createdTime` int(10) unsigned NOT NULL COMMENT '创建时间',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $connection->exec("
            CREATE TABLE IF NOT EXISTS `course_lesson` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `courseId` bigint(20) unsigned NOT NULL COMMENT '课程ID',
              `chapterId` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '章节ID',
              `number` int(10) unsigned NOT NULL COMMENT '同级别排序',
              `seq` int(10) unsigned NOT NULL COMMENT '课程中总排序',
              `free` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否免费',
              `status` enum('unpublished','published') NOT NULL DEFAULT 'published' COMMENT '发布状态',
              `title` varchar(255) NOT NULL COMMENT '课时名称',
              `summary` text COMMENT '简介',
              `tags` text COMMENT '标签',
              `type` varchar(64) NOT NULL DEFAULT 'text' COMMENT '课时类型video/audio/ppt/document/text/testpaper',
              `content` text COMMENT '文本内容',
              `mediaId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '媒体文件ID(user_disk_file.id)',
              `mediaSource` varchar(32) NOT NULL DEFAULT '' COMMENT '媒体文件来源(self:本站上传,youku:优酷)',
              `mediaName` varchar(255) NOT NULL DEFAULT '' COMMENT '媒体文件名称',
              `mediaUri` text COMMENT '媒体文件资源名',
              `length` int(11) unsigned DEFAULT NULL COMMENT '音视频长度',
              `materialNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上传的资料数量',
              `quizNum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '测验题目数量',
              `createdTime` int(10) unsigned NOT NULL,
              `updatedTime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后更新时间',
              `testMode` enum('normal','realTime') DEFAULT 'normal' COMMENT '考试模式',
              `testStartTime` int(10) DEFAULT '0' COMMENT '实时考试开始时间',
              PRIMARY KEY (`id`),
              KEY `updatedTime` (`updatedTime`) USING BTREE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $biz = $this->getContainer();
        $connection = $biz['db'];
        $connection->exec("
            DROP TABLE `course`;
            DROP TABLE `course_chapter`;
            DROP TABLE `course_lesson`;
        ");
    }
}
