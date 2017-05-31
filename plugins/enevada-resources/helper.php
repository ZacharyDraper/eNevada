<?php
// load the wordpress core
require_once(dirname(__FILE__) . '/../../../wp-load.php');

// ******* Functions ******* //

/**
 * Returns an array of objects containing all of the categories in the system 
 *
 * @return Array of objects, each object contains the id and name of a single category
 */
function en_get_categories(){
  global $wpdb;

  return $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}en_categories WHERE status = 'publish' ORDER BY name ASC;", OBJECT_K);
}

/**
 * Returns the name of a single category by its ID
 *
 * @param Int The ID of the category
 * @return String The name of the category, empty on fail
 */
function en_get_category_name($id){
  $category = new en_Category();
  $category->id = $id;
  $category->load();

  return $category->name;
}

/**
 * Returns the name of a single organization by its ID
 *
 * @param Int The ID of the organization
 * @return String The name of the organization, empty on fail
 */
function en_get_organization_name($id){
  $org = new en_Organization();
  $org->id = $id;
  $org->load();

  return $org->name;
}

/**
 * Returns an array of objects containing all of the organizations in the system 
 *
 * @param Int The ID of an organization that must be included in the return
 * @return Array of objects, each object contains the id and name of a single organization
 */
function en_get_organizations($id = 0){
  global $wpdb;

  return $wpdb->get_results("SELECT id, name FROM {$wpdb->prefix}en_orgs WHERE status = 'publish'" . ($id ? " OR id = $id": '') . " ORDER BY name ASC;", OBJECT_K);
}

/**
  * Returns an array of Resource objects
  *
  * @return Array of en_Resource objects
  */
function en_get_resources(){
  global $wpdb;

  $resources = array();

  // get all categories associations
  $categories = $wpdb->get_results("SELECT resource, category FROM {$wpdb->prefix}en_resource_categories;"); 

  // pull all active resources
  $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}en_resources WHERE status = 'publish' ORDER BY name ASC;", ARRAY_A);
  foreach($results as $r){
    $tmpObj = new en_Resource();
    foreach($r as $field => $value){
      $tmpObj->$field = $value;
    }

    // add in categories
    $cats = array();
    foreach($categories as $category){
      if($category->resource == $tmpObj->id){
        $cats[] = $category->category;
      }
    }
    $tmpObj->categories = $cats;

    // add this resource to the return
    $resources[] = $tmpObj;
  }
  return $resources;
}

// ******** Classes ******** //

/**
 * The eNevada Resource Category class contains various methods pertaining
 * to the organizations supported by the eNevada website
 */
class en_Category{

  // properties
  private $error = '';

  private $created;
  private $created_by;
  private $description;
  private $id;
  private $modified;
  private $modified_by;
  private $name;
  private $status;

  // constructor
  function __construct(){
    // set defaults
    $this->created = new DateTime('0000-00-00 00:00:00');
    $this->created_by = 0;
    $this->description = '';
    $this->id = 0;
    $this->modified = new DateTime('0000-00-00 00:00:00');
    $this->modified_by = 0;
    $this->name = '';
    $this->status = 'publish';
  }

  // getters and setters
  public function __get($property){
    if(property_exists($this, $property)){
      return $this->$property;
    }
  }

  public function __set($property, $value){
    if(property_exists($this, $property)){
      $this->$property = $value;
    }

    return $this;
  }

  // methods

  /**
   * Get the last error
   *
   * @return string
   */
  public function getLastError(){
    if($this->error){
      return $this->error;
    }else{
      return 'An unspecified error occured';
    }
  }

