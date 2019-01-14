<?php

declare(strict_types=1);

namespace Tests\Unit;

/** Dummy class necessary for sqlite testing. */
class wpdb
{
    public $termmeta = 'termmeta';
    public $terms = 'terms';
    public $term_taxonomy = 'term_taxonomy';
    public $term_relationships = 'term_relationships';
    public $commentmeta = 'commentmeta';
    public $comments = 'comments';
    public $links = 'links';
    public $options = 'options';
    public $postmeta = 'postmeta';
    public $posts = 'posts';
    public $users = 'users';
    public $usermeta = 'usermeta';
    public $blogs = 'blogs';
    public $blog_versions = 'blog_versions';
    public $registration_log = 'registration_log';
    public $site = 'site';
    public $sitemeta = 'sitemeta';
    public $sitecategories = 'sitecategories';
    public $signups = 'signups';

    public function __construct($prefix)
    {
        foreach ($this as $key => $value) {
            $this->$key = $prefix . $value;
        }
    }

    public function get_charset_collate()
    {
        return "";
    }

    public function set_blog_id($id)
    {

    }
}