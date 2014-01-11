<?php

namespace Sf\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