  /**
   * Retrieve the data for this category from the database
   */
  public function load(){
    global $wpdb;

    // only run if this category has an id
    if($this->id > 0){
      $rtnObj = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}en_categories WHERE id = {$this->id} LIMIT 1;");
      if($rtnObj){
        foreach($rtnObj as $field => $value){
          $this->$field = $value;
        }
      }
    }
  }

  /**
   * Validates and then saves the current category
   */
  public function save(){
    // validate
    if(!$this->validate()){
      return false;
    }

    return $this->saveToDatabase();
  }

  /**
   * Writes the record to the database. Updates created and modified flags.
   */
  private function saveToDatabase(){
    global $wpdb;
    $isNew = ($this->id <= 0 ? true : false);
    $reinstating = false;

    // check if this is a duplicate of an existing category
    if($duplicate = $wpdb->get_row("SELECT id, status FROM {$wpdb->prefix}en_categories WHERE name = '{$this->name}'" . ($isNew ? '' : " AND id != {$this->id}") . " LIMIT 1;")){
      // a duplicate exists
      if($duplicate->status == 'publish'){
        // this already exists and is published, don't continue
        $this->error = 'A category by this name already exists';
        return false;
      }else{
        // this organization already exists, but was trashed. Untrash it and update
        $this->status = 'publish';
        $data = array(
          'description' => $this->description,
          'modified' => current_time('mysql', 1),
          'modified_by' => get_current_user_id(),
          'status' => $this->status
        );
        $format = array(
          '%s',
          '%s',
          '%d',
          '%s'
        );
        $where = array(
          'id' => $duplicate->id
        );
        return $wpdb->update("{$wpdb->prefix}en_categories", $data, $where, $format);
      }
    }else{
      if($isNew){
        // add the new record
        $data = array(
          'description' => $this->description,
          'name' => $this->name,
          'created' => current_time('mysql', 1),
          'created_by' => get_current_user_id(),
          'status' => $this->status
        );
        $format = array(
          '%s',
          '%s',
          '%s',
          '%d',
          '%s'
        );
        $return = $wpdb->insert("{$wpdb->prefix}en_categories", $data, $format);

        // save the id
        $this->id = $wpdb->insert_id;
        
        return $return;
      }else{
        // update the record
        $data = array(
          'description' => $this->description,
          'modified' => current_time('mysql', 1),
          'modified_by' => get_current_user_id(),
          'name' => $this->name,
          'status' => $this->status
        );
        $format = array(
          '%s',
          '%s',
          '%d',
          '%s',
          '%s'
        );
        $where = array(
          'id' => $this->id
        );
        return $wpdb->update("{$wpdb->prefix}en_categories", $data, $where, $format);
      }
    }
  }

  /**
   * Trashes the current organization
   */
  public function trash(){
    if($this->id > 0){
      // load the resource
      $this->load();

      // update the status
      $this->status = 'trash';

      // save
      return $this->saveToDatabase();
    }
  }

  /**
   * Validates the current category
   */
  public function validate(){
    // variables
    $errors = array();

    // trim and sanitize each variable
    $this->description = filter_var(trim($this->description), FILTER_SANITIZE_STRING); 
    $this->id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
    $this->name = filter_var(trim($this->name), FILTER_SANITIZE_STRING);    
    $this->status = filter_var(trim($this->status), FILTER_SANITIZE_STRING);
    
    // description
    if(empty($this->description)){
      $errors[] = 'You must enter a description';
    }else{
      if(strlen($this->description) > 250){
        $errors[] = 'The description you entered is too long';
      }
    }
    
    // name
    if(empty($this->name)){
      $errors[] = 'You must enter a name';
    }else{
      if(strlen($this->name) > 50){
        $errors[] = 'The name you entered is too long';
      }
    }

    // status
    if(empty($this->status)){
      $errors[] = 'You must select a status';
    }else{
      if(!in_array($this->status, array('publish','draft','trash'))){
        $errors[] = 'You must select a status';
      }
    }

    // return
    if($errors){
      $this->error = 'The data you entered is invalid for the following reasons:';
      foreach($errors as $error){
        $this->error .= '<br> - ' . $error;
      }
      return false;
    }
    return true;
  }
}

/**
 * The eNevada Resource Organization class contains various methods pertaining
 * to the organizations supported by the eNevada website
 */
class en_Organization{

	// properties
  private $error = '';

  private $created;
  private $created_by;
  private $description;
  private $email;
  private $id;
  private $modified;
  private $modified_by;
  private $name;
  private $fname;
  private $lname;
  private $status;

