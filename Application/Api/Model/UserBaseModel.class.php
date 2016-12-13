<?php
namespace Api\Model;
use Api\Model\CommonModel;
class UserBaseModel extends CommonModel {
	protected $connection = 'UAT_CAS';
	protected $tablePrefix = '';
	
}