<?php

/**
 * The tests depend on the seed data from phpplaneta_sqlite_data.sql
 * that data is already loaded into phpplaneta.sqlite.init database
 * 
 * The seed data contains:
 *      - 12 news, the first 10 is active, the last 2 are inactive
 *      - Each news' text contains "Lorem ipsum" dummy text only
 *      - 4 news categories, the first 3 have news, the 4th doesn't
 *      - 12 tags, each news has it's own tag
 *      - 1 user
 */

class NewsTest extends PHPUnit_Framework_TestCase {
    
    /**
     *
     * @var Planet_Model_News
     */
    protected $_model = null;
    
    public function setup() {
        $initDb = realpath(APPLICATION_PATH . '/../db/sqlite/phpplaneta.sqlite.init');
        $testDb = realpath(APPLICATION_PATH . '/../db/sqlite/phpplaneta.sqlite');
        copy($initDb, $testDb);
        
        $this->_model = new Planet_Model_News();
    }
    
    public function tearDown() {
        $initDb = realpath(APPLICATION_PATH . '/../db/sqlite/phpplaneta.sqlite.init');
        $testDb = realpath(APPLICATION_PATH . '/../db/sqlite/phpplaneta.sqlite');
        copy($initDb, $testDb);
    }
    
    public static function invalidNewsData() {
        return array(
            array(
                array(
                    'title' => '',
                    'slug' => 'news-slug',
                    'text' => 'Lorem ipsum dummy text',
                    'fk_news_category_id' => 1,
                    'fk_user_id' => 1,
                    'active' => 1,
                    'comments_enabled' => 1
                ),
            ),
            array(
                array(
                    'title' => 'News title',
                    'slug' => '',
                    'text' => 'Lorem ipsum dummy text',
                    'fk_news_category_id' => 1,
                    'fk_user_id' => 1,
                    'active' => 1,
                    'comments_enabled' => 1
                ),
            ),
            array(
                array(
                    'title' => 'News title',
                    'slug' => 'news-slug',
                    'text' => '',
                    'fk_news_category_id' => 1,
                    'fk_user_id' => 1,
                    'active' => 1,
                    'comments_enabled' => 1
                ),
            ),
            array(
                array(
                    'title' => 'News title',
                    'slug' => 'news-slug',
                    'text' => 'Lorem ipsum',
                    'fk_user_id' => 1,
                    'active' => 1,
                    'comments_enabled' => 1
                )
            )
        );
    }
    
    public function testGetAllActiveNewsReturnsOnlyActiveNews() {
        $news = $this->_model->getAllActiveNews();
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(10, count($news));
    }
    
