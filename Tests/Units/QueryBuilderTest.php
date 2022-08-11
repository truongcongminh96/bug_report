<?php


namespace Tests\Units;


use App\Database\QueryBuilder;
use App\Exception\InvalidArgumentException;
use App\Helpers\DbQueryBuilderFactory;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    /** @var QueryBuilder $queryBuilder */
    private QueryBuilder $queryBuilder;

    public function setUp(): void
    {
        $this->queryBuilder = DbQueryBuilderFactory::make(
            'database', 'pdo', ['db_name' => 'bug_app_testing']
        );

        $this->queryBuilder->beginTransaction();
        parent::setUp();
    }

    public function testItCanCreateRecords()
    {
        $id = $this->insertIntoTable();
        self::assertNotNull($id);
    }

    public function testInCanPerformRawQuery()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->raw("SELECT * FROM reports;")->get();
        self::assertNotNull($result);
    }

    public function testItCanPerformSelectQuery()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', $id)
            ->runQuery()
            ->first();

        self::assertNotNull($result);
        self::assertSame((int)$id, (int)$result->id);
    }

    public function testItCanPerformSelectQueryWithMultipleWhereClause()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder
            ->table('reports')
            ->select('*')
            ->where('id', $id)
            ->where('report_type', '=', 'Report Type 1')
            ->runQuery()
            ->get();

        self::assertNotNull($result);
        self::assertSame((int)$id, (int)$result[0]->id);
        self::assertSame('Report Type 1', $result[0]->report_type);
    }

    public function testItCanFindById()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->select('*')->find($id);

        self::assertNotNull($result);
        self::assertSame((int)$id, (int)$result->id);
        self::assertSame('Report Type 1', $result->report_type);
    }

    public function testItCanFindOneByGivenValue()
    {
        $id = $this->insertIntoTable();
        $result = $this->queryBuilder->select('*')->findOneBy('report_type', 'Report Type 1');

        self::assertNotNull($result);
        self::assertSame((int)$id, (int)$result->id);
        self::assertSame('Report Type 1', $result->report_type);
    }

    public function testItCanFindOneByGivenRecord()
    {
        $id = $this->insertIntoTable();

        $count = $this->queryBuilder->table('reports')->update([
            'report_type' => 'Report Type 1 updated'
        ])->where('id', $id)->runQuery()->affected();
        self::assertEquals(1, $count);

        $result = $this->queryBuilder->select('*')->find($id);
        self::assertNotNull($result);
        self::assertSame((int)$id, (int)$result->id);
        self::assertSame('Report Type 1 updated', $result->report_type);
    }

    public function testItCanDeleteGivenId()
    {
        $id = $this->insertIntoTable();

        $count = $this->queryBuilder->table('reports')->delete()->where('id', $id)->runQuery()->affected();
        self::assertEquals(1, $count);

        $result = $this->queryBuilder->select('*')->find($id);
        self::assertNull($result);
    }

    /**
     * This method is called after each test.
     */
    public function tearDown(): void
    {
        $this->queryBuilder->rollback();
        parent::tearDown();
    }

    /**
     * @return mixed
     * @throws InvalidArgumentException
     */
    private function insertIntoTable(): mixed
    {
        $data = [
            'report_type' => 'Report Type 1',
            'message' => 'This is a dummy message',
            'link' => 'https://link.com',
            'email' => 'minh.truong@s3corp.com.vn',
            'created_at' => date('Y-m-d H:i:s')
        ];
        return $this->queryBuilder->table('reports')->create($data);
    }
}
