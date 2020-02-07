<?php

use PHPUnit\Framework\TestCase;
use Rbit\Milk\Xyz\Space\XyzSpaceFeature;

class XyzSpaceFeatureTest extends TestCase
{
    public function testGetFeaturesStatusCode()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $xyzSpaceFeature = XyzSpaceFeature::setToken($xyzToken)->iterate("bB6WZ2Sb")->getResponse();
        $this->assertEquals(200, $xyzSpaceFeature->getStatusCode(), "Testing List Feature");
    }
    public function testGetFeaturesStructure()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken)->iterate("bB6WZ2Sb")->get();
        $this->assertEquals("FeatureCollection", $xyzSpaceFeature->type, "Check FeatureCollection");
        $this->assertIsArray( $xyzSpaceFeature->features, "Check Features");
        $this->assertGreaterThan( 1, count($xyzSpaceFeature->features) , "Check lenght Features");
        //$feature = array_rand($xyzSpaceFeature->features, 1);
        $feature = $xyzSpaceFeature->features[0];
        //for ($i=0; $i < count($xyzSpaceFeature->features); $i++) {
            //$feature = $xyzSpaceFeature->features[$i];
            $this->assertEquals("Feature", $feature->type, "Check Feature type");
            $this->assertObjectHasAttribute("geometry", $feature, "Check Geometry");
            $this->assertObjectHasAttribute("properties", $feature, "Check Properties");

        //}

    }
    public function testGetFeaturesLimit()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken)->iterate("bB6WZ2Sb")->limit(2)->get();
        $this->assertEquals("FeatureCollection", $xyzSpaceFeature->type, "Check FeatureCollection");
        $this->assertIsArray( $xyzSpaceFeature->features, "Check Features");
        $this->assertEquals( 2, count($xyzSpaceFeature->features) , "Check lenght Features is 2");

        //$feature = array_rand($xyzSpaceFeature->features, 1);
        $feature = $xyzSpaceFeature->features[0];

        //for ($i=0; $i < count($xyzSpaceFeature->features); $i++) {
            //$feature = $xyzSpaceFeature->features[$i];
            $this->assertEquals("Feature", $feature->type, "Check Feature type");
            $this->assertObjectHasAttribute("geometry", $feature, "Check Geometry");
            $this->assertObjectHasAttribute("properties", $feature, "Check Properties");

        //}

    }

    public function testGetFeaturesLimitAndSelection()
    {
        $xyzToken = getenv('XYZ_ACCESS_TOKEN');
        $xyzSpaceFeature = XyzSpaceFeature::instance($xyzToken)->iterate("bB6WZ2Sb")->limit(2)->selection(["p.loc", "p.time"])->get();
        $this->assertEquals("FeatureCollection", $xyzSpaceFeature->type, "Features, Limit and Selection: Check FeatureCollection");
        $this->assertIsArray( $xyzSpaceFeature->features, "Feattures, Limit and Selection: Check Features is array");
        $this->assertEquals( 2, count($xyzSpaceFeature->features) , "Check lenght Features is 2");

        //$feature = array_rand($xyzSpaceFeature->features, 1);
        $feature = $xyzSpaceFeature->features[0];

        //for ($i=0; $i < count($xyzSpaceFeature->features); $i++) {
            //$feature = $xyzSpaceFeature->features[$i];
            $this->assertEquals("Feature", $feature->type, "Check Feature type");
            $this->assertObjectHasAttribute("geometry", $feature, "Check Geometry");
            $this->assertObjectHasAttribute("properties", $feature, "Check Properties");

        //}

    }

}
