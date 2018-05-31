<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/28
 * Time: 下午4:27
 */

namespace Biz\Course\Dao\Impl;

use Biz\Course\Dao\CourseChapterDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class CourseChapterDaoImpl extends GeneralDaoImpl implements CourseChapterDao
{
    protected $table = 'course_chapter';

    public function findChaptersByCourseId($courseId)
    {
        $sql = "SELECT * FROM {$this->table()} WHERE courseId = ? ORDER BY createdTime ASC";

        return $this->db()->fetchAll($sql, array($courseId));
    }

    //todo, add to course.delete.event
    public function deleteChaptersByCourseId($courseId)
    {
        $sql    = "DELETE FROM {$this->table} WHERE courseId = ?";
        $result = $this->db()->executeUpdate($sql, array($courseId));

        return $result;
    }

    public function declares()
    {
        return array(
            'serializes' => array(

            ),
            'orderbys' => array(
                'courseId',
                'number',
                'seq',
                'createdTime',
                'id',
            ),
            'timestamps' => array('createdTime'),
            'conditions' => array(
                'id = :id',
                'type = :type',
                'title LIKE :titleLike',
                'createdTime >= :startTime',
                'createdTime < :endTime',
                'keypoints LIKE :keypointsLike',
                'parentId = :parentId',
                'parentId > :parentId_GT',
                'parentId IN ( :parentIds )',
                'id NOT IN ( :excludeIds )',
                'id IN ( :courseIds )',
                'seq >= :seq_GTE',
                'seq <= :seq_LTE',
                'seq < :seq_LT',
                'seq > :seq_GT',
            ),
            'wave_cahceable_fields' => array(),
        );
    }
}