<?php

namespace Nectary;

interface Feed {
  public function retrieve_items();
  public function sort_by_date( $order = 'asc' );
  public function get_items();
  public function set_items( $items );
  public function get_unique_items();
}
