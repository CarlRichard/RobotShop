

namespace App\Tests\Services;

use App\Services\CoursService;
use PHPUnit\Framework\TestCase;

class CoursServiceTest extends TestCase
{
    private $cours = null;

    protected function setUp() : void
    {
        $this->cours = new CoursService();
    }
    public function testCalculateSumWithPositiveNumbers(): void
    {
        
        $result = $this->cours->calculateSum([1,2,3,4]);

        $this->assertEquals(10,$result);
        
    }
    public function testCalculateSumWithNegativeNumbers(): void
    {
        $result = $this->cours->calculateSum([-1,-2,-3,-4]);

        $this->assertEquals(-10,$result);
        
    }
    public function testCalculateSumWithZeroNumbers(): void
    {
        $result = $this->cours->calculateSum([0,0,0,0]);

        $this->assertEquals(0,$result);
        
    }
    public function testCalculateSumWithEmptyArray(): void
    {
        $result = $this->cours->calculateSum([]);

        $this->assertEquals(0,$result);
        
    }
    public function testReverseString(): void
    {
        $this->assertEquals('olleh',$this->cours->reverseString('hello'));
    }
    public function testReverseStringWithEmptyString(): void
    {
        $this->assertEquals('',$this->cours->reverseString(''));
    }
    public function testReverseStringWithSpecialCharacters(): void
    {
        $this->assertEquals('!dlroW olleH',$this->cours->reverseString('Hello World!'));
    }


}