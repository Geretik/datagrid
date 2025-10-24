<?php declare(strict_types=1);

namespace App\Model\Datagrid;

use Contributte\Datagrid\Persistence\ColumnsVisibilityPersistorInterface;
use Nette\Http\Session;

final class SessionColumnsVisibilityPersistor implements ColumnsVisibilityPersistorInterface
{
	public function __construct(private Session $session) {}

	public function save(string $userContext, string $gridId, array $visible): void
	{
		$this->sec($userContext)["v.$gridId"] = array_values(array_unique(array_map('strval', $visible)));
	}

	public function load(string $userContext, string $gridId): ?array
	{
		return $this->sec($userContext)["v.$gridId"] ?? null;
	}

	public function saveDefault(string $userContext, string $gridId, array $visible): void
	{
		$this->sec($userContext)["d.$gridId"] = array_values(array_unique(array_map('strval', $visible)));
	}

	public function loadDefault(string $userContext, string $gridId): ?array
	{
		return $this->sec($userContext)["d.$gridId"] ?? null;
	}

	private function sec(string $userContext): \Nette\Http\SessionSection
	{
		return $this->session->getSection('dg.columns.' . $userContext);
	}
}
