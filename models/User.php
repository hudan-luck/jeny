<?php
namespace jeny\models;

use sf\db\Model;

class User extends Model
{
	public static function tableName()
	{
		return 'jeny_user';	
	}

}
