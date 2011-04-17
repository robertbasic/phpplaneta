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
    
    public static function invalidNewsCategoryData() {
        return array(
            array(
                array(
                    'title' => '',
                    'slug' => 'category-slug'
                ),
            ),
            array(
                array(
                    'title' => 'Category title',
                    'slug' => ''
                ),
            )
        );
    }
    
    public static function invalidCommentData() {
        return array(
            array(
                array(
                    'fk_news_id' => '',
                    'name' => 'Foo',
                    'email' => 'foo@bar.com',
                    'url' => 'bar.com',
                    'comment' => 'Comment text',
                    'honeypot' => '',
                    'js_fill' => 'filled with javascript'
                )
            ),
            array(
                array(
                    'fk_news_id' => 999,
                    'name' => 'Foo',
                    'email' => 'foo@bar.com',
                    'url' => 'bar.com',
                    'comment' => 'Comment text',
                    'honeypot' => '',
                    'js_fill' => 'filled with javascript'
                )
            ),
            array(
                array(
                    'fk_news_id' => 1,
                    'name' => '',
                    'email' => 'foo@bar.com',
                    'url' => 'bar.com',
                    'comment' => 'Comment text',
                    'honeypot' => '',
                    'js_fill' => 'filled with javascript'
                )
            ),
            array(
                array(
                    'fk_news_id' => 1,
                    'name' => 'Foo',
                    'email' => 'foo@bar',
                    'url' => 'bar.com',
                    'comment' => 'Comment text',
                    'honeypot' => '',
                    'js_fill' => 'filled with javascript'
                )
            ),
            array(
                array(
                    'fk_news_id' => 1,
                    'name' => 'Foo',
                    'email' => '',
                    'url' => 'bar.com',
                    'comment' => 'Comment text',
                    'honeypot' => '',
                    'js_fill' => 'filled with javascript'
                )
            ),
            array(
                array(
                    'fk_news_id' => 1,
                    'name' => 'Foo',
                    'email' => 'foo@bar.comm',
                    'url' => 'bar.com',
                    'comment' => '',
                    'honeypot' => '',
                    'js_fill' => 'filled with javascript'
                )
            ),
            array(
                array(
                    'fk_news_id' => 1,
                    'name' => 'Foo',
                    'email' => 'foo@bar.comm',
                    'url' => 'bar.com',
                    'comment' => 'Comment text',
                    'honeypot' => 'honeypot!',
                    'js_fill' => 'filled with javascript'
                )
            ),
            array(
                array(
                    'fk_news_id' => 1,
                    'name' => 'Foo',
                    'email' => 'foo@bar.comm',
                    'url' => 'bar.com',
                    'comment' => 'Comment text',
                    'honeypot' => '',
                    'js_fill' => ''
                )
            )
        );
    }
    
    /**
     * Tests for news, until further notice.
     */
    
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
    public function testNewsSlugMustBeUnique() {
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
    
    
    public function testValidNewsIsUpdated() {
        $data = array(
            'id' => 1,
            'title' => 'Some news',
            'slug' => 'some-news',
            'text' => 'Lorem ipsum dummy text',
            'fk_news_category_id' => 1,
            'fk_user_id' => 1,
            'active' => 1,
            'comments_enabled' => 1,
            'datetime_added' => '2011-04-16 20:28:00'
        );
        
        $returnedData = array(
            'id' => 1,
            'title' => 'Some news',
            'slug' => 'some-news',
            'text' => 'Lorem ipsum dummy text',
            'fk_news_category_id' => 1,
            'fk_user_id' => 1,
            'active' => 1,
            'comments_enabled' => 1,
            'datetime_added' => '2011-04-16 20:28:00',
            'category_slug' => 'vesti',
            'category_title' => 'Vesti',
            'firstname' => 'Admin',
            'lastname' => 'Admin'
        );
        
        $this->_model->saveNews($data);
        
        $newsAfterUpdate = $this->_model->getOneNewsById(1);
        $newsAfterUpdate = $newsAfterUpdate->toArray();
        
        $this->assertEquals($returnedData, $newsAfterUpdate);
    }
    
    /**
     * @dataProvider invalidNewsData
     */
    public function testInvalidNewsIsNotUpdated($data) {
        $data['id'] = 1;
        $this->_model->saveNews($data);
        
        $returnedData = array(
            'id' => 1,
            'title' => 'Naslov vesti 1',
            'slug' => 'slug-vesti-1',
            'text' => 'Lorem ipsum dummy text',
            'fk_news_category_id' => 1,
            'fk_user_id' => 1,
            'active' => 1,
            'comments_enabled' => 1,
            'datetime_added' => '2010-05-09 20:25:14',
            'category_slug' => 'vesti',
            'category_title' => 'Vesti',
            'firstname' => 'Admin',
            'lastname' => 'Admin'
        );
        
        $newsAfterUpdate = $this->_model->getOneNewsById(1);
        $newsAfterUpdate = $newsAfterUpdate->toArray();
        
        // unsetting to not to try to compare a few paragraphs of text...
        unset($newsAfterUpdate['text']);
        unset($returnedData['text']);
        
        $this->assertEquals($returnedData, $newsAfterUpdate);
    }
    
    /**
     * @expectedException Exception
     */
    public function testNewsSlugMustBeUniqueOnUpdate() {
        $dataOne = array(
            'id' => 1,
            'title' => 'Some news',
            'slug' => 'slug-vesti-2',
            'text' => 'Lorem ipsum dummy text',
            'fk_news_category_id' => 1,
            'fk_user_id' => 1,
            'active' => 1,
            'comments_enabled' => 1
        );
        
        $this->_model->saveNews($dataOne);
    }
    
    public function testNewsGetsDeleted() {
        $newsBeforeDelete = $this->_model->getAllNews();
        
        $this->_model->deleteNews(1);
        
        $newsAfterDelete = $this->_model->getAllNews();
        
        $this->assertEquals(12, count($newsBeforeDelete));
        $this->assertEquals(11, count($newsAfterDelete));
    }
    
    public function testAllNewsFromACategoryGetDeleted() {
        $newsBeforeDelete = $this->_model->getAllNews();
        
        $this->_model->deleteNewsFromCategory(1);
        
        $newsAfterDelete = $this->_model->getAllNews();
        
        $this->assertEquals(12, count($newsBeforeDelete));
        $this->assertEquals(8, count($newsAfterDelete));
    }
    
    /**
     * Tests for categories, until further notice.
     */
    
    public function testGetOnlyCategoriesWithNews() {
        $categories = $this->_model->getAllNewsCategoriesWithPosts();
        
        $this->assertEquals(3, count($categories));
    }
    
    public function testGetCategoryBySlug() {
        $slug = 'vesti';
        
        $data = array(
            'id' => 1,
            'slug' => 'vesti',
            'title' => 'Vesti'
        );
        
        $category = $this->_model->getOneNewsCategoryBySlug($slug);
        $category = $category->toArray();
        
        $this->assertEquals($data, $category);
    }
    
    public function testGetCategoryById() {
        $id = 1;
        
        $data = array(
            'id' => 1,
            'slug' => 'vesti',
            'title' => 'Vesti'
        );
        
        $category = $this->_model->getOneNewsCategoryById($id);
        $category = $category->toArray();
        
        $this->assertEquals($data, $category);
    }

    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetNonexistingCategoryBySlug() {
        $slug = 'no-such-category';
        
        $category = $this->_model->getOneNewsCategoryBySlug($slug);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetNonexistingCategoryById() {
        $id = 999;
        
        $category = $this->_model->getOneNewsCategoryById($id);
    }
    
    public function testValidCategoryIsAdded() {
        $before = $this->_model->getAllNewsCategories();
        
        $data = array(
            'title' => 'Some category',
            'slug' => 'some-category'
        );
        
        $this->_model->saveNewsCategory($data);
        
        $after = $this->_model->getAllNewsCategories();
        
        $this->assertEquals(4, count($before));
        $this->assertEquals(5, count($after));
    }
    
    /**
     * @dataProvider invalidNewsCategoryData
     */
    public function testInvalidCategoryIsNotAdded($data) {
        $before = $this->_model->getAllNewsCategories();
        
        $this->_model->saveNewsCategory($data);
        
        $after = $this->_model->getAllNewsCategories();
        
        $this->assertEquals(4, count($before));
        $this->assertEquals(4, count($after));
    }
    
    public function testCategorySlugGetsSluggified() {
        $data = array(
            'title' => 'Some category',
            'slug' => 'some string with -- and spaces'
        );
        
        $expectedSlug = 'some-string-with-and-spaces';
        
        $this->_model->saveNewsCategory($data);
        
        $category = $this->_model->getOneNewsCategoryBySlug($expectedSlug);
        
        $this->assertEquals($expectedSlug, $category->slug);
    }
    
    /**
     * @expectedException Exception
     */
    public function testCategorySlugMustBeUnique() {
        $dataOne = array(
            'title' => 'Some category',
            'slug' => 'vesti'
        );
        $dataTwo = array(
            'title' => 'Some other category',
            'slug' => 'unique-slug'
        );
        
        $this->_model->saveNewsCategory($dataOne);
        
        $this->_model->saveNewsCategory($dataTwo);
        
    }
    
    
    public function testValidCategoryIsUpdated() {
        $data = array(
            'id' => 1,
            'title' => 'Some category',
            'slug' => 'some-category'
        );
        
        $returnedData = array(
            'id' => 1,
            'title' => 'Some category',
            'slug' => 'some-category'
        );
        
        $this->_model->saveNewsCategory($data);
        
        $after = $this->_model->getOneNewsCategoryById(1);
        $after = $after->toArray();
        
        $this->assertEquals($returnedData, $after);
    }
    
    /**
     * @dataProvider invalidNewsCategoryData
     */
    public function testInvalidCategoryIsNotUpdated($data) {
        $data['id'] = 1;
        $this->_model->saveNewsCategory($data);
        
        $returnedData = array(
            'id' => 1,
            'title' => 'Vesti',
            'slug' => 'vesti'
        );
        
        $after = $this->_model->getOneNewsCategoryById(1);
        $after = $after->toArray();
        
        $this->assertEquals($returnedData, $after);
    }
    
    /**
     * @expectedException Exception
     */
    public function testCategorySlugMustBeUniqueOnUpdate() {
        $dataOne = array(
            'id' => 1,
            'title' => 'Some category',
            'slug' => 'alati'
        );
        
        $this->_model->saveNewsCategory($dataOne);
    }
    
    public function testCategoryGetsDeleted() {
        $before = $this->_model->getAllNewsCategories();
        
        $this->_model->deleteNewsCategory(1);
        
        $after = $this->_model->getAllNewsCategories();
        
        $this->assertEquals(4, count($before));
        $this->assertEquals(3, count($after));
    }
    
    /**
     * Tests for comments, until further notice.
     */
    
    public function testValidCommentIsAdded() {
        $data = array(
            'fk_news_id' => 1,
            'name' => 'Commenter',
            'email' => 'spammer@email.com',
            'url' => 'http://example.com',
            'comment' => 'Lorem ipsum',
            'honeypot' => '',
            'js_fill' => 'filled with javascript'
        );
        
        $before = $this->_model->getCommentsForNews(1);
        
        $this->_model->saveComment($data);
        
        $after = $this->_model->getCommentsForNews(1);
        
        $this->assertEquals(0, count($before));
        $this->assertEquals(1, count($after));
    }
    
    /**
     * @dataProvider invalidCommentData
     */
    public function testInvalidCommentIsNotAdded($data) {
        $before = $this->_model->getCommentsForNews(1);
        
        $this->_model->saveComment($data);
        
        $after = $this->_model->getCommentsForNews(1);
        
        $this->assertEquals(0, count($before));
        $this->assertEquals(0, count($after));
    }
    
    public function testValidCommentIsUpdated() {
        $insertData = array(
            'fk_news_id' => 1,
            'name' => 'Commenter',
            'email' => 'spammer@email.com',
            'url' => 'http://example.com',
            'comment' => 'Lorem ipsum',
            'honeypot' => '',
            'js_fill' => 'filled with javascript'
        );
        
        $updateData = array(
            'id' => 1,
            'fk_news_id' => 1,
            'name' => 'Commenter',
            'email' => 'foo@bar.com',
            'url' => 'http://example.com',
            'comment' => 'Lorem ipsum',
            'honeypot' => '',
            'js_fill' => 'filled with javascript'
        );
        
        $this->_model->saveComment($insertData);
        
        $this->_model->saveComment($updateData);
        
        $comment = $this->_model->getOneCommentById(1);
        
        $this->assertEquals('foo@bar.com', $comment->email);
    }
    
    /**
     * @dataProvider invalidCommentData
     */
    public function testInvalidCommentIsNotUpdated($data) {
        $insertData = array(
            'fk_news_id' => 1,
            'name' => 'Commenter',
            'email' => 'spammer@email.com',
            'url' => 'http://example.com',
            'comment' => 'Lorem ipsum',
            'honeypot' => '',
            'js_fill' => 'filled with javascript'
        );
        
        $data['id'] = 1;
        
        $this->_model->saveComment($insertData);
        
        $this->_model->saveComment($data);
        
        $comment = $this->_model->getOneCommentById(1);
        $comment = $comment->toArray();
        
        unset($comment['id']);
        unset($comment['datetime_added']);
        unset($comment['active']);
        
        unset($insertData['honeypot']);
        unset($insertData['js_fill']);
        
        $this->assertEquals($insertData, $comment);
    }
    
    public function testCommentIsDeleted() {
        $data = array(
            'fk_news_id' => 1,
            'name' => 'Commenter',
            'email' => 'spammer@email.com',
            'url' => 'http://example.com',
            'comment' => 'Lorem ipsum',
            'honeypot' => '',
            'js_fill' => 'filled with javascript'
        );
        
        $this->_model->saveComment($data);
        
        $before = $this->_model->getAllComments();
        
        $this->_model->deleteComment(1);
        
        $after = $this->_model->getAllComments();
        
        $this->assertEquals(1, count($before));
        $this->assertEquals(0, count($after));
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testNonexistingComment() {
        $this->_model->getOneCommentById(1);
    }
    
    /**
     * Tests for tags, until further notice.
     */
    
    public function testGetAllTags() {
        $tags = $this->_model->getAllNewsTags();
        
        $this->assertEquals(12, count($tags));
    }
    
    public function testGetTagsForNewsPassInteger() {
        $tags = $this->_model->getTagsForNews(1);
        
        $this->assertEquals(1, count($tags));
    }
    
    public function testGetTagsForNewsPassValidArray() {
        $tags = $this->_model->getTagsForNews(array('newsId' => 1));
        
        $this->assertEquals(1, count($tags));
    }
    
    public function testGetTagsForNewsPassInvalidArray() {
        $tags = $this->_model->getTagsForNews(array('not_a_valid_key' => 1));
        
        $this->assertEquals(0, count($tags));
    }
    
    public function testGetExistingTagById() {
        $tag = $this->_model->getOneNewsTagById(1);
        
        $this->assertEquals('tag-1', $tag->slug);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetNonexistingTagById() {
        $tag = $this->_model->getOneNewsTagById(999);
    }
    
    public function testGetExistingTagBySlug() {
        $tag = $this->_model->getOneNewsTagBySlug('tag-1');
        
        $this->assertEquals('tag-1', $tag->slug);
    }
    
    /**
     * @expectedException PPN_Exception_NotFound
     */
    public function testGetNonexistingTagBySlug() {
        $tag = $this->_model->getOneNewsTagBySlug('no-such-tag');
    }
    
}