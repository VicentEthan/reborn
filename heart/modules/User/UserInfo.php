<?php

namespace User;

class UserInfo extends \Reborn\Module\AbstractInfo
{
	protected $name = 'User';

	protected $version = '1.0';

	protected $description = 'User, Group, and Permission managament with Cataclyst\Sentry.';

	protected $author = 'K';

	protected $authorUrl = 'http://khaynote.com';

	protected $authorEmail = 'khayusaki@gmail.com';

	protected $frontendSupprot = true;

	protected $backendSupport = true;

	protected $uriPrefix = 'user';

	protected $allowToChangeUriPrefix = false;

	protected $useAsDefaultModule = true;

	protected $roles = array(
		'user.create' => 'Create',
		'user.edit' => 'Edit',
		'user.delete' => 'Delete',
		'user.group' => 'Groups',
		'user.group.create' => 'Create Group',
		'user.group.edit' => 'Edit Group',
		'user.group.delete' => 'Delete Group',
		'user.permission' => 'Permission',
		'user.permission.edit' => 'Edit Permission',
	);

}
