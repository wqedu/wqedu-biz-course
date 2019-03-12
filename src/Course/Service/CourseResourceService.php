<?php
/**
 * Created by PhpStorm.
 * User: wangqianjin
 * Date: 2019/3/12
 * Time: 3:28 PM
 */
namespace Biz\Course\Service;

use Codeages\Biz\Framework\Service\Exception\AccessDeniedException;

interface CourseResourceService
{
    public function getResource($id);

    public function createResource($resource);

    public function updateResource($id, $fields);

    public function deleteResource($id);

    public function searchResourceCount($conditions);

    public function searchResources($conditions, $sort, $start, $limit);

}