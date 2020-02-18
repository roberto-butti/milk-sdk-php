<?php

use PHPUnit\Framework\TestCase;
use Rbit\Milk\Xyz\Space\XyzSpace;
use Rbit\Milk\Xyz\Space\XyzSpaceFeature;
use Rbit\Milk\Xyz\Space\XyzSpaceFeatureEditor;
use Rbit\Milk\Utils\GeoJson;

class XyzSpaceFeatureTest extends TestCase
{

    protected static $xyzToken;
    protected static $space;
    protected static $spaceFeature;
    protected static $spaceFeatureEditor;
    protected static $spaceId;
    protected static $featureId;


    public static function setUpBeforeClass(): void
    {
        Dotenv\Dotenv::createImmutable(__DIR__ . "/../../")->load();
        self::$xyzToken = getenv('XYZ_ACCESS_TOKEN');
        self::$space = XyzSpace::instance(self::$xyzToken);
        self::$spaceFeature = XyzSpaceFeature::instance(self::$xyzToken);
        self::$spaceFeatureEditor = XyzSpaceFeatureEditor::instance(self::$xyzToken);

        $spaceTitle = "My Space";
        $spaceDescription = "Description";
        $response = self::$space->create($spaceTitle, $spaceDescription);

        $jsonResponse =  json_decode($response->getBody());
        self::$spaceId = $jsonResponse->id;

        $geoJson = new GeoJson();
        $geoJson->addPoint(41.890251, 12.492373, ["name" => "Colosseo"], 1);
        $geoJson->addPoint(52.5165, 13.37809, ["name" => "Berlin"], 2);
        self::$spaceFeatureEditor->addTags(["automatic test"])->create(self::$spaceId, $geoJson->getString());
    }

    public static function tearDownAfterClass(): void
    {
        $response = self::$space->delete(self::$spaceId);
    }

    protected  function setUp(): void
    {
        self::$space->reset();
        self::$spaceFeature->reset();
        self::$spaceFeatureEditor->reset();
    }

    protected  function tearDown(): void
    {
    }

    public function testGetFeaturesStatusCode()
    {
        $xyzSpaceFeature = self::$spaceFeature->iterate(self::$spaceId)->getResponse();
        $this->assertEquals(200, $xyzSpaceFeature->getStatusCode(), "Testing List Feature");
    }

    public function testGetFeaturesStructure()
    {
        $xyzSpaceFeature = self::$spaceFeature->iterate(self::$spaceId)->get();
        $this->assertEquals("FeatureCollection", $xyzSpaceFeature->type, "Check FeatureCollection");
        $this->assertIsArray( $xyzSpaceFeature->features, "Check Features");
        $this->assertGreaterThan( 1, count($xyzSpaceFeature->features) , "Check lenght Features");
        //$feature = array_rand($xyzSpaceFeature->features, 1);
        $feature = $xyzSpaceFeature->features[0];
        $this->assertEquals("Feature", $feature->type, "Check Feature type");
        $this->assertObjectHasAttribute("geometry", $feature, "Check Geometry");
        $this->assertObjectHasAttribute("properties", $feature, "Check Properties");
    }
    public function testGetFeaturesLimit()
    {
        $xyzSpaceFeature = self::$spaceFeature->iterate(self::$spaceId)->limit(2)->get();
        $this->assertEquals("FeatureCollection", $xyzSpaceFeature->type, "Check FeatureCollection");
        $this->assertIsArray( $xyzSpaceFeature->features, "Check Features");
        $this->assertEquals( 2, count($xyzSpaceFeature->features) , "Check lenght Features is 2");
        //$feature = array_rand($xyzSpaceFeature->features, 1);
        $feature = $xyzSpaceFeature->features[0];
        $this->assertEquals("Feature", $feature->type, "Check Feature type");
        $this->assertObjectHasAttribute("geometry", $feature, "Check Geometry");
        $this->assertObjectHasAttribute("properties", $feature, "Check Properties");
    }

    public function testGetFeaturesLimitAndSelection()
    {
        $xyzSpaceFeature = self::$spaceFeature->iterate(self::$spaceId)->limit(2)->selection(["p.name"])->get();
        $this->assertEquals("FeatureCollection", $xyzSpaceFeature->type, "Features, Limit and Selection: Check FeatureCollection");
        $this->assertIsArray( $xyzSpaceFeature->features, "Feattures, Limit and Selection: Check Features is array");
        $this->assertEquals( 2, count($xyzSpaceFeature->features) , "Check lenght Features is 2");

        $feature = $xyzSpaceFeature->features[0];
        $this->assertEquals("Feature", $feature->type, "Check Feature type");
        $this->assertObjectHasAttribute("geometry", $feature, "Check Geometry");
        $this->assertObjectHasAttribute("properties", $feature, "Check Properties");
    }

    public function testSearchSpace()
    {
        $xyzSpaceFeature = self::$spaceFeature->addSearchParams("p.name", "Colosseo");
        $result = $xyzSpaceFeature->search(self::$spaceId)->get();
        $this->assertEquals(1, count($result->features) , "Search and find 1 feature");
    }

    public function testSpatialSearchSpace()
    {
        $result = self::$spaceFeature->spatial(self::$spaceId,  41.890251, 12.492373,  1000)->get();
        $this->assertEquals(1, count($result->features), "Search Spatial and find 1 feature");
    }

    public function testSpatialSearchSpaceFluent()
    {
        $result = self::$spaceFeature
            ->latlon(41.890251, 12.492373)
            ->radius(1000)
            ->spatial(self::$spaceId)
            ->get();
        $this->assertEquals(1, count($result->features), "Search Spatial and find 1 feature");
    }

}
