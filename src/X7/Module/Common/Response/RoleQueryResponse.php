<?php

namespace X7\Module\Common\Response;

use RuntimeException;
use X7\Model\Role;
use X7\Module\Common\Constant\ApiMethod;
use X7\Response\CommonResponse;

class RoleQueryResponse extends CommonResponse
{
    /**
     * @var Role[]
     */
    public $guidRoles = [];

    /**
     * @var Role
     */
    public $role = [];

    protected $apiMethod = ApiMethod::ROLE_QUERY;

    public function setGuidRoles($guidRoles)
    {
        $getGuidRoles = is_object($guidRoles) ? [$guidRoles] : $guidRoles;
        foreach ($getGuidRoles as $role) {
            if (!($role instanceof Role)) {
                throw new RuntimeException("guidRoles数组内元素类型必须为 X7\Model\Role");
            }
        }
        $this->guidRoles = $getGuidRoles;
        return $this;
    }

    public function appendGuidRole(Role $role)
    {
        $this->guidRoles[] = $role;
        return $this;
    }

    public function setRole(Role $role)
    {
        $this->role = $role;
    }

}