	// constructor
	function __construct(){
  	// set defaults
    $this->created = new DateTime('0000-00-00 00:00:00');
    $this->created_by = 0;
    $this->description = '';
    $this->id = 0;
    $this->modified = new DateTime('0000-00-00 00:00:00');
    $this->modified_by = 0;
    $this->name = '';
    $this->status = 'publish';
    $this->email = '';
    $this->fname = '';
    $this->lname = '';
  }

	// getters and setters
	public function __get($property){
    if(property_exists($this, $property)){
      return $this->$property;
    }
  }

  public function __set($property, $value){
    if(property_exists($this, $property)){
      $this->$property = $value;
    }

    return $this;
  }

  // methods

  /**
   * Get the last error
   *
   * @return string
   */
  public function getLastError(){
    if($this->error){
      return $this->error;
    }else{
      return 'An unspecified error occured';
    }
  }

  /**
   * Retrieve the data for this category from the database
   */
  public function load(){
  	global $wpdb;

  	// only run if this category has an id
  	if($this->id > 0){
  		$rtnObj = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}en_orgs WHERE id = {$this->id} LIMIT 1;");
  		if($rtnObj){
  			foreach($rtnObj as $field => $value){
  				$this->$field = $value;
  			}
  		}
  	}
  }

  /**
   * Validates and then saves the current category
   */
  public function save(){
    // validate
    if(!$this->validate()){
      return false;
    }

    return $this->saveToDatabase();
  }

  /**
   * Writes the record to the database. Updates created and modified flags.
   */
  private function saveToDatabase(){
    global $wpdb;
    $isNew = ($this->id <= 0 ? true : false);
    $reinstating = false;

    // check if this is a duplicate of an existing organization
    if($duplicate = $wpdb->get_row("SELECT id, status FROM {$wpdb->prefix}en_orgs WHERE name = '{$this->name}'" . ($isNew ? '' : " AND id != {$this->id}") . " LIMIT 1;")){
      // a duplicate exists
      if($duplicate->status == 'publish'){
        // this already exists and is published, don't continue
        $this->error = 'An organization by this name already exists';
        return false;
      }else{
        // this organization already exists, but was trashed. Untrash it and update
        $this->status = 'publish';
        $data = array(
          'description' => $this->description,
          'email' => $this->email,
          'fname' => $this->fname,
          'lname' => $this->lname,
          'modified' => current_time('mysql', 1),
          'modified_by' => get_current_user_id(),
          'status' => $this->status
        );
        $format = array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%d',
          '%s'
        );
        $where = array(
          'id' => $duplicate->id
        );
        return $wpdb->update("{$wpdb->prefix}en_orgs", $data, $where, $format);
      }
    }else{
      if($isNew){
        // add the new record
        $data = array(
          'description' => $this->description,
          'email' => $this->email,
          'fname' => $this->fname,
          'lname' => $this->lname,
          'name' => $this->name,
          'created' => current_time('mysql', 1),
          'created_by' => get_current_user_id(),
          'status' => $this->status
        );
        $format = array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%d',
          '%s'
        );
        $return = $wpdb->insert("{$wpdb->prefix}en_orgs", $data, $format);

        // save the id
        $this->id = $wpdb->insert_id;
        
        return $return;
      }else{
        // update the record
        $data = array(
          'description' => $this->description,
          'email' => $this->email,
          'fname' => $this->fname,
          'lname' => $this->lname,
          'modified' => current_time('mysql', 1),
          'modified_by' => get_current_user_id(),
          'name' => $this->name,
          'status' => $this->status
        );
        $format = array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%d',
          '%s',
          '%s'
        );
        $where = array(
          'id' => $this->id
        );
        return $wpdb->update("{$wpdb->prefix}en_orgs", $data, $where, $format);
      }
    }
  }

  /**
   * Trashes the current organization
   */
  public function trash(){
  	if($this->id > 0){
      // load the resource
      $this->load();

      // update the status
  		$this->status = 'trash';

      // save
  		return $this->saveToDatabase();
  	}
  }

  /**
   * Validates the current category
   */
  public function validate(){
    // variables
    $errors = array();

    // trim and sanitize each variable
    $this->description = filter_var(trim($this->description), FILTER_SANITIZE_STRING); 
    $this->email = filter_var(trim($this->email), FILTER_SANITIZE_EMAIL);
    $this->fname = filter_var(trim($this->fname), FILTER_SANITIZE_STRING);
    $this->lname = filter_var(trim($this->lname), FILTER_SANITIZE_STRING);
    $this->id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
    $this->name = filter_var(trim($this->name), FILTER_SANITIZE_STRING);
    $this->status = filter_var(trim($this->status), FILTER_SANITIZE_STRING);
    
    // description
    if(empty($this->description)){
      $errors[] = 'You must enter a description for the organization';
    }else{
      if(strlen($this->description) > 500){
        $errors[] = 'The description you entered is too long';
      }
    }
    
    // email
    if(empty($this->email)){
      $errors[] = 'You must enter the email address of the organization contact';
    }elseif(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
      $errors[] = 'You email address you entered is invalid';
    }else{
      if(strlen($this->email) > 100){
        $errors[] = 'The email address you entered is too long';
      }
    }
    
    // fname
    if(empty($this->fname)){
      $errors[] = 'You must enter the first name of the organization contact';
    }else{
      if(strlen($this->fname) > 50){
        $errors[] = 'The first name you entered is too long';
      }
    }
    
    // lname
    if(empty($this->lname)){
      $errors[] = 'You must enter the last name of the organization contact';
    }else{
      if(strlen($this->lname) > 50){
        $errors[] = 'The last name you entered is too long';
      }
    }
    
    // name
    if(empty($this->name)){
      $errors[] = 'You must enter a name';
    }else{
      if(strlen($this->name) > 250){
        $errors[] = 'The name you entered is too long';
      }
    }

    // status
    if(empty($this->status)){
      $errors[] = 'You must select a status';
    }else{
      if(!in_array($this->status, array('publish','draft','trash'))){
        $errors[] = 'You must select a status';
      }
    }

    // return
    if($errors){
      $this->error = 'The data you entered is invalid for the following reasons:';
      foreach($errors as $error){
        $this->error .= '<br> - ' . $error;
      }
      return false;
    }
    return true;
  }
}

