<?php
declare(strict_types=1);

namespace Contributte\Datagrid\DI;

use Contributte\Datagrid\Bridge\Nette\Grid\GridFactory;
use Nette\DI\CompilerExtension;

final class DatagridExtension extends CompilerExtension
{
	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();

		// Registrace GridFactory – app musí dodat ColumnsVisibilityPersistorInterface a User
		$builder->addDefinition($this->prefix('gridFactory'))
			->setFactory(GridFactory::class);
	}
}
