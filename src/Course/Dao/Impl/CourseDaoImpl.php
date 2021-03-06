<?php
/**
 * Created by PhpStorm.
 * User: qujiyong
 * Date: 2018/5/28
 * Time: 下午4:27
 */

namespace Biz\Course\Dao\Impl;

use Biz\Course\Dao\CourseDao;
use Codeages\Biz\Framework\Dao\GeneralDaoImpl;

class CourseDaoImpl extends GeneralDaoImpl implements CourseDao
{
    protected $table = 'course';

    public function declares()
    {
        return array(
            'serializes' => array(
                'goals' => 'delimiter',
                'audiences' => 'delimiter',
            ),
            'orderbys' => array(
                'lessonNum',
                'createdTime',
                'updatedTime',
                'id',
            ),
            'timestamps' => array('createdTime', 'updatedTime'),
            'conditions' => array(
                'id = :id',
                'updatedTime >= :updatedTime_GE',
                'status = :status',
                'type = :type',
                'title LIKE :titleLike',
                'createdTime >= :startTime',
                'createdTime < :endTime',
                'category LIKE :categoryLike',
                'tag LIKE :tagLike',
                'goals LIKE :goalsLike',
                'keypoints LIKE :keypointsLike',
                'audiences LIKE :audiencesLike',
                'smallPicture = :smallPicture',
                'parentId = :parentId',
                'parentId > :parentId_GT',
                'parentId IN ( :parentIds )',
                'id NOT IN ( :excludeIds )',
                'id IN ( :courseIds )',
                'lessonNum > :lessonNumGT',
            ),
            'wave_cahceable_fields' => array(),
        );
    }
}