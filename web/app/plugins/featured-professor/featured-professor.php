<?php

/*
  Plugin Name: Featured Professor Block Type
  Version: 1.0
  Author: Lala Trala
*/

require_once __DIR__ . '/FeaturedProfessor.php';

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



$featuredProfessor = new FeaturedProfessor();
