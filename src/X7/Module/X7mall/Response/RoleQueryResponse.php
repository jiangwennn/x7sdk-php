<?php

namespace X7\Module\X7mall\Response;


use X7\Module\Common\Response\RoleQueryResponse as CommonRoleQueryResponse;
use X7\Module\X7mall\Constant\ApiMethod;

class RoleQueryResponse extends CommonRoleQueryResponse
{
    protected $apiMethod = ApiMethod::ROLE_QUERY;
}