    public function testGetAllActiveNewsReturnsOnlyActiveNewsPaginated() {
        $news = $this->_model->getAllActiveNews(1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(5, $news->getCurrentItemCount());
    }
    
    public function testSearchReturnsOnlyActiveNews() {
        $searchString = 'lorem ipsum';
        
        $news = $this->_model->searchActiveNews($searchString);
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(10, count($news));
    }
    
    public function testSearchReturnsOnlyActiveNewsPaginated() {
        $searchString = 'lorem ipsum';
        
        $news = $this->_model->searchActiveNews($searchString, 1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(5, $news->getCurrentItemCount());
    }
    
    public function testGetAllNewsReturnsActiveAndInactiveNews() {
        $news = $this->_model->getAllNews();
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(12, count($news));
    }
    
    public function testGetAllNewsReturnsActiveAndInactiveNewsPaginated() {
        $news = $this->_model->getAllNews(1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(5, $news->getCurrentItemCount());
    }
    
    public function testGetOneExistingActiveNewsBySlug() {
        $slug = 'slug-vesti-1';
        
        $news = $this->_model->getOneActiveNewsBySlug($slug);
        
        $this->assertEquals($slug, $news->slug);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetOneExistingInactiveNewsBySlugThrowsException() {
        $slug = 'slug-vesti-11';
        
        $news = $this->_model->getOneActiveNewsBySlug($slug);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetOneNonexistingNewsBySlug() {
        $slug = 'no-such-news';
        
        $news = $this->_model->getOneActiveNewsBySlug($slug);
    }
    
    public function testGetOneExistingNewsById() {
        $id = '1';
        
        $news = $this->_model->getOneNewsById($id);
        
        $this->assertEquals($id, $news->id);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetOneNonexistingNewsById() {
        $id = '100';
        
        $news = $this->_model->getOneNewsById($id);
    }

    public function testGetActiveNewsFromAnExistingCategory() {
        $categorySlug = 'vesti';
        
        $news = $this->_model->getAllActiveNewsFromCategoryBySlug($categorySlug);
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(4, count($news));
    }

    public function testGetActiveNewsFromAnExistingCategoryPaginated() {
        $categorySlug = 'vesti';
        
        $news = $this->_model->getAllActiveNewsFromCategoryBySlug($categorySlug, 1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(4, $news->getCurrentItemCount());
    }

    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetActiveNewsFromANonexistingCategory() {
        $categorySlug = 'no-such-category';
        
        $news = $this->_model->getAllActiveNewsFromCategoryBySlug($categorySlug);
    }

    public function testGetActiveNewsForAnExistingTag() {
        $tagSlug = 'tag-1';
        
        $news = $this->_model->getAllActiveNewsByTagSlug($tagSlug);
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(1, count($news));
    }

    public function testGetActiveNewsForAnExistingTagPaginated() {
        $tagSlug = 'tag-1';
        
        $news = $this->_model->getAllActiveNewsByTagSlug($tagSlug, 1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(1, $news->getCurrentItemCount());
    }

    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetActiveNewsForANonexistingTag() {
        $tagSlug = 'no-such-tag';
        
        $news = $this->_model->getAllActiveNewsByTagSlug($tagSlug);
    }
    
    public function testGetActiveNewsByDate() {
        $date = '2010-05-09';
        
        $news = $this->_model->getAllActiveNewsByDate($date);
        
        $this->assertInstanceOf('Zend_Db_Table_Rowset_Abstract', $news);
        $this->assertEquals(10, count($news));
    }
    
    public function testGetActiveNewsByDatePaginated() {
        $date = '2010-05-09';
        
        $news = $this->_model->getAllActiveNewsByDate($date, 1);
        
        $this->assertInstanceOf('Zend_Paginator', $news);
        $this->assertEquals(5, $news->getCurrentItemCount());
    }
    
    public function testValidNewsIsAdded() {
        $newsBeforeInsert = $this->_model->getAllNews();
        
        $data = array(
            'title' => 'Some news',
            'slug' => 'some-news',
            'text' => 'Lorem ipsum dummy text',
            'fk_news_category_id' => 1,
            'fk_user_id' => 1,
            'active' => 1,
            'comments_enabled' => 1
        );
        
        $this->_model->saveNews($data);
        
        $newsAfterInsert = $this->_model->getAllNews();
        
        $this->assertEquals(12, count($newsBeforeInsert));
        $this->assertEquals(13, count($newsAfterInsert));
    }
    
    /**
     * @dataProvider invalidNewsData
     */
    public function testInvalidNewsIsNotAdded($data) {
        $newsBeforeInsert = $this->_model->getAllNews();
        
        $this->_model->saveNews($data);
        
        $newsAfterInsert = $this->_model->getAllNews();
        
        $this->assertEquals(12, count($newsBeforeInsert));
        $this->assertEquals(12, count($newsAfterInsert));
    }
    
    public function testNewsSlugGetsSluggified() {
        $data = array(
            'title' => 'Some news',
            'slug' => 'some string with -- and spaces',
            'text' => 'Lorem ipsum dummy text',
            'fk_news_category_id' => 1,
            'fk_user_id' => 1,
            'active' => 1,
            'comments_enabled' => 1
        );
        
        $expectedSlug = 'some-string-with-and-spaces';
        
        $this->_model->saveNews($data);
        
        $news = $this->_model->getOneActiveNewsBySlug($expectedSlug);
        
        $this->assertEquals($expectedSlug, $news->slug);
    }
    
    /**
     * @expectedException Exception
     */
    public function testSlugMustBeUnique() {
        $dataOne = array(
            'title' => 'Some news',
            'slug' => 'unique-slug',
            'text' => 'Lorem ipsum dummy text',
            'fk_news_category_id' => 1,
            'fk_user_id' => 1,
            'active' => 1,
            'comments_enabled' => 1
        );
        $dataTwo = array(
            'title' => 'Some other news',
            'slug' => 'unique-slug',
            'text' => 'Lorem ipsum other dummy text',
            'fk_news_category_id' => 2,
            'fk_user_id' => 1,
            'active' => 0,
            'comments_enabled' => 1
        );
        
        $this->_model->saveNews($dataOne);
        
        $this->_model->saveNews($dataTwo);
    }
}