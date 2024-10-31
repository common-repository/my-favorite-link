<?php

if (!defined('ABSPATH')) {
    exit;
}

class MFL_data
{
    private $wpdb;

    public function __construct($wpdb)
    {
        $this->wpdb = $wpdb;
    }

    public function insert_link($name, $site, $category)
    {
        $name = sanitize_text_field($name);
        $site = esc_url_raw($site);
        $category = sanitize_text_field($category);

        $this->wpdb->insert(
            MFL_DB_TABLE,
            array(
                'name' => $name,
                'site' => $site,
                'category'=> $category ?? 'NONE'
            )
        );
    }

    public function insert_cat($name)
    {
        $name = sanitize_text_field($name);

        $this->wpdb->insert(
            MFL_DB_TABLE_CAT,
            array('name' => $name)
        );
    }

    public function remove_link($id)
    {
        $id = intval($id);

        $this->wpdb->delete(
            MFL_DB_TABLE,
            array('id'=> $id)
        );
    }

    public function remove_cat($id)
    {
        $id = intval($id);

        $this->wpdb->delete(
            MFL_DB_TABLE_CAT,
            array('id'=> $id)
        );
    }

    public function links($category)
    {
        $where = "";

        if(!is_null($category)) {
            $category = intval($category);
            $where .= "WHERE category = $category";
        }

        return $this->wpdb->get_results("SELECT id, name, site, category FROM " . MFL_DB_TABLE . " $where ORDER BY category ASC");
    }

    public function categories()
    {
        return $this->wpdb->get_results("SELECT id, name FROM " . MFL_DB_TABLE_CAT . " ORDER BY name ASC");
    }
}

global $wpdb;
$data = new MFL_data($wpdb);