/**
 * The eNevada Resource class contains various methods pertaining
 * to the resources supported by the eNevada website
 */
class en_Resource{

  // properties
  private $error = '';

  private $categories;
  private $contact_name;
  private $created;
  private $created_by;
  private $description;
  private $email;
  private $id;
  private $modified;
  private $modified_by;
  private $name;
  private $org;
  private $slug;
  private $status;
  private $telephone;
  private $website;
  
  // constructor
  function __construct(){
    // set defaults
    $this->categories = array();
    $this->contact_name = '';
    $this->created = new DateTime('0000-00-00 00:00:00');
    $this->created_by = 0;
    $this->description = '';
    $this->email = '';
    $this->id = 0;
    $this->modified = new DateTime('0000-00-00 00:00:00');
    $this->modified_by = 0;
    $this->name = '';
    $this->org = 0;
    $this->slug = '';
    $this->status = 'publish';
    $this->telephone = '';
    $this->website = '';
  }

  // getters and setters
  public function __get($property){
    if(property_exists($this, $property)){
      return $this->$property;
    }
  }

  public function __set($property, $value){
    if(property_exists($this, $property)){
      $this->$property = $value;
    }

    return $this;
  }

  // methods

  /**
   * Generates a unique slug based on the name of this resource
   *
   * @return string
   */
  private function generateUniqueSlug(){
    global $wpdb;

    $count = 0;
    while(true){
      $slug = sanitize_title($this->name) . ($count ? '-' . $count : '');

      $rtnObj = $wpdb->get_row("SELECT id FROM {$wpdb->prefix}en_resources WHERE slug = '{$slug}' LIMIT 1;");
      if(!$rtnObj){
        break;
      }
    }

    return $slug;
  }

  /**
   * Get the last error
   *
   * @return string
   */
  public function getLastError(){
    if($this->error){
      return $this->error;
    }else{
      return 'An unspecified error occured';
    }
  }

