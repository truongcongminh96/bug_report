<?php


namespace Tests\Units;


use App\Database\PDOConnection;
use App\Database\QueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    /** @var QueryBuilder $queryBuilder */
    private $queryBuilder;

    public function setUp(): void
    {
        $pdo = new PDOConnection(Config::get('database', 'pdo'));
        $this->queryBuilder = new QueryBuilder(
            $pdo->connect()
        );
        parent::setUp();
    }

    public function testItCanCreateRecords()
    {
        $id = $this->queryBuilder->table('reports')->create($data);
        self::assertNotNull($id);
    }

    public function testInCanPerformRawQuery()
    {
        $result = $this->queryBuilder->raw("SELECT * FROM reports;");
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery()
    {
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', 1);

        var_dump($result->query);
        exit;
        self::assertNotNull($result);
        self::assertSame(1, (int)$result->id);
    }

    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', 1)
            ->where('report_type', '=', 'Report Type 1')
            ->first();
        self::assertNotNull($result);
        self::assertSame(1, (int)$result->id);
        self::assertSame('Report Type 1', $result->report_type);
    }
}
