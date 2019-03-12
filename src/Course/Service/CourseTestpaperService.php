<?php
/**
 * Created by PhpStorm.
 * User: wangqianjin
 * Date: 2019/3/12
 * Time: 3:27 PM
 */
namespace Biz\Course\Service;

use Codeages\Biz\Framework\Service\Exception\AccessDeniedException;

interface CourseTestpaperService
{
    public function getTestpaper($id);

    public function createTestpaper($question);

    public function updateTestpaper($id, $fields);

    public function deleteTestpaper($id);

    public function searchTestpaperCount($conditions);

    public function searchTestpapers($conditions, $sort, $start, $limit);

}