<?php

declare(strict_types=1);

namespace App\Support;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

final class DataTableEngine
{
    private const SEARCH_MAX_LENGTH = 100;

    private Builder $baseQuery;

    /** @var array<int, string|null> */
    private array $columns = [];

    /** @var array<int, string> */
    private array $searchColumns = [];

    /** @var Closure(Builder): void */
    private Closure $defaultOrderApplier;

    /** @var Closure(object): array */
    private Closure $rowMapper;

    /** @var Closure(object): array<string, mixed>|null */
    private ?Closure $rowMeta = null;

    /** @var array<string, mixed> */
    private array $extraPayload = [];

    private function __construct(private readonly Request $request)
    {
        $this->rowMapper = static fn (object $row): array => [(array) $row];
        $this->defaultOrderApplier = static function (Builder $q): void {
            $q->orderBy('id', 'desc');
        };
    }

    public static function make(Request $request): self
    {
        return new self($request);
    }

    public function query(Builder $baseQuery): self
    {
        $this->baseQuery = $baseQuery;

        return $this;
    }

    /**
     * DataTables columns mapping (index => DB column or null for actions).
     *
     * @param array<int, string|null> $columns
     */
    public function columns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Columns used for GLOBAL search.
     *
     * @param array<int, string> $columns
     */
    public function searchColumns(array $columns): self
    {
        $this->searchColumns = $columns;

        return $this;
    }

    /** Default order (simple). */
    public function defaultOrder(string $column, string $dir = 'desc'): self
    {
        $dir = strtolower($dir) === 'asc' ? 'asc' : 'desc';

        $this->defaultOrderApplier = static function (Builder $q) use ($column, $dir): void {
            $q->orderBy($column, $dir);
        };

        return $this;
    }

    /**
     * Default order (custom), e.g. CASE, complex logic.
     * Do NOT use user input inside $sql; use bindings for variable values.
     *
     * @param array<int, mixed> $bindings
     */
    public function defaultOrderRaw(string $sql, array $bindings = []): self
    {
        $this->defaultOrderApplier = static function (Builder $q) use ($sql, $bindings): void {
            $q->orderByRaw($sql, $bindings);
        };

        return $this;
    }

    /**
     * Row -> cells mapper.
     *
     * @param Closure(object): array $mapper
     */
    public function map(Closure $mapper): self
    {
        $this->rowMapper = $mapper;

        return $this;
    }

    /**
     * Optional row meta (DT_RowId etc). When set, each row in data is an associative array
     * with meta keys plus __cells (numeric array of cell values). On the frontend, read
     * cell data from row.__cells when using this.
     *
     * @param Closure(object): array<string, mixed> $meta
     */
    public function rowMeta(Closure $meta): self
    {
        $this->rowMeta = $meta;

        return $this;
    }

    /**
     * Add extra keys to payload (e.g. task_current_page).
     *
     * @param array<string, mixed> $extra
     */
    public function extra(array $extra): self
    {
        $this->extraPayload = array_merge($this->extraPayload, $extra);

        return $this;
    }

    /**
     * Execute and return DataTables 1.10 payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $this->guardConfig();

        $recordsTotal = (clone $this->baseQuery)->count();

        $filteredQuery = clone $this->baseQuery;
        $this->applyWhere($filteredQuery);
        $recordsFiltered = $filteredQuery->count();

        $dataQuery = clone $this->baseQuery;
        $this->applyWhere($dataQuery);
        $this->applyOrder($dataQuery);
        $this->applyLimit($dataQuery);

        $rows = $dataQuery->get();

        $data = [];
        foreach ($rows as $row) {
            $cells = ($this->rowMapper)($row);

            if ($this->rowMeta !== null) {
                $meta = ($this->rowMeta)($row);
                $meta['__cells'] = $cells;
                $data[] = $meta;

                continue;
            }

            $data[] = $cells;
        }

        $payload = [
            'draw' => (int) $this->request->input('draw', 1),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return array_merge($payload, $this->extraPayload);
    }

    private function guardConfig(): void
    {
        if (!isset($this->baseQuery)) {
            throw new \RuntimeException('DataTableEngine: missing base query.');
        }
        if ($this->columns === []) {
            throw new \RuntimeException('DataTableEngine: missing columns mapping.');
        }
        if ($this->searchColumns === []) {
            $this->searchColumns = array_values(array_filter(
                $this->columns,
                static fn ($c) => is_string($c) && $c !== ''
            ));
        }
    }

    private function applyWhere(Builder $query): void
    {
        $global = $this->request->input('search.value', '');
        $global = is_string($global) ? substr(trim($global), 0, self::SEARCH_MAX_LENGTH) : '';

        if ($global !== '') {
            $cols = $this->searchColumns;
            $query->where(function (Builder $q) use ($cols, $global): void {
                foreach ($cols as $col) {
                    $q->orWhere($col, 'like', '%' . $global . '%');
                }
            });
        }

        $colsInput = $this->request->input('columns', []);
        if (!is_array($colsInput)) {
            return;
        }

        foreach ($colsInput as $i => $colInput) {
            $val = $colInput['search']['value'] ?? '';
            $val = is_string($val) ? substr(trim($val), 0, self::SEARCH_MAX_LENGTH) : '';
            if ($val === '') {
                continue;
            }

            $colName = $this->columns[(int) $i] ?? null;
            if (!is_string($colName) || $colName === '') {
                continue;
            }

            $query->where($colName, 'like', '%' . $val . '%');
        }
    }

    private function applyOrder(Builder $query): void
    {
        $orderInput = $this->request->input('order', []);
        if (!is_array($orderInput) || empty($orderInput)) {
            ($this->defaultOrderApplier)($query);

            return;
        }

        $first = $orderInput[0];
        $colIndex = (int) ($first['column'] ?? -1);
        $dir = strtolower((string) ($first['dir'] ?? 'asc')) === 'desc' ? 'desc' : 'asc';

        $colName = $this->columns[$colIndex] ?? null;
        if (!is_string($colName) || $colName === '') {
            ($this->defaultOrderApplier)($query);

            return;
        }

        $query->orderBy($colName, $dir);
    }

    private function applyLimit(Builder $query): void
    {
        $start = filter_var($this->request->input('start'), FILTER_VALIDATE_INT);
        $length = filter_var($this->request->input('length'), FILTER_VALIDATE_INT);

        if ($start !== false && $start >= 0 && $length !== false && $length > 0) {
            $query->offset($start)->limit($length);
        }
    }
}
