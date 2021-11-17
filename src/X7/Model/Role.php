<?php

namespace X7\Model;


/**
 * 角色信息
 */
class Role extends Model
{
    /**
     * 角色id
     * 
     * @var string
     */
    public $roleId;

    /**
     * 角色所属小号id
     * 
     * @var string
     */
    public $guid;

    /**
     * 角色名称
     * 
     * @var string
     */
    public $roleName;

    /**
     * 角色所属区服id
     * 
     * @var string
     */
    public $serverId;

    /**
     * 角色所属区服名称
     * 
     * @var string
     */
    public $serverName = '';

    /**
     * 角色等级
     * 
     * @var string
     */
    public $roleLevel = '';

    /**
     * 角色战力
     * 
     * @var string
     */
    public $roleCE = '';

    /**
     * 角色关卡
     * 
     * @var string
     */
    public $roleStage = '';

    /**
     * 角色充值总额
     * 
     * @var float
     */
    public $roleRechargeAmount = 0.00;

}