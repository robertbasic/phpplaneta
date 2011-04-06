<?php

class SlugTest extends PHPUnit_Framework_TestCase {
        
    public function testSimpleStringGetsSluggified() {
        $string = 'This is a simple string';
        
        $filter = new PPN_Filter_Slug();
        
        $slug = $filter->filter($string);
        
        $this->assertEquals('This-is-a-simple-string', $slug);
    }
    
    public function testMultipleDashesGetStripped() {
        $string = 'this-string---has-multiple----dashes';
        
        $filter = new PPN_Filter_Slug();
        
        $slug = $filter->filter($string);
        
        $this->assertEquals('this-string-has-multiple-dashes', $slug);
    }
    
}