  /**
   * Retrieve the data for this resource from the database
   */
  public function load(){
    global $wpdb;

    // only run if this resource has an id or a slug
    if($this->id > 0 || $this->slug){
      $rtnObj = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}en_resources WHERE " . ($this->id > 0 ? "id = {$this->id}" : "slug = '{$this->slug}'") . ' LIMIT 1;');
      if($rtnObj){
        foreach($rtnObj as $field => $value){
          $this->$field = $value;
        }

        // get the categories
        $this->categories = $wpdb->get_col("SELECT category FROM {$wpdb->prefix}en_resource_categories WHERE resource = {$this->id};"); 
      }
    }
  }

  /**
   * Validates and then saves the current resource
   */
  public function save(){
    // validate
    if(!$this->validate()){
      return false;
    }

    return $this->saveToDatabase();
  }

  /**
   * Saves the categories for this resource
   *
   * @return Boolean True on success, false otherwise
   */
  private function saveCategories(){
    global $wpdb;

    // remove any previously checked, but now unchecked categories
    if(false === $wpdb->query("DELETE FROM {$wpdb->prefix}en_resource_categories WHERE resource = {$this->id} AND category NOT IN (" . join(',', $this->categories) . ");")){
      $this->error = 'Unable to delete old categories.';
      return false;
    }

    // add any new categories
    $values = array();
    foreach($this->categories as $cat){
      $values[] = '(' . $this->id . ',' . $cat . ')';
    }
    if(empty($values)){
      $this->error = 'No categories available to save.';
      return false;
    }
    return (false === $wpdb->query("INSERT INTO {$wpdb->prefix}en_resource_categories (resource, category) VALUES " . join(',', $values) . " ON DUPLICATE KEY UPDATE resource = resource;") ? false : true);
  }

  /**
   * Writes the record to the database. Updates created and modified flags.
   */
  private function saveToDatabase(){
    global $wpdb;
    $isNew = ($this->id <= 0 ? true : false);
    $reinstating = false;

    // check if this is a duplicate of an existing resource
    if($duplicate = $wpdb->get_row("SELECT id, status FROM {$wpdb->prefix}en_resources WHERE name = '{$this->name}'" . ($isNew ? '' : " AND id != {$this->id}") . " LIMIT 1;")){
      // a duplicate exists
      if($duplicate->status == 'publish'){
        // this already exists and is published, don't continue
        $this->error = 'A resource by this name already exists';
        return false;
      }else{
        // this resource already exists, but was trashed. Untrash it and update
        $this->status = 'publish';
        $data = array(
          'contact_name' => $this->contact_name,
          'description' => $this->description,
          'email' => $this->email,
          'modified' => current_time('mysql', 1),
          'modified_by' => get_current_user_id(),
          'org' => $this->org,
          'slug' => $this->slug,
          'status' => $this->status,
          'telephone' => $this->telephone,
          'website' => $this->website
        );
        $format = array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%d',
          '%d',
          '%s',
          '%s',
          '%s',
          '%s'
        );
        $where = array(
          'id' => $duplicate->id
        );

        $this->id = $duplicate->id;

        // save the categories
        if(!$this->saveCategories()){
          return false;
        }

        return $wpdb->update("{$wpdb->prefix}en_resources", $data, $where, $format);
      }
    }else{
      if($isNew){
        // add the new record
        $data = array(
          'contact_name' => $this->contact_name,
          'description' => $this->description,
          'email' => $this->email,
          'name' => $this->name,
          'created' => current_time('mysql', 1),
          'created_by' => get_current_user_id(),
          'org' => $this->org,
          'slug' => $this->slug,
          'status' => $this->status,
          'telephone' => $this->telephone,
          'website' => $this->website
        );
        $format = array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%s',
          '%d',
          '%d',
          '%s',
          '%s',
          '%s',
          '%s'
        );
        $return = $wpdb->insert("{$wpdb->prefix}en_resources", $data, $format);

        // save the id
        $this->id = $wpdb->insert_id;

        // save the categories
        if(!$this->saveCategories()){
          return false;
        }
        
        return $return;
      }else{
        // update the record
        $data = array(
          'contact_name' => $this->contact_name,
          'description' => $this->description,
          'email' => $this->email,
          'modified' => current_time('mysql', 1),
          'modified_by' => get_current_user_id(),
          'name' => $this->name,
          'org' => $this->org,
          'slug' => $this->slug,
          'status' => $this->status,
          'telephone' => $this->telephone,
          'website' => $this->website
        );
        $format = array(
          '%s',
          '%s',
          '%s',
          '%s',
          '%d',
          '%s',
          '%d',
          '%s',
          '%s',
          '%s',
          '%s'
        );
        $where = array(
          'id' => $this->id
        );

        // save the categories
        if(!$this->saveCategories()){
          return false;
        }

        return $wpdb->update("{$wpdb->prefix}en_resources", $data, $where, $format);
      }
    }
  }

  /**
   * Trashes the current organization
   */
  public function trash(){
    if($this->id > 0){
      // load the resource
      $this->load();

      // update the status
      $this->status = 'trash';

      // save
      return $this->saveToDatabase();
    }
  }

  /**
   * Validates the current category
   */
  public function validate(){
    // variables
    $errors = array();

    // trim and sanitize each variable
    $this->contact_name = filter_var(trim($this->contact_name), FILTER_SANITIZE_STRING);
    $this->description = filter_var(trim($this->description), FILTER_SANITIZE_STRING); 
    $this->email = filter_var(trim($this->email), FILTER_SANITIZE_EMAIL);
    $this->id = filter_var($this->id, FILTER_SANITIZE_NUMBER_INT);
    $this->name = filter_var(trim($this->name), FILTER_SANITIZE_STRING);    
    $this->org = filter_var($this->org, FILTER_SANITIZE_NUMBER_INT);
    $this->slug = filter_var(trim($this->slug), FILTER_SANITIZE_STRING);  
    $this->status = filter_var(trim($this->status), FILTER_SANITIZE_STRING);
    $this->telephone = filter_var(trim($this->telephone), FILTER_SANITIZE_STRING);
    $this->website = filter_var(trim($this->website), FILTER_SANITIZE_STRING);
    $tmp_categories = array();
    foreach($this->categories as $category){
      $category = filter_var($category, FILTER_SANITIZE_NUMBER_INT);
      if($category){
        $tmp_categories[] = $category;
      }
    }
    $this->categories = array_unique($tmp_categories);

    // categories
    if(empty($this->categories)){
      $errors[] = 'You must select at least one category for this resource';
    }

    // contact name (optional)
    if(!empty($this->contact_name)){
     if(strlen($this->contact_name) > 75){
        $errors[] = 'The contact name you entered is too long';
      }
    }

    // description
    if(empty($this->description)){
      $errors[] = 'You must enter a description';
    }else{
      if(strlen($this->description) > 500){
        $errors[] = 'The description you entered is too long';
      }
    }

    // email (optional)
    if(!empty($this->email)){
      if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
        $errors[] = 'The email address you entered is invalid';
      }
    }
    
    // name
    if(empty($this->name)){
      $errors[] = 'You must enter a name';
    }else{
      if(strlen($this->name) > 50){
        $errors[] = 'The name you entered is too long';
      }
    }

    // org
    if(empty($this->org)){
      $errors[] = 'You must select an organization';
    }else{
// TO DO: Add validation here
//      if(!in_array($this->org, array('publish','draft','trash'))){
//        $errors[] = 'You must select an organization';
//      }
    }

    // slug
    if(empty($this->slug)){
      $this->slug = $this->generateUniqueSlug();
    }

    // status
    if(empty($this->status)){
      $errors[] = 'You must select a status';
    }else{
      if(!in_array($this->status, array('publish','draft','trash'))){
        $errors[] = 'You must select a status';
      }
    }

    // telephone (optional)
    if(!empty($this->telephone)){
      if(!preg_match('/^\(\d{3}\) \d{3}-\d{4}$/', $this->telephone)){
        $errors[] = 'The phone number you entered is not properly formatted. Please use (xxx) xxx-xxxx';
      }
    }

    // website (optional)
    if(!empty($this->website)){
      // TO DO: Add website validation
      if(strlen($this->website) > 100){
        $errors[] = 'The website address you entered is too long';
      }
    }

    // return
    if($errors){
      $this->error = 'The data you entered is invalid for the following reasons:';
      foreach($errors as $error){
        $this->error .= '<br> - ' . $error;
      }
      return false;
    }
    return true;
  }
}
?>