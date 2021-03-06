<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/28
 * Time: 下午4:24
 */
namespace Biz\Course\Dao;

use Codeages\Biz\Framework\Dao\GeneralDaoInterface;

interface CourseLessonDao extends GeneralDaoInterface
{
    public function findLessonsByCourseId($courseId);

    public function deleteLessonsByCourseId($courseId);
}
