<?php
declare(strict_types=1);

namespace Contributte\Datagrid\Bridge\Nette\Grid;

use Contributte\Datagrid\Datagrid;
use Contributte\Datagrid\Persistance\ColumnsVisibilityPersistorInterface;
use Nette\Security\User;

final class GridFactory
{
	public function __construct(
		private ColumnsVisibilityPersistorInterface $persistor,
		private User $user,
	) {}

	public function create(): Datagrid
	{
		$grid = new Datagrid();

		// povolit přepínání sloupců + předej perzistor, aby si grid uměl načíst výchozí stav
		$grid->setColumnsHideable(true);
		$grid->setColumnsVisibilityPersistor($this->persistor);

		$uid = (string)($this->user->getId() ?? 'guest');

		// callbacky, které vyvolají tvoje nové handlery v Datagrid.php
		$grid->onSaveColumnsVisibility[] = function (Datagrid $g, string $gridId, array $columns) use ($uid): void {
			$this->persistor->save($uid, $gridId, $columns);
		};

		$grid->onSaveColumnsDefault[] = function (Datagrid $g, string $gridId, array $columns) use ($uid): void {
			$this->persistor->saveDefault($uid, $gridId, $columns);
		};

		return $grid;
	}
}